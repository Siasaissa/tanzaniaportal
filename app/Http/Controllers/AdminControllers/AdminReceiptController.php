<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\PurchaseOrder;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReceiptController extends Controller
{
    protected $guard = 'company';

    /**
     * Display all receipts
     */
    public function index()
    {
        $companyId = auth()->guard($this->guard)->id();
        
        $receipts = Receipt::where('company_id', $companyId)
            ->with('purchaseOrder')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get only eligible purchase orders (not fully received yet)
        $purchaseOrders = PurchaseOrder::where('company_id', $companyId)
            ->whereIn('status', ['approved', 'ordered'])
            ->with('receipts') // Eager load receipts for calculation
            ->orderBy('po_date', 'desc')
            ->get()
            ->filter(function($po) {
                // Filter out fully received POs
                $totalReceived = $po->receipts
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_quantity_received');
                $totalOrdered = $po->total_quantity_ordered;
                
                return $totalReceived < $totalOrdered;
            });
        
        $stats = [
            'total' => Receipt::where('company_id', $companyId)->count(),
            'draft' => Receipt::where('company_id', $companyId)->where('status', 'draft')->count(),
            'completed' => Receipt::where('company_id', $companyId)->where('status', 'completed')->count(),
            'verified' => Receipt::where('company_id', $companyId)->where('status', 'verified')->count(),
            'partial' => Receipt::where('company_id', $companyId)->where('status', 'partial')->count(),
            'total_value' => Receipt::where('company_id', $companyId)->sum('total_amount'),
        ];
        
        return view('admin.receipt.index', compact('receipts', 'stats', 'purchaseOrders'));
    }

    /**
     * Show form to create new receipt
     */
    public function create()
    {
        $companyId = auth()->guard($this->guard)->id();
        
        // Get only eligible purchase orders
        $purchaseOrders = PurchaseOrder::where('company_id', $companyId)
            ->whereIn('status', ['approved', 'ordered'])
            ->with('receipts')
            ->orderBy('po_date', 'desc')
            ->get()
            ->filter(function($po) {
                $totalReceived = $po->receipts
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_quantity_received');
                $totalOrdered = $po->total_quantity_ordered;
                
                return $totalReceived < $totalOrdered;
            });
        
        $receiptNumber = Receipt::generateReceiptNumber($companyId);
        
        return view('admin.receipt.create', compact('purchaseOrders', 'receiptNumber'));
    }

    /**
     * Get PO details for receipt creation
     */
    public function getPurchaseOrderDetails($id)
    {
        $companyId = auth()->guard($this->guard)->id();
        $po = PurchaseOrder::where('company_id', $companyId)
            ->with(['receipts' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->findOrFail($id);
        
        // Check if PO is eligible
        $totalReceived = $po->receipts->sum('total_quantity_received');
        $totalOrdered = $po->total_quantity_ordered;
        
        if ($totalReceived >= $totalOrdered) {
            return response()->json([
                'error' => 'This purchase order has been fully received.',
                'fully_received' => true
            ], 400);
        }
        
        // Get PO items
        $items = $po->formatted_items;
        
        // Calculate received quantities for each item
        foreach ($items as &$item) {
            $item['quantity_ordered'] = $item['quantity'] ?? 0;
            $item['quantity_received'] = 0;
            $item['remaining'] = $item['quantity_ordered'];
            $item['price'] = $item['price'] ?? 0;
            $item['unit'] = $item['unit'] ?? 'pcs';
        }
        
        // Subtract already received quantities from all receipts
        foreach ($po->receipts as $receipt) {
            $receiptItems = $receipt->items;
            foreach ($receiptItems as $receiptItem) {
                foreach ($items as &$item) {
                    if (($item['description'] ?? '') == ($receiptItem['description'] ?? '')) {
                        $item['quantity_received'] += $receiptItem['quantity_received'] ?? 0;
                        $item['remaining'] = $item['quantity_ordered'] - $item['quantity_received'];
                    }
                }
            }
        }
        
        return response()->json([
            'po' => $po,
            'items' => $items,
            'total_ordered' => $po->total_quantity_ordered,
            'total_received' => $totalReceived,
            'remaining' => $po->total_quantity_ordered - $totalReceived,
            'is_eligible' => true
        ]);
    }

    /**
     * Store new receipt
     */
    public function store(Request $request)
    {
        $companyId = auth()->guard($this->guard)->id();
        
        // Simple validation
        $request->validate([
            'receipt_number' => 'required|string|max:100|unique:receipts,receipt_number',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'receipt_type' => 'required|in:full_delivery,partial_delivery,return,damaged_goods',
            'items' => 'required|array|min:1',
            'items.*.quantity_received' => 'required|numeric|min:0'
        ]);

        // Get PO and check eligibility
        $po = PurchaseOrder::where('company_id', $companyId)
            ->with(['receipts' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->findOrFail($request->purchase_order_id);
        
        // Check if PO is still eligible
        $totalReceived = $po->receipts->sum('total_quantity_received');
        $totalOrdered = $po->total_quantity_ordered;
        
        if ($totalReceived >= $totalOrdered) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This purchase order has been fully received. Cannot create new receipt.');
        }
        
        // Calculate totals
        $totalItems = count($request->items);
        $totalQuantity = 0;
        $totalAmount = 0;
        
        foreach ($request->items as $item) {
            $quantity = floatval($item['quantity_received'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            
            $totalQuantity += $quantity;
            $totalAmount += ($quantity * $price);
        }
        
        // Create receipt
        $receipt = Receipt::create([
            'company_id' => $companyId,
            'receipt_number' => $request->receipt_number,
            'purchase_order_id' => $request->purchase_order_id,
            'receipt_date' => $request->receipt_date,
            'received_by' => Auth::id(),
            'received_by_name' => Auth::user()->name,
            'supplier_name' => $po->supplier_name,
            'supplier_contact_person' => $po->supplier_contact_person,
            'items' => $request->items,
            'total_items_received' => $totalItems,
            'total_quantity_received' => $totalQuantity,
            'total_amount' => $totalAmount,
            'status' => 'draft',
            'receipt_type' => $request->receipt_type,
            'delivery_note_number' => $request->delivery_note_number,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'driver_contact' => $request->driver_contact,
            'condition' => $request->condition ?? 'good',
            'quality_notes' => $request->quality_notes,
            'storage_location' => $request->storage_location,
            'bin_location' => $request->bin_location,
            'notes' => $request->notes,
            'return_reason' => $request->return_reason,
        ]);

        return redirect()->route('admin.receipt.index')
            ->with('success', 'Receipt created successfully!');
    }

    /**
     * Display single receipt
     */
    public function show(Receipt $receipt)
    {
        $this->checkAccess($receipt);
        $receipt->load('purchaseOrder');
        return view('admin.receipt.show', compact('receipt'));
    }

    /**
     * Show edit form
     */
    public function edit(Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        if ($receipt->status !== 'draft') {
            return redirect()->route('admin.receipt.index')
                ->with('error', 'Only draft receipts can be edited.');
        }
        
        return view('admin.receipt.edit', compact('receipt'));
    }

    /**
     * Update receipt
     */
    public function update(Request $request, Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        if ($receipt->status !== 'draft') {
            return redirect()->route('admin.receipt.index')
                ->with('error', 'Only draft receipts can be edited.');
        }
        
        $request->validate([
            'receipt_date' => 'required|date',
            'receipt_type' => 'required|in:full_delivery,partial_delivery,return,damaged_goods',
            'items' => 'required|array|min:1',
            'items.*.quantity_received' => 'required|numeric|min:0'
        ]);
        
        // Calculate totals
        $totalItems = count($request->items);
        $totalQuantity = 0;
        $totalAmount = 0;
        
        foreach ($request->items as $item) {
            $quantity = floatval($item['quantity_received'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            
            $totalQuantity += $quantity;
            $totalAmount += ($quantity * $price);
        }
        
        $receipt->update([
            'receipt_date' => $request->receipt_date,
            'items' => $request->items,
            'total_items_received' => $totalItems,
            'total_quantity_received' => $totalQuantity,
            'total_amount' => $totalAmount,
            'receipt_type' => $request->receipt_type,
            'delivery_note_number' => $request->delivery_note_number,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'driver_contact' => $request->driver_contact,
            'condition' => $request->condition,
            'quality_notes' => $request->quality_notes,
            'storage_location' => $request->storage_location,
            'bin_location' => $request->bin_location,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('admin.receipt.index')
            ->with('success', 'Receipt updated successfully!');
    }

    /**
     * Update receipt status
     */
    public function updateStatus(Request $request, Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        $request->validate([
            'status' => 'required|in:completed,verified,cancelled'
        ]);
        
        $receipt->update([
            'status' => $request->status,
            'verification_notes' => $request->verification_notes,
            'verified_by' => $request->status === 'verified' ? Auth::id() : null,
            'verified_at' => $request->status === 'verified' ? now() : null,
        ]);
        
        return redirect()->back()
            ->with('success', 'Receipt status updated successfully!');
    }

    /**
     * Delete receipt
     */
    public function destroy(Request $request, Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        if ($receipt->status !== 'draft') {
            return redirect()->route('admin.receipt.index')
                ->with('error', 'Only draft receipts can be deleted.');
        }
        
        $request->validate([
            'delete_reason' => 'required|string|min:5'
        ]);
        
        $receipt->delete();
        
        return redirect()->route('admin.receipt.index')
            ->with('success', 'Receipt deleted successfully!');
    }

    /**
     * Generate receipt number
     */
    public function generateNumber()
    {
        $companyId = auth()->guard($this->guard)->id();
        $number = Receipt::generateReceiptNumber($companyId);
        
        return response()->json(['receipt_number' => $number]);
    }

    /**
     * Download PDF
     */
    public function download(Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        // For now, just redirect back - add PDF later
        return redirect()->back()
            ->with('info', 'PDF download will be available soon');
    }

    /**
     * Print/View PDF
     */
    public function print(Receipt $receipt)
    {
        $this->checkAccess($receipt);
        
        // For now, just show the receipt
        return $this->show($receipt);
    }

    /**
     * Check access
     */
    private function checkAccess(Receipt $receipt)
    {
        $companyId = auth()->guard($this->guard)->id();
        
        if ($receipt->company_id != $companyId) {
            abort(403, 'Unauthorized access.');
        }
    }

    // Add this method to your controller
public function debugReceipt($id)
{
    $receipt = Receipt::find($id);
    
    if (!$receipt) {
        return response()->json(['error' => 'Receipt not found'], 404);
    }
    
    // Calculate what total should be
    $calculatedTotal = 0;
    $items = $receipt->items ?? [];
    
    foreach ($items as $index => $item) {
        $quantity = floatval($item['quantity_received'] ?? 0);
        $price = floatval($item['price'] ?? 0);
        $itemTotal = $quantity * $price;
        $calculatedTotal += $itemTotal;
        
        $items[$index]['calculated_total'] = $itemTotal;
        $items[$index]['has_price'] = isset($item['price']);
        $items[$index]['price_type'] = gettype($item['price'] ?? 'null');
    }
    
    return response()->json([
        'receipt' => [
            'id' => $receipt->id,
            'receipt_number' => $receipt->receipt_number,
            'stored_total_amount' => $receipt->total_amount,
            'stored_total_amount_type' => gettype($receipt->total_amount),
            'calculated_total_amount' => $calculatedTotal,
            'difference' => $calculatedTotal - $receipt->total_amount,
            'items_count' => count($items),
        ],
        'items_analysis' => $items,
        'calculation_details' => [
            'method' => 'sum(quantity_received * price) for each item',
            'items_processed' => count($items),
        ]
    ]);
}
}