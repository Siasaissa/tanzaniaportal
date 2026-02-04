<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Company;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AdminQuotationController extends Controller
{
    /**
     * Display all quotations
     */
    public function index()
{


    // Get quotations for this company
    $quotations = Quotation::latest()
        ->paginate(10);

    // Count total quotations
    $totalQuotations = Quotation::all()->count();

    return view('admin.quotation.index', compact(
        'quotations',
        'totalQuotations',
    ));
}

    /**
     * Show form to create new quotation
     */
    public function create()
    {
        $companyId = Auth::guard('company')->user();
        $quotationNumber = Quotation::generateQuotationNumber($companyId);
        
        return view('quotations.create', compact('quotationNumber'));
    }

    /**
     * Store new quotation
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $companyId = Auth::guard('company')->id();
        
        $quotation = Quotation::create([
            'company_id' => $companyId,
            'quotation_number' => $request->quotation_number ?? Quotation::generateQuotationNumber($companyId),
            'date' => $request->date,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'items' => json_encode($request->items),
            'total' => Quotation::calculateTotal($request->items),
            'notes' => $request->notes
        ]);

        return redirect()->route('company.quotation.index')
                         ->with('success', 'Quotation created successfully!');
    }

    /**
     * Display single quotation
     */
    public function show(Quotation $quotation)
    {
        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show edit form
     */
    public function edit(Quotation $quotation)
    {
        return view('quotations.edit', compact('quotation'));
    }

    /**
     * Update quotation
     */
    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $quotation->update([
            'date' => $request->date,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'items' => json_encode($request->items),
            'total' => Quotation::calculateTotal($request->items),
            'notes' => $request->notes
        ]);

        return redirect()->route('company.quotation.index')
                         ->with('success', 'Quotation updated successfully!');
    }

    /**
     * Delete quotation
     */
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();
        
        return redirect()->route('company.quotation.index')
                         ->with('success', 'Quotation deleted successfully!');
    }

    /**
     * Download/Print PDF
     */
public function download(Quotation $quotation)
{
    $pdf = PDF::loadView('admin.quotation.pdf', compact('quotation'))
              ->setPaper('a4', 'portrait');

    $filename = 'quotation-' . preg_replace('/[^A-Za-z0-9\-]/', '_', $quotation->quotation_number) . '.pdf';

    return $pdf->download($filename);
}


    /**
     * View PDF in browser
     */
    public function print(Quotation $quotation)
    {
        $pdf = PDF::loadView('admin.quotation.pdf', compact('quotation'));
        
        return $pdf->stream('quotation-' . $quotation->quotation_number . '.pdf');
    }

    public function generateNumber()
{
    $companyId = auth()->user()->company_id ?? 1;
    $number = Quotation::generateQuotationNumber($companyId);
    
    return response()->json(['quotation_number' => $number]);
}
}
