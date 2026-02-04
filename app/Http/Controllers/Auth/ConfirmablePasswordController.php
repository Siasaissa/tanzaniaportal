<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Company;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }

public function storeS(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'company_id' => 'required|exists:companies,id',
    ]);

    // First, get the company name from the provided ID
    $company = Company::find($request->company_id);
    
    if (!$company) {
        return back()->withErrors([
            'company_id' => 'Company not found.',
        ])->withInput();
    }

    // Now authenticate using the company name instead of ID
    if (! Auth::guard('company')->attempt([
        'email'      => $request->email,
        'password'   => $request->password,
        'name'       => $company->name, // ✅ Use name instead of ID
        'status'     => 'active',
    ], $request->filled('remember'))) {

        return back()->withErrors([
            'email' => 'These credentials do not belong to this company.',
        ])->withInput();
    }

    // Save last login info
    $loggedInCompany = Auth::guard('company')->user();
    $loggedInCompany->update([
        'last_login_at' => now(),
        'last_login_ip' => $request->ip(),
    ]);

    return redirect()->route('company.dashboard');
}
// In your controller (e.g., CompanyAuthController)
public function logout(Request $request): RedirectResponse
{
    // Get company info before logging out (optional, for logging)
    $company = Auth::guard('company')->user();
    
    // Logout the company from the 'company' guard
    Auth::guard('company')->logout();
    
    // Invalidate the session
    $request->session()->invalidate();
    
    // Regenerate CSRF token
    $request->session()->regenerateToken();
    
    // Optional: Log the logout activity
    if ($company) {
        // You can log to database, file, or monitoring system
        \Log::info('Company logged out', [
            'company_id' => $company->id,
            'company_email' => $company->email,
            'time' => now(),
        ]);
    }
    
    // Redirect to company login page
    return redirect()->route('welcome')
        ->with('status', 'You have been successfully logged out.');
}

}