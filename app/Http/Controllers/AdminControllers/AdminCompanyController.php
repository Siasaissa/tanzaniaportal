<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|max:255',
        'phone'        => 'nullable|string|max:20',
        'provider'     => 'nullable|string|max:50',
        'status'       => 'required|in:active,inactive,pending',
        'company_desc' => 'nullable|string|max:2000',
        'logo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password'     => 'nullable|string|min:8|confirmed',
    ]);

    // update normal fields
    $company->fill($request->except(['logo', 'password', 'remove_logo']));

    // update password only if entered
    if ($request->filled('password')) {
        $company->password = Hash::make($request->password);
    }

    // remove logo if checked
    if ($request->remove_logo && $company->logo) {
        Storage::disk('public')->delete($company->logo);
        $company->logo = null;
    }

    // upload new logo
    if ($request->hasFile('logo')) {
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
        $company->logo = $request->file('logo')->store('companies/logos', 'public');
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
