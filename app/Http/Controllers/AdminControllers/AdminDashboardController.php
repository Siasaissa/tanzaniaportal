<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // existing totals (KEEP THESE)
        $deliverydata = DeliveryNote::count();
        $invoicedata = Invoice::count();
        $purchasingorder = PurchaseOrder::count();
        $total = $deliverydata + $invoicedata + $purchasingorder;

        // weekly report (NEW)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $weeklyReport = [];

        for ($day = 0; $day < 7; $day++) {
            $date = $startOfWeek->copy()->addDays($day);

            $dailyTotal =
                DeliveryNote::whereDate('created_at', $date)->count()
              + Invoice::whereDate('created_at', $date)->count()
              + PurchaseOrder::whereDate('created_at', $date)->count();

            $weeklyReport[$date->format('D')] = $dailyTotal; // Mon, Tue, etc.
        }

        return view('dashboard', compact(
            'deliverydata',
            'invoicedata',
            'purchasingorder',
            'total',
            'weeklyReport'
        ));
    }
}
