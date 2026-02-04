<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\Receipt;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){

        $Delivery = DeliveryNote::whereBelongsTo(Auth::user())->count();
        $Invoice = Invoice::whereBelongsTo(Auth::user())->count();
        $PO = PurchaseOrder::whereBelongsTo(Auth::user())->count();
        $Quotation = Quotation::whereBelongsTo(Auth::user())->count();
        $Receipt = Receipt::whereBelongsTo(Auth::user())->count();


        $today = now();
        $last7Days = [];
        $dailyTotals = [];
        $dailyLabels = [];
        

        for ($i = 6; $i >= 0; $i--) {
        $date = $today->copy()->subDays($i);
        $dateString = $date->format('Y-m-d');
        $dayLabel = $date->format('D'); // Mon, Tue, Wed, etc.
        
        // Query documents created on this specific date
        $deliveryCount = DeliveryNote::whereDate('created_at', $dateString)->count();
        $invoiceCount = Invoice::whereDate('created_at', $dateString)->count();
        $poCount = PurchaseOrder::whereDate('created_at', $dateString)->count();
        
        $total = $deliveryCount + $invoiceCount + $poCount;
        
        $last7Days[] = [
            'date' => $dateString,
            'label' => $dayLabel,
            'delivery' => $deliveryCount,
            'invoice' => $invoiceCount,
            'po' => $poCount,
            'total' => $total
        ];
        
        $dailyTotals[] = $total;
        $dailyLabels[] = $dayLabel;
    }

        return view('company.dashboard', compact('Delivery','Invoice','PO','Quotation','Receipt','last7Days', 'dailyTotals', 'dailyLabels'));
    }
}
