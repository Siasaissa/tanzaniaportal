<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminCompanyController extends Controller
{
   /**
     * Display a listing of companies.
     */
    public function index()
    {
        $companies = Company::latest()->paginate(20);
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $inactiveCompanies = Company::where('status', 'inactive')->count();
        $pendingCompanies = Company::where('status', 'pending')->count();

        // Count unique company_ids
        $uniqueCompanyCount = Quotation::distinct('company_id')->count('company_id');

        return view('admin.company.index', compact(
            'companies', 
            'totalCompanies',
            'activeCompanies',
            'inactiveCompanies',
            'pendingCompanies'
        ));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        // We're using modals, so this can return JSON for modal content
        // or just redirect to index (modal will handle the form)
        return redirect()->route('companies.index');
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company)
{
    //dd($request->all());
    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => [
            'required',
            'email',
            'max:255',
            Rule::unique('companies')->ignore($company->id)
        ],
        'phone'        => 'nullable|string|max:20',
        'provider'     => 'nullable|string|max:50',
        'status'       => 'required|in:active,inactive,pending',
        'company_desc' => 'nullable|string|max:2000',
        'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password'     => 'nullable|string|min:8|confirmed',
    ]);

    // Update fields
    $company->name = $request->name;
    $company->email = $request->email;
    $company->phone = $request->phone;
    $company->provider = $request->provider;
    $company->status = $request->status;
    $company->company_desc = $request->company_desc;

    // Update password only if entered
    if ($request->filled('password')) {
        $company->password = Hash::make($request->password);
    }

    // Handle logo removal
    if ($request->boolean('remove_logo') && $company->logo) {
        Storage::disk('public')->delete($company->logo);
        $company->logo = null;
    }

    // Handle new logo upload
    if ($request->hasFile('logo')) {
        // Delete old logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        
        // Store new logo
        $path = $request->file('logo')->store('companies/logos', 'public');
        $company->logo = $path;
    }

    $company->save();

    return redirect()->route('admin.company.index')
        ->with('success', 'Company updated successfully.');
}

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        if ($company->quotations()->exists()) {
            return redirect()->route('admin.company.index')
                ->with('error', 'Cannot delete company with related quotations.');
        }
        
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        
        $company->delete();
        
        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    /**
     * Update company status.
     */
    public function updateStatus(Request $request, Company $company)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,pending'
        ]);
        
        $company->status = $request->status;
        $company->save();
        
        return redirect()->back()
            ->with('success', 'Company status updated successfully.');
    }
}
