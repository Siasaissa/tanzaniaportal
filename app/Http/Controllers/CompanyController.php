<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyCredentialsMail;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Store a newly created company.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'phone' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_desc' => 'nullable|string',
            'provider' => 'required|string|max:255',
        ]);

        // Generate random password
        $generatedPassword = Str::random(10);
        
        // Handle logo upload if exists
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company_logos', 'public');
        }

        // Create company with hashed password
        $company = Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'logo' => $logoPath,
            'company_desc' => $validated['company_desc'] ?? null,
            'password' => Hash::make($generatedPassword),
            'status' => 'active',
            'provider' => $validated['provider'],
            // Add other fields here
        ]);

        // Send email with credentials
        try {
            Mail::to($company->email)->send(new CompanyCredentialsMail($company, $generatedPassword));
        } catch (\Exception $e) {
            // Log error but don't fail company creation
            \Log::error('Failed to send company credentials email: ' . $e->getMessage());
        }

        return redirect()->route('admin.company')->with(['success' , 'Company created successfully. Credentials have been sent to the company email.' ],);
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:companies,email,' . $company->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_desc' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'status' => 'sometimes|in:active,inactive,suspended',
        ]);

        // Handle logo upload if exists
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                \Storage::disk('public')->delete($company->logo);
            }
            $logoPath = $request->file('logo')->store('company_logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Handle password update
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $company->update($validated);

        return response()->json([
            'message' => 'Company updated successfully',
            'company' => $company
        ]);
    }

    /**
     * Reset company password and send via email.
     */
    public function resetPassword(Request $request, Company $company)
    {
        // Generate new random password
        $newPassword = Str::random(10);
        
        // Update company password
        $company->update([
            'password' => Hash::make($newPassword)
        ]);

        // Send email with new credentials
        try {
            Mail::to($company->email)->send(new CompanyCredentialsMail($company, $newPassword, true));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Password reset but failed to send email: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Password reset successfully. New credentials have been sent to the company email.'
        ]);
    }
}