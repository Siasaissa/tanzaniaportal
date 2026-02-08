<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\DeliveryNote;
use App\Models\Company;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display all invoices
     */
    public function index(Request $request)
    {
        $companyId = Auth::guard('company')->id();
        $company = Company::find($companyId);
        
        // Eager load deliveryNote with quotation nested
        $invoices = Invoice::whereBelongsTo(Auth::user())
            ->where('company_id', $companyId)
            ->with(['deliveryNote' => function($query) {
                $query->with('quotation');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        

            
        $totalInvoices = Invoice::whereBelongsTo(Auth::user())
                                    ->where('company_id', $companyId)->count();

        $totalAmount = Invoice::whereBelongsTo(Auth::user())
                                ->where('company_id', $companyId)->sum('total');

        $totalPaid = Invoice::whereBelongsTo(Auth::user())
                                ->where('company_id', $companyId)->sum('amount_paid');

        $totalBalance = Invoice::whereBelongsTo(Auth::user())
                                ->where('company_id', $companyId)->sum('balance');
        
        // Get available delivery notes for creating new invoices
        // Remove whereDoesntHave('invoice') temporarily to test
        $availableDeliveryNotes = DeliveryNote::whereBelongsTo(Auth::user())
            ->where('company_id', $companyId)
           // ->where('status', 'delivered')
            ->whereDoesntHave('invoice')
            ->with('quotation')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter out delivery notes that already have invoices
        $availableDeliveryNotes = $availableDeliveryNotes->filter(function($deliveryNote) {
            return is_null($deliveryNote->invoice);
        });
        
        return view('company.invoice.index', compact(
            'invoices', 
            'totalInvoices',
            'totalAmount',
            'totalPaid',
            'totalBalance',
            'company',
            'availableDeliveryNotes'
        ));
    }

    /**
     * Store new invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_note_id' => 'required|exists:delivery_notes,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        $companyId = Auth::guard('company')->id();
        
        // Find delivery note with quotation
        $deliveryNote = DeliveryNote::with('quotation')->find($request->delivery_note_id);
        
        if (!$deliveryNote) {
            return back()->with('error', 'Delivery note not found!');
        }
        
        if (!$deliveryNote->quotation) {
            return back()->with('error', 'Quotation not found for this delivery note!');
        }
        
        $quotation = $deliveryNote->quotation;
        
        // Check if delivery note already has an invoice
        if ($deliveryNote->invoice) {
            return back()->with('error', 'This delivery note already has an invoice!');
        }
        
        // Calculate totals
        $subtotal = $deliveryNote->total ?? 0;
        $taxRate = $request->tax_rate ?? 0;
        $tax = ($subtotal * $taxRate) / 100;
        $discount = $request->discount ?? 0;
        $total = $subtotal + $tax - $discount;
        
        $invoice = Invoice::create([
            'delivery_note_id' => $request->delivery_note_id,
            'company_id' => $companyId,
            'invoice_number' => $request->invoice_number ?? Invoice::generateInvoiceNumberC($companyId),
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'client_name' => $quotation->client_name ?? 'N/A',
            'client_email' => $quotation->client_email ?? null,
            'client_phone' => $quotation->client_phone ?? null,
            'client_address' => $deliveryNote->delivery_address ?? '',
            'items' => $deliveryNote->items ?? [],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'tax_rate' => $taxRate,
            'discount' => $discount,
            'total' => $total,
            'balance' => $total,
            'notes' => $request->notes,
            'terms' => $request->terms,
            'status' => $request->status ?? 'draft'
        ]);

        return redirect()->route('company.invoice.index')->with('success', 'Invoice created successfully!');
    }

    /**
     * Update invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'status' => 'required|in:draft,sent,viewed,paid,overdue,cancelled'
        ]);

        // Calculate totals from items
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += ($item['quantity'] * $item['price']);
        }
        
        $taxRate = $request->tax_rate ?? $invoice->tax_rate;
        $tax = ($subtotal * $taxRate) / 100;
        $discount = $request->discount ?? $invoice->discount;
        $total = $subtotal + $tax - $discount;
        $balance = $total - $invoice->amount_paid;
        
        // Update payment status based on balance
        $paymentStatus = $invoice->payment_status;
        if ($balance <= 0) {
            $paymentStatus = 'paid';
        } elseif ($balance < $total && $balance > 0) {
            $paymentStatus = 'partial';
        } elseif ($invoice->due_date < now() && $balance > 0) {
            $paymentStatus = 'overdue';
        } else {
            $paymentStatus = 'pending';
        }
        
        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'client_address' => $request->client_address,
            'items' => json_encode($request->items),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'tax_rate' => $taxRate,
            'discount' => $discount,
            'total' => $total,
            'balance' => $balance,
            'payment_status' => $paymentStatus,
            'notes' => $request->notes,
            'terms' => $request->terms,
            'status' => $request->status
        ]);

        return redirect()->route('company.invoice.index')->with('success', 'Invoice updated successfully!');
    }

    /**
     * Delete invoice
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('company.invoice.index')->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Download PDF
     */
    public function download(Invoice $invoice)
    {
        // Eager load relationships for PDF
        $invoice->load(['deliveryNote.quotation']);
        
        $pdf = PDF::loadView('company.invoice.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $invoice->invoice_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Print PDF
     */
    public function print(Invoice $invoice)
    {
        // Eager load relationships for PDF
        $invoice->load(['deliveryNote.quotation']);
        
        $pdf = PDF::loadView('company.invoice.pdf', compact('invoice'));
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Send invoice email
     */
    public function sendEmail(Request $request, Invoice $invoice)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        // Send email logic here
        $invoice->update(['status' => 'sent']);
        
        return back()->with('success', 'Invoice sent successfully!');
    }

    /**
     * Record payment
     */
    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:' . $invoice->balance,
            'payment_method' => 'required|in:cash,bank_transfer,cheque,mobile_money,credit_card,other',
            'transaction_reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date'
        ]);

        $invoice->recordPayment(
            $request->amount,
            $request->payment_method,
            $request->transaction_reference
        );

        return back()->with('success', 'Payment recorded successfully!');
    }

    /**
     * Get delivery note details (for AJAX)
     */
    public function getDeliveryNoteDetails($deliveryNoteId)
    {
        $companyId = auth()->user()->company_id ?? 1;
        
        $deliveryNote = DeliveryNote::where('company_id', $companyId)
            ->with('quotation')
            ->find($deliveryNoteId);

        if (!$deliveryNote) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery note not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'deliveryNote' => $deliveryNote,
            'items' => $deliveryNote->formatted_items,
            'total' => $deliveryNote->total,
            'client' => [
                'name' => $deliveryNote->quotation->client_name ?? 'N/A',
                'email' => $deliveryNote->quotation->client_email ?? '',
                'phone' => $deliveryNote->quotation->client_phone ?? ''
            ]
        ]);
    }

    /**
     * Generate invoice number
     */
    public function generateNumber()
    {
        $companyId = auth()->user()->company_id ?? 1;
        $number = Invoice::generateInvoiceNumberC($companyId);
        
        return response()->json(['invoice_number' => $number]);
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,viewed,paid,overdue,cancelled'
        ]);

        $invoice->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $invoice->status]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,partial,paid,overdue,cancelled'
        ]);

        $invoice->update(['payment_status' => $request->payment_status]);

        return response()->json(['success' => true, 'payment_status' => $invoice->payment_status]);
    }
}