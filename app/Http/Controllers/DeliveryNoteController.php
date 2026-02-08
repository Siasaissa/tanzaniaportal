<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Quotation;
use App\Models\Company;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DeliveryNoteController extends Controller
{
    /**
     * Display all delivery notes
     */
    public function index(Request $request)
    {
        $companyId = Auth::guard('company')->id();
        $company = Company::find($companyId);
        
        $deliveryNotes = DeliveryNote::whereBelongsTo(Auth::user())
            ->where('company_id', $companyId)
            ->with('quotation')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $totalDeliveryNotes = DeliveryNote::whereBelongsTo(Auth::user())
                                            ->where('company_id', $companyId)->count();
        
        // Get available quotations for creating new delivery notes
        $availableQuotations = Quotation::whereBelongsTo(Auth::user())
            ->where('company_id', $companyId)
            ->whereDoesntHave('deliveryNote')
            ->orderBy('created_at', 'desc')
            ->get();
        
            
        // If quotation_id is provided in query string, auto-select it in the modal
        $selectedQuotationId = $request->query('quotation_id');
        
        return view('company.delivery-note.index', compact(
            'deliveryNotes', 
            'totalDeliveryNotes', 
            'company',
            'availableQuotations',
            'selectedQuotationId'
        ));
    }

    /**
     * Store new delivery note
     */
    public function store(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'delivery_date' => 'required|date',
            'dispatch_date' => 'nullable|date',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_contact_person' => 'nullable|string|max:255',
            'delivery_contact_phone' => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:255',
            'driver_contact' => 'nullable|string|max:20',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'delivery_notes' => 'nullable|string'
        ]);

        $companyId = Auth::guard('company')->id();
        $quotation = Quotation::findOrFail($request->quotation_id);
        
        $deliveryNote = DeliveryNote::create([
            'quotation_id' => $request->quotation_id,
            'company_id' => $companyId,
            'delivery_note_number' => $request->delivery_note_number ?? DeliveryNote::generateDeliveryNoteNumberC($companyId),
            'delivery_date' => $request->delivery_date,
            'dispatch_date' => $request->dispatch_date,
            'delivery_address' => $request->delivery_address ?? $this->getClientAddress($quotation),
            'delivery_contact_person' => $request->delivery_contact_person,
            'delivery_contact_phone' => $request->delivery_contact_phone ?? $quotation->client_phone,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'driver_contact' => $request->driver_contact,
            'items' => json_encode($request->items),
            'status' => $request->status ?? 'pending',
            'delivery_notes' => $request->delivery_notes
        ]);

        return redirect()->route('company.delivery-note.index')->with('success','Delivery note successfully created');
    }

    /**
     * Update delivery note
     */
    public function update(Request $request, DeliveryNote $deliveryNote)
    {
        $request->validate([
            'delivery_date' => 'required|date',
            'dispatch_date' => 'nullable|date',
            'delivery_address' => 'nullable|string|max:500',
            'delivery_contact_person' => 'nullable|string|max:255',
            'delivery_contact_phone' => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:255',
            'driver_contact' => 'nullable|string|max:20',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,dispatched,delivered,cancelled',
            'delivery_notes' => 'nullable|string'
        ]);

        $deliveryNote->update([
            'delivery_date' => $request->delivery_date,
            'dispatch_date' => $request->dispatch_date,
            'delivery_address' => $request->delivery_address,
            'delivery_contact_person' => $request->delivery_contact_person,
            'delivery_contact_phone' => $request->delivery_contact_phone,
            'vehicle_number' => $request->vehicle_number,
            'driver_name' => $request->driver_name,
            'driver_contact' => $request->driver_contact,
            'items' => json_encode($request->items),
            'status' => $request->status,
            'delivery_notes' => $request->delivery_notes
        ]);

        return redirect()->route('company.delivery-note.index')->with('success','Delivery note updated successfully!');
            
    }

    /**
     * Delete delivery note
     */
    public function destroy(DeliveryNote $deliveryNote)
    {
        $deliveryNote->delete();
        
        return redirect()->route('company.delivery-note.index')->with('success','Delivery note deleted successfully!');
            
    }

    /**
     * Download PDF
     */
    public function download(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load('quotation');
        
        $pdf = PDF::loadView('company.delivery-note.pdf', compact('deliveryNote'))
            ->setPaper('a4', 'portrait');

        $filename = 'delivery-note-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $deliveryNote->delivery_note_number) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * View PDF in browser
     */
    public function print(DeliveryNote $deliveryNote)
    {
        $deliveryNote->load('quotation');
        
        $pdf = PDF::loadView('company.delivery-note.pdf', compact('deliveryNote'));
        
        return $pdf->stream('delivery-note-' . $deliveryNote->delivery_note_number . '.pdf');
    }

    /**
     * Update status only
     */
    public function updateStatus(Request $request, DeliveryNote $deliveryNote)
    {
        $request->validate([
            'status' => 'required|in:pending,dispatched,delivered,cancelled'
        ]);

        $deliveryNote->update([
            'status' => $request->status,
            'dispatch_date' => $request->status === 'dispatched' && !$deliveryNote->dispatch_date 
                ? now() 
                : $deliveryNote->dispatch_date
        ]);

        return response()->json(['success' => true, 'status' => $deliveryNote->status]);
    }

    /**
     * Get quotation details for AJAX request
     */
    public function getQuotationDetails($quotationId)
{
    $companyId = auth()->user()->company_id ?? 1;
    
    $quotation = Quotation::where('company_id', $companyId)
        ->where('id', $quotationId)
        ->firstOrFail();

    return response()->json([
        'success' => true,
        'quotation' => $quotation,
        'items' => $quotation->formatted_items,
        'client' => [
            'name' => $quotation->client_name,
            'email' => $quotation->client_email,
            'phone' => $quotation->client_phone
        ]
    ]);
}
    /**
     * Search quotations for dropdown
     */
    public function searchQuotations(Request $request)
{
    $companyId = auth()->user()->company_id ?? 1;
    $searchTerm = $request->get('search', '');
    
    $quotations = Quotation::where('company_id', $companyId)
        ->whereDoesntHave('deliveryNote')
        ->where(function($query) use ($searchTerm) {
            $query->where('quotation_number', 'like', "%{$searchTerm}%")
                  ->orWhere('client_name', 'like', "%{$searchTerm}%")
                  ->orWhere('client_email', 'like', "%{$searchTerm}%")
                  ->orWhere('client_phone', 'like', "%{$searchTerm}%");
        })
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    return response()->json([
        'success' => true,
        'quotations' => $quotations
    ]);
}
    /**
     * Get client address from quotation
     */
    private function getClientAddress($quotation)
    {
        return $quotation->client_name . "\n" . 
               ($quotation->client_email ? "Email: " . $quotation->client_email . "\n" : '') .
               ($quotation->client_phone ? "Phone: " . $quotation->client_phone : '');
    }

    /**
     * Generate delivery note number
     */
    public function generateNumber()
    {
        $companyId = auth()->user()->company_id ?? 1;
        $number = DeliveryNote::generateDeliveryNoteNumberC($companyId);
        
        return response()->json(['delivery_note_number' => $number]);
    }


    
}