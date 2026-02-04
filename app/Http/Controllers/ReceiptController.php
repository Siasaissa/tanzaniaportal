<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\PurchaseOrder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Display all receipts
     */
   public function index()
{
    $companyId = Auth::guard('company')->id();
    $company = Company::find($companyId);
    
    // Get receipts
    $receipts = Receipt::whereBelongsTo(Auth::user())
        ->where('company_id', $companyId)
        ->with('purchaseOrder')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    // Get ALL POs for debugging - remove filters temporarily
    $allPOs = PurchaseOrder::whereBelongsTo(Auth::user())
                            ->where('company_id', $companyId)->get();
    
    // Get POs for the create modal dropdown
    $purchaseOrders = PurchaseOrder::whereBelongsTo(Auth::user())
        ->where('company_id', $companyId)
        ->orderBy('po_date', 'desc')
        ->get();
    
    $stats = [
        'total' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)->count(),
        'draft' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)
            ->where('status', 'draft')->count(),
        'completed' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)
            ->where('status', 'completed')->count(),
        'verified' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)
            ->where('status', 'verified')->count(),
        'partial' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)
            ->where('status', 'partial')->count(),
        'total_value' => Receipt::whereBelongsTo(Auth::user())->where('company_id', $companyId)->sum('total_amount'),
    ];
    
    // Debug information
    $debugInfo = [
        'company_id' => $companyId,
        'all_po_count' => $allPOs->count(),
        'all_po_statuses' => $allPOs->pluck('status')->unique()->toArray(),
        'filtered_po_count' => $purchaseOrders->count(),
        'filtered_po_numbers' => $purchaseOrders->pluck('po_number')->toArray(),
    ];
    
    \Log::info('Receipt Index Debug:', $debugInfo);
    
    return view('company.receipt.index', compact('receipts', 'company', 'stats', 'purchaseOrders', 'debugInfo'));
}
    /**
     * Show form to create new receipt
     */
    public function create()
    {
        $companyId = Auth::guard('company')->id();
        
        // Get POs that can receive goods
        $purchaseOrders = PurchaseOrder::where('company_id', $companyId)
            ->whereIn('status', ['approved', 'ordered'])
            ->where(function($query) {
                $query->where('receipt_status', 'not_received')
                      ->orWhere('receipt_status', 'partial');
            })
            ->orderBy('po_date', 'desc')
            ->get();
        
        $receiptNumber = Receipt::generateReceiptNumber($companyId);
        
        return view('company.receipt.create', compact('purchaseOrders', 'receiptNumber'));
    }

    /**
     * Get PO details for receipt creation
     */
    public function getPurchaseOrderDetails($id)
    {
        $po = PurchaseOrder::with('receipts')->findOrFail($id);
        
        // Calculate remaining quantities
        $items = $po->formatted_items;
        $receipts = $po->receipts()->where('status', '!=', 'cancelled')->get();
        
        // Calculate received quantities for each item
        foreach ($items as &$item) {
            $item['quantity_ordered'] = $item['quantity'] ?? 0;
            $item['quantity_received'] = 0;
            $item['remaining'] = $item['quantity_ordered'];
        }
        
        // Subtract already received quantities
        foreach ($receipts as $receipt) {
            $receiptItems = $receipt->formatted_items;
            foreach ($receiptItems as $receiptItem) {
                foreach ($items as &$item) {
                    if ($item['description'] == $receiptItem['description']) {
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
            'total_received' => $po->total_quantity_received,
            'remaining' => $po->remaining_quantity
        ]);
    }

    /**
     * Store new receipt
     */
    public function store(Request $request)
    {
        

        $request->validate([
            'receipt_number' => 'required|string|max:100',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'receipt_type' => 'required|in:full_delivery,partial_delivery,return,damaged_goods',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity_ordered' => 'required|numeric|min:0',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'delivery_note_number' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_contact' => 'nullable|string|max:20',
            'condition' => 'required|in:excellent,good,fair,poor,damaged',
            'quality_notes' => 'nullable|string',
            'storage_location' => 'nullable|string|max:100',
            'bin_location' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'return_reason' => 'nullable|string|required_if:receipt_type,return'
        ]);

        $companyId = Auth::guard('company')->id();
        $po = PurchaseOrder::findOrFail($request->purchase_order_id);
        
        // Validate quantities don't exceed ordered amounts
        foreach ($request->items as $item) {
            $quantityOrdered = floatval($item['quantity_ordered'] ?? 0);
            $quantityReceived = floatval($item['quantity_received'] ?? 0);
            
            if ($quantityReceived > $quantityOrdered && $request->receipt_type != 'return') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Quantity received cannot exceed quantity ordered for item: {$item['description']}");
            }
        }
        
        // Calculate totals
        $totals = Receipt::calculateTotals($request->items);
        
        // Determine status based on receipt type
        $status = 'draft';
        if ($request->receipt_type == 'return' || $request->receipt_type == 'damaged_goods') {
            $status = 'completed';
        }
        
        $receipt = Receipt::create([
        'company_id' => $companyId,
        'receipt_number' => $request->receipt_number,
        'purchase_order_id' => $request->purchase_order_id,
        'receipt_date' => $request->receipt_date,
        'received_by' => 1,
        'received_by_name' => Auth::guard('company')->user()->name,
        'supplier_name' => $po->supplier_name,
        'supplier_contact_person' => $po->supplier_contact_person,
        'items' => $request->items,
        'total_items_received' => $totals['total_items'],
        'total_quantity_received' => $totals['total_quantity'],
        'total_amount' => $totals['total_amount'],
        'status' => $status,
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
        'return_reason' => $request->return_reason,
    ]);

        // Update PO receipt status
        $receipt->updatePurchaseOrderStatus();

        return redirect()->route('company.receipt.index')
            ->with('success', 'Receipt created successfully!');
    }

    /**
     * Display single receipt
     */
    public function show(Receipt $receipt)
    {
        $receipt->load('purchaseOrder', 'receiver', 'verifier');
        return view('company.receipt.show', compact('receipt'));
    }

    /**
     * Show edit form
     */
    public function edit(Receipt $receipt)
    {
        if (!$receipt->can_edit) {
            return redirect()->route('company.receipt.index')
                ->with('error', 'This receipt cannot be edited.');
        }
        
        $receipt->load('purchaseOrder');
        return view('company.receipt.edit', compact('receipt'));
    }

    /**
     * Update receipt
     */
    public function update(Request $request, Receipt $receipt)
    {
        if (!$receipt->can_edit) {
            return redirect()->route('company.receipt.index')
                ->with('error', 'This receipt cannot be edited.');
        }
        
        $request->validate([
            'receipt_date' => 'required|date',
            'receipt_type' => 'required|in:full_delivery,partial_delivery,return,damaged_goods',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity_ordered' => 'required|numeric|min:0',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'delivery_note_number' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:100',
            'driver_contact' => 'nullable|string|max:20',
            'condition' => 'required|in:excellent,good,fair,poor,damaged',
            'quality_notes' => 'nullable|string',
            'storage_location' => 'nullable|string|max:100',
            'bin_location' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'return_reason' => 'nullable|string|required_if:receipt_type,return'
        ]);
        
        $po = $receipt->purchaseOrder;
        
        // Validate quantities
        foreach ($request->items as $item) {
            $quantityOrdered = floatval($item['quantity_ordered'] ?? 0);
            $quantityReceived = floatval($item['quantity_received'] ?? 0);
            
            if ($quantityReceived > $quantityOrdered && $request->receipt_type != 'return') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Quantity received cannot exceed quantity ordered for item: {$item['description']}");
            }
        }
        
        // Calculate totals
        $totals = Receipt::calculateTotals($request->items);
        
        $receipt->update([
            'receipt_date' => $request->receipt_date,
            'items' => json_encode($request->items),
            'total_items_received' => $totals['total_items'],
            'total_quantity_received' => $totals['total_quantity'],
            'total_amount' => $totals['total_amount'],
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
            'return_reason' => $request->return_reason
        ]);
        
        // Update PO receipt status
        $receipt->updatePurchaseOrderStatus();

        return redirect()->route('company.receipt.index')
            ->with('success', 'Receipt updated successfully!');
    }

    /**
     * Update receipt status
     */
    public function updateStatus(Request $request, Receipt $receipt)
    {
        $request->validate([
            'status' => 'required|in:completed,verified,cancelled'
        ]);
        
        $status = $request->status;
        
        if ($status === 'verified' && !$receipt->can_verify) {
            return redirect()->back()
                ->with('error', 'Receipt must be completed before verification.');
        }
        
        $updates = ['status' => $status];
        
        // If verifying, record verifier info
        if ($status === 'verified') {
            $updates['verified_by'] = Auth::id();
            $updates['verified_at'] = now();
        }
        
        $receipt->update($updates);
        
        // Update PO receipt status if cancelled
        if ($status === 'cancelled') {
            $receipt->updatePurchaseOrderStatus();
        }

        return redirect()->back()
            ->with('success', "Receipt status updated to " . ucfirst($status));
    }

    /**
     * Delete receipt
     */
    public function destroy(Receipt $receipt)
    {
        if (!$receipt->can_delete) {
            return redirect()->route('company.receipt.index')
                ->with('error', 'This receipt cannot be deleted.');
        }
        
        $po = $receipt->purchaseOrder;
        $receipt->delete();
        
        // Update PO receipt status
        $receipt->updatePurchaseOrderStatus();

        return redirect()->route('company.receipt.index')
            ->with('success', 'Receipt deleted successfully!');
    }

    /**
     * Download PDF
     */
    public function download(Receipt $receipt)
    {
        $company = Company::find($receipt->company_id);
        $receipt->load('purchaseOrder', 'receiver', 'verifier');
        
        $pdf = PDF::loadView('company.receipt.pdf', compact('receipt', 'company'))
            ->setPaper('a4', 'portrait');

        $filename = 'receipt-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $receipt->receipt_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * View PDF in browser
     */
    public function print(Receipt $receipt)
    {
        $company = Company::find($receipt->company_id);
        $receipt->load('purchaseOrder', 'receiver', 'verifier');
        
        $pdf = PDF::loadView('company.receipt.pdf', compact('receipt', 'company'));
        
        return $pdf->stream('receipt-' . $receipt->receipt_number . '.pdf');
    }

    /**
     * Generate receipt number
     */
    public function generateNumber()
    {
        $companyId = auth()->user()->company_id ?? 1;
        $number = Receipt::generateReceiptNumber($companyId);
        
        return response()->json(['receipt_number' => $number]);
    }

    /**
     * Get receipts for a specific PO
     */
    public function getPOReceipts($poId)
    {
        $receipts = Receipt::where('purchase_order_id', $poId)
            ->orderBy('receipt_date', 'desc')
            ->get();
            
        return response()->json($receipts);
    }

    /**
     * Mark receipt as completed
     */
    public function markAsCompleted(Receipt $receipt)
    {
        if ($receipt->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft receipts can be marked as completed.');
        }
        
        $receipt->update(['status' => 'completed']);
        $receipt->updatePurchaseOrderStatus();

        return redirect()->back()
            ->with('success', 'Receipt marked as completed.');
    }
}