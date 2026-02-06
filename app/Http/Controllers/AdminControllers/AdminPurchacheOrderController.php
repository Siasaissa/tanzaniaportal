<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


class AdminPurchacheOrderController extends Controller
{
    /**
     * Display all purchase orders
     */
    public function index()
    {
        $companyId = Auth::guard('company')->id();
        $company = Company::find($companyId);
        
        $purchaseOrders = PurchaseOrder::orderBy('created_at', 'desc')
            ->paginate(15);
        
        $stats = [
            'total' => PurchaseOrder::count(),
            'pending' => PurchaseOrder::whereIn('status', ['draft', 'pending_approval'])->count(),
            'approved' => PurchaseOrder::where('status', 'approved')->count(),
            'ordered' => PurchaseOrder::where('status', 'ordered')->count(),
            'completed' => PurchaseOrder::where('status', 'completed')->count(),
            'total_value' => PurchaseOrder::sum('total_amount'),
        ];
        
        return view('admin.purchase-order.index', compact('purchaseOrders', 'company', 'stats'));
    }

    /**
     * Show form to create new purchase order
     */
    public function create()
    {
        $companyId = auth()->user()->company_id ?? 1;
        $poNumber = PurchaseOrder::generatePONumber($companyId);
        
        return view('admin.purchase-order.create', compact('poNumber'));
    }

    /**
     * Store new purchase order
     */
    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required|string|max:100',
            'po_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'nullable|email',
            'supplier_phone' => 'nullable|string',
            'supplier_address' => 'nullable|string',
            'supplier_contact_person' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,pending_approval',
            'payment_terms' => 'required|in:net_15,net_30,net_45,net_60,upon_delivery,advance_payment',
            'delivery_method' => 'nullable|in:pickup,delivery,courier,freight',
            'shipping_address' => 'nullable|string',
            'shipping_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string'
        ]);

        $companyId = Auth::guard('company')->id();
        
        // Calculate totals
        $taxRate = floatval($request->tax_rate ?? 0);
        $discount = floatval($request->discount ?? 0);
        $shippingCost = floatval($request->shipping_cost ?? 0);
        
        $totals = PurchaseOrder::calculateTotals(
            $request->items, 
            $taxRate, 
            $discount, 
            $shippingCost
        );
        
        $purchaseOrder = PurchaseOrder::create([
            'company_id' => $companyId,
            'po_number' => $request->po_number,
            'po_date' => $request->po_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'supplier_name' => $request->supplier_name,
            'supplier_email' => $request->supplier_email,
            'supplier_phone' => $request->supplier_phone,
            'supplier_address' => $request->supplier_address,
            'supplier_contact_person' => $request->supplier_contact_person,
            'items' => $request->items,
            'subtotal' => $totals['subtotal'],
            'tax_rate' => $taxRate,
            'tax_amount' => $totals['tax_amount'],
            'discount' => $discount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totals['total'],
            'status' => $request->status,
            'payment_terms' => $request->payment_terms,
            'delivery_method' => $request->delivery_method,
            'shipping_address' => $request->shipping_address,
            'shipping_instructions' => $request->shipping_instructions,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions
        ]);

        return redirect()->route('company.purchase-order.index')
            ->with('success', 'Purchase Order created successfully!');
    }

    /**
     * Display single purchase order
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        return view('admin.purchase-order.show', compact('purchaseOrder'));
    }

    /**
     * Show edit form
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if (!$purchaseOrder->can_edit) {
            return redirect()->route('company.purchase-order.index')
                ->with('error', 'This purchase order cannot be edited.');
        }
        
        return view('company.purchase-order.edit', compact('purchaseOrder'));
    }

    /**
     * Update purchase order
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {




        if (!$purchaseOrder->can_edit) {
            return redirect()->route('company.purchase-order.index')
                ->with('error', 'This purchase order cannot be edited.');
        }
        
        $request->validate([
            'po_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'nullable|email',
            'supplier_phone' => 'nullable|string',
            'supplier_address' => 'nullable|string',
            'supplier_contact_person' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'nullable|numeric|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
            'items.*.unit' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,pending_approval',
            'payment_terms' => 'nullable|in:net_15,net_30,net_45,net_60,upon_delivery,advance_payment',
            'delivery_method' => 'nullable|in:pickup,delivery,courier,freight',
            'shipping_address' => 'nullable|string',
            'shipping_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string'
        ]);
        
        // Calculate totals
        $taxRate = floatval($request->tax_rate ?? 0);
        $discount = floatval($request->discount ?? 0);
        $shippingCost = floatval($request->shipping_cost ?? 0);
        
        $totals = PurchaseOrder::calculateTotals(
            $request->items, 
            $taxRate, 
            $discount, 
            $shippingCost
        );
        
        $purchaseOrder->update([
            'po_date' => $request->po_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'supplier_name' => $request->supplier_name,
            'supplier_email' => $request->supplier_email,
            'supplier_phone' => $request->supplier_phone,
            'supplier_address' => $request->supplier_address,
            'supplier_contact_person' => $request->supplier_contact_person,
            'items' => $request->items,
            'subtotal' => $totals['subtotal'],
            'tax_rate' => $taxRate,
            'tax_amount' => $totals['tax_amount'],
            'discount' => $discount,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totals['total'],
            'status' => $request->status,
            'payment_terms' => $request->payment_terms,
            'delivery_method' => $request->delivery_method,
            'shipping_address' => $request->shipping_address,
            'shipping_instructions' => $request->shipping_instructions,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions
        ]);

        return redirect()->route('company.purchase-order.index')
            ->with('success', 'Purchase Order updated successfully!');
    }

    /**
     * Update purchase order status
     */
    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending_approval,approved,ordered,partial_received,completed,cancelled'
        ]);
        
        $status = $request->status;
        $updates = ['status' => $status];
        
        // If approving, record approver info
        if ($status === 'approved') {
            $updates['approved_by'] = Auth::id();
            $updates['approved_at'] = now();
        }
        
        $purchaseOrder->update($updates);
        
        return redirect()->back()
            ->with('success', "Purchase Order status updated to " . str_replace('_', ' ', $status));
    }

    /**
     * Delete purchase order
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        
        return redirect()->route('admin.purchase-order.index')
            ->with('success', 'Purchase Order deleted successfully!');
    }

    /**
     * Download PDF
     */
    public function download(PurchaseOrder $purchaseOrder)
    {
        $company = Company::find($purchaseOrder->company_id);
        
        $pdf = PDF::loadView('admin.purchase-order.pdf', compact('purchaseOrder', 'company'))
            ->setPaper('a4', 'portrait');

        $filename = 'purchase-order-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $purchaseOrder->po_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * View PDF in browser
     */
    public function print(PurchaseOrder $purchaseOrder)
    {
        $company = Company::find($purchaseOrder->company_id);
        
        $pdf = PDF::loadView('admin.purchase-order.pdf', compact('purchaseOrder', 'company'));
        
        return $pdf->stream('purchase-order-' . $purchaseOrder->po_number . '.pdf');
    }

    /**
     * Generate PO number
     */
    public function generateNumber()
    {
        $companyId = auth()->user()->company_id ?? 1;
        $number = PurchaseOrder::generatePONumber($companyId);
        
        return response()->json(['po_number' => $number]);
    }

    /**
     * Calculate totals (AJAX)
     */
    public function calculateTotals(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0'
        ]);
        
        $totals = PurchaseOrder::calculateTotals(
            $request->items,
            floatval($request->tax_rate ?? 0),
            floatval($request->discount ?? 0),
            floatval($request->shipping_cost ?? 0)
        );
        
        return response()->json($totals);
    }
}
