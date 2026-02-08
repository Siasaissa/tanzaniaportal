<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\Receipt;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Get the authenticated company user
        $companyUser = Auth::guard('company')->user();
        
        // Get the company name from the companies table
        $company = Company::find($companyUser->id); // Since Company user IS the company record
        
        // Count delivery notes where company name matches (using your original logic)
        $Delivery = DeliveryNote::whereHas('company', function($query) use ($company) {
            $query->where('name', $company->name);
        })->count();
        
        // Count other documents - FIXED: Use $company instead of Auth::user()
        $Invoice = Invoice::where('company_id', $company->id)->count();
        $PO = PurchaseOrder::where('company_id', $company->id)->count();
        $Quotation = Quotation::where('company_id', $company->id)->count();
        $Receipt = Receipt::where('company_id', $company->id)->count();

        $today = now();
        $last7Days = [];
        $dailyTotals = [];
        $dailyLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dayLabel = $date->format('D');
            
            // Query documents for THIS COMPANY created on this date
            $deliveryDailyCount = DeliveryNote::where('company_id', $company->id)
                ->whereDate('created_at', $dateString)
                ->count();
            $invoiceDailyCount = Invoice::where('company_id', $company->id)
                ->whereDate('created_at', $dateString)
                ->count();
            $poDailyCount = PurchaseOrder::where('company_id', $company->id)
                ->whereDate('created_at', $dateString)
                ->count();
            
            $total = $deliveryDailyCount + $invoiceDailyCount + $poDailyCount;
            
            $last7Days[] = [
                'date' => $dateString,
                'label' => $dayLabel,
                'delivery' => $deliveryDailyCount,
                'invoice' => $invoiceDailyCount,
                'po' => $poDailyCount,
                'total' => $total
            ];
            
            $dailyTotals[] = $total;
            $dailyLabels[] = $dayLabel;
        }

        return view('company.dashboard', compact(
            'Delivery', 'Invoice', 'PO', 'Quotation', 'Receipt', 
            'last7Days', 'dailyTotals', 'dailyLabels'
        ));
    }
}