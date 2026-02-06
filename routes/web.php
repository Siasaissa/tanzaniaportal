<?php

use App\Http\Controllers\AdminControllers\AdminDashboardController;
use App\Http\Controllers\AdminControllers\AdminInvoiceController;
use App\Http\Controllers\AdminControllers\AdminPurchacheOrderController;
use App\Http\Controllers\AdminControllers\AdminQuotationController;
use App\Http\Controllers\AdminControllers\AdminDeliveryNoteController;
use App\Http\Controllers\AdminControllers\AdminReceiptController;
use App\Http\Controllers\AdminControllers\AdminCompanyController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PagesController::class, 'welcome'])->name('welcome');

/*
|--------------------------------------------------------------------------
| User Dashboard & Profile
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('company', [PagesController::class, 'page'])->name('company');
    Route::resource('companies', CompanyController::class);
    Route::post('Admin/company', [CompanyController::class, 'store'])->name('store.company');
    Route::post('companies/{company}/reset-password', [CompanyController::class, 'resetPassword'])->name('companies.reset-password');

    //new routes 

    /*
    |--------------------------------------------------------------------------
    | Quotation Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('quotations')->name('quotation.')->group(function () {
        Route::get('/', [AdminQuotationController::class, 'index'])->name('index');
        Route::get('create', [AdminQuotationController::class, 'create'])->name('create');
        Route::post('/', [AdminQuotationController::class, 'store'])->name('store');
        Route::get('{quotation}', [AdminQuotationController::class, 'show'])->name('show');
        Route::get('{quotation}/edit', [AdminQuotationController::class, 'edit'])->name('edit');
        Route::put('{quotation}', [AdminQuotationController::class, 'update'])->name('update');
        Route::delete('{quotation}', [AdminQuotationController::class, 'destroy'])->name('destroy');

        Route::get('{quotation}/download', [AdminQuotationController::class, 'download'])->name('download');
        Route::get('{quotation}/print', [AdminQuotationController::class, 'print'])->name('print');

        Route::get('data', [AdminQuotationController::class, 'data'])->name('data');
        Route::get('generate-number', [AdminQuotationController::class, 'generateNumber'])->name('generate');
    });

    /*
    |--------------------------------------------------------------------------
    | Invoice Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('invoices')->name('invoice.')->group(function () {
    Route::get('/', [AdminInvoiceController::class, 'index'])->name('index');
    Route::post('/', [AdminInvoiceController::class, 'store'])->name('store');
    Route::put('{invoice}', [AdminInvoiceController::class, 'update'])->name('update');
    Route::delete('{invoice}', [AdminInvoiceController::class, 'destroy'])->name('destroy');
    
    Route::get('{invoice}/download', [AdminInvoiceController::class, 'download'])->name('download');
    Route::get('{invoice}/print', [AdminInvoiceController::class, 'print'])->name('print');
    Route::post('{invoice}/send-email', [AdminInvoiceController::class, 'sendEmail'])->name('send-email');
    Route::post('{invoice}/record-payment', [AdminInvoiceController::class, 'recordPayment'])->name('record-payment');
    
    Route::get('delivery-note/{deliveryNoteId}/details', [AdminInvoiceController::class, 'getDeliveryNoteDetails'])->name('delivery-note.details');
    Route::get('generate-number', [AdminInvoiceController::class, 'generateNumber'])->name('generate');
    Route::post('{invoice}/update-status', [AdminInvoiceController::class, 'updateStatus'])->name('update-status');
    Route::post('{invoice}/update-payment-status', [AdminInvoiceController::class, 'updatePaymentStatus'])->name('update-payment-status');
});

    /*
    |--------------------------------------------------------------------------
    | Delivery Note Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('delivery-notes')->name('delivery-note.')->group(function () {
    Route::get('/', [AdminDeliveryNoteController::class, 'index'])->name('index');
    Route::post('/', [AdminDeliveryNoteController::class, 'store'])->name('store');
    Route::put('{deliveryNote}', [AdminDeliveryNoteController::class, 'update'])->name('update');
    Route::delete('{deliveryNote}', [AdminDeliveryNoteController::class, 'destroy'])->name('destroy');
    
    Route::get('{deliveryNote}/download', [AdminDeliveryNoteController::class, 'download'])->name('download');
    Route::get('{deliveryNote}/print', [AdminDeliveryNoteController::class, 'print'])->name('print');
    
    Route::post('{deliveryNote}/update-status', [AdminDeliveryNoteController::class, 'updateStatus'])->name('update-status');
    Route::get('quotation/{quotationId}/details', [AdminDeliveryNoteController::class, 'getQuotationDetails'])->name('quotation.details');
    Route::get('search-quotations', [AdminDeliveryNoteController::class, 'searchQuotations'])->name('search-quotations');
    Route::get('generate-number', [AdminDeliveryNoteController::class, 'generateNumber'])->name('generate');
});

    /*
    |--------------------------------------------------------------------------
    | Purchase Order Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('purchase-orders')->name('purchase-order.')->group(function () {
    // Basic CRUD routes (you have these)
    Route::get('/', [AdminPurchacheOrderController::class, 'index'])->name('index');
    Route::get('create', [AdminPurchacheOrderController::class, 'create'])->name('create');
    Route::post('/', [AdminPurchacheOrderController::class, 'store'])->name('store');
    Route::get('{purchaseOrder}', [AdminPurchacheOrderController::class, 'show'])->name('show');
    Route::get('{purchaseOrder}/edit', [AdminPurchacheOrderController::class, 'edit'])->name('edit');
    Route::put('{purchaseOrder}', [AdminPurchacheOrderController::class, 'update'])->name('update');
    Route::delete('{purchaseOrder}', [AdminPurchacheOrderController::class, 'destroy'])->name('destroy');
    
    // Add these additional routes for full functionality:

    // Status management
    Route::post('{purchaseOrder}/status', [AdminPurchacheOrderController::class, 'updateStatus'])->name('status.update');
    
    // PDF generation
    Route::get('{purchaseOrder}/download', [AdminPurchacheOrderController::class, 'download'])->name('download');
    Route::get('{purchaseOrder}/print', [AdminPurchacheOrderController::class, 'print'])->name('print');
    
    // AJAX routes
    Route::get('purchase-orders/generate-number', [AdminPurchacheOrderController::class, 'generateNumber'])->name('generate');
    Route::post('calculate-totals', [AdminPurchacheOrderController::class, 'calculateTotals'])->name('calculate.totals');
});



        /*
    |--------------------------------------------------------------------------
    | Admin company
    |--------------------------------------------------------------------------
    */
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('admin/company/index', [AdminCompanyController::class, 'index'])->name('index');
           // View Company (for modal - optional if using AJAX)
        Route::get('admin/company/{id}', [AdminCompanyController::class, 'show'])->name('show');
        
        // Update Company
        Route::put('/admin/company/{id}', [AdminCompanyController::class, 'update'])->name('update');

        
        // Delete Company
        Route::delete('admin/company/{id}', [AdminCompanyController::class, 'destroy'])->name('destroy');
        
        // Update Company Status
        Route::post('admin/company/{id}/status', [AdminCompanyController::class, 'updateStatus'])->name('status');

    });
    //end of admin company


    /*
|--------------------------------------------------------------------------
| Receipt Routes (Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('receipts')->name('receipt.')->group(function () {
    Route::get('/', [AdminReceiptController::class, 'index'])->name('index');
    Route::get('create', [AdminReceiptController::class, 'create'])->name('create');
    Route::post('/', [AdminReceiptController::class, 'store'])->name('store');
    Route::get('{receipt}', [AdminReceiptController::class, 'show'])->name('show');
    Route::get('{receipt}/edit', [AdminReceiptController::class, 'edit'])->name('edit');
    Route::put('{receipt}', [AdminReceiptController::class, 'update'])->name('update');
    Route::delete('{receipt}', [AdminReceiptController::class, 'destroy'])->name('destroy');
    
    Route::post('{receipt}/status', [AdminReceiptController::class, 'updateStatus'])->name('status.update');
    
    // PDF download
    Route::get('{receipt}/download', [AdminReceiptController::class, 'download'])->name('download');
    Route::get('{receipt}/print', [AdminReceiptController::class, 'print'])->name('print');
    

    Route::get('generate-number', [AdminReceiptController::class, 'generateNumber'])->name('generate');
    Route::get('purchase-order/{id}/details', [AdminReceiptController::class, 'getPurchaseOrderDetails'])->name('po.details');
});
    /*
    |--------------------------------------------------------------------------
    | Setting Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('settings')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('update', [SettingController::class, 'update'])->name('update');
    });

});

/*
|--------------------------------------------------------------------------
| Staff Login
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('slogin/{company}', [PagesController::class, 'staff'])->name('slogin');
    Route::post('slogin', [ConfirmablePasswordController::class, 'storeS'])->name('slogin.submit');
});

/*
|--------------------------------------------------------------------------
| Company Dashboard & Modules
|--------------------------------------------------------------------------
*/
Route::middleware('auth:company')->prefix('company')->name('company.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Quotation Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('quotations')->name('quotation.')->group(function () {
        Route::get('/', [QuotationController::class, 'index'])->name('index');
        Route::get('create', [QuotationController::class, 'create'])->name('create');
        Route::post('/', [QuotationController::class, 'store'])->name('store');
        Route::get('{quotation}', [QuotationController::class, 'show'])->name('show');
        Route::get('{quotation}/edit', [QuotationController::class, 'edit'])->name('edit');
        Route::put('{quotation}', [QuotationController::class, 'update'])->name('update');
        Route::delete('{quotation}', [QuotationController::class, 'destroy'])->name('destroy');

        Route::get('{quotation}/download', [QuotationController::class, 'download'])->name('download');
        Route::get('{quotation}/print', [QuotationController::class, 'print'])->name('print');

        Route::get('data', [QuotationController::class, 'data'])->name('data');
        Route::get('generate-number', [QuotationController::class, 'generateNumber'])->name('generate');
    });

    /*
    |--------------------------------------------------------------------------
    | Invoice Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('invoices')->name('invoice.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::put('{invoice}', [InvoiceController::class, 'update'])->name('update');
    Route::delete('{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
    
    Route::get('{invoice}/download', [InvoiceController::class, 'download'])->name('download');
    Route::get('{invoice}/print', [InvoiceController::class, 'print'])->name('print');
    Route::post('{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('send-email');
    Route::post('{invoice}/record-payment', [InvoiceController::class, 'recordPayment'])->name('record-payment');
    
    Route::get('delivery-note/{deliveryNoteId}/details', [InvoiceController::class, 'getDeliveryNoteDetails'])->name('delivery-note.details');
    Route::get('generate-number', [InvoiceController::class, 'generateNumber'])->name('generate');
    Route::post('{invoice}/update-status', [InvoiceController::class, 'updateStatus'])->name('update-status');
    Route::post('{invoice}/update-payment-status', [InvoiceController::class, 'updatePaymentStatus'])->name('update-payment-status');
});

    /*
    |--------------------------------------------------------------------------
    | Delivery Note Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('delivery-notes')->name('delivery-note.')->group(function () {
    Route::get('/', [DeliveryNoteController::class, 'index'])->name('index');
    Route::post('/', [DeliveryNoteController::class, 'store'])->name('store');
    Route::put('{deliveryNote}', [DeliveryNoteController::class, 'update'])->name('update');
    Route::delete('{deliveryNote}', [DeliveryNoteController::class, 'destroy'])->name('destroy');
    
    Route::get('{deliveryNote}/download', [DeliveryNoteController::class, 'download'])->name('download');
    Route::get('{deliveryNote}/print', [DeliveryNoteController::class, 'print'])->name('print');
    
    Route::post('{deliveryNote}/update-status', [DeliveryNoteController::class, 'updateStatus'])->name('update-status');
    Route::get('quotation/{quotationId}/details', [DeliveryNoteController::class, 'getQuotationDetails'])->name('quotation.details');
    Route::get('search-quotations', [DeliveryNoteController::class, 'searchQuotations'])->name('search-quotations');
    Route::get('generate-number', [DeliveryNoteController::class, 'generateNumber'])->name('generate');
});

    /*
    |--------------------------------------------------------------------------
    | Purchase Order Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('purchase-orders')->name('purchase-order.')->group(function () {
    // Basic CRUD routes (you have these)
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
    Route::get('create', [PurchaseOrderController::class, 'create'])->name('create');
    Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
    Route::get('{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('show');
    Route::get('{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
    Route::put('{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('update');
    Route::delete('{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
    
    // Add these additional routes for full functionality:
    
    // Status management
    Route::post('{purchaseOrder}/status', [PurchaseOrderController::class, 'updateStatus'])->name('status.update');
    
    // PDF generation
    Route::get('{purchaseOrder}/download', [PurchaseOrderController::class, 'download'])->name('download');
    Route::get('{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])->name('print');
    
    // AJAX routes
    Route::get('purchase-orders/generate-number', [PurchaseOrderController::class, 'generateNumber'])->name('generate');
    Route::post('calculate-totals', [PurchaseOrderController::class, 'calculateTotals'])->name('calculate.totals');
});

    /*
    |--------------------------------------------------------------------------
    | Receipt Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('receipts')->name('receipt.')->group(function () {
        Route::get('/', [ReceiptController::class, 'index'])->name('index');
        Route::get('create', [ReceiptController::class, 'create'])->name('create');
        Route::post('/', [ReceiptController::class, 'store'])->name('store');
        Route::get('{receipt}', [ReceiptController::class, 'show'])->name('show');
        Route::get('{receipt}/edit', [ReceiptController::class, 'edit'])->name('edit');
        Route::put('{receipt}', [ReceiptController::class, 'update'])->name('update');
        Route::delete('{receipt}', [ReceiptController::class, 'destroy'])->name('destroy');
        
        // Status management
        Route::post('{receipt}/status', [ReceiptController::class, 'updateStatus'])->name('status.update');
        Route::post('{receipt}/complete', [ReceiptController::class, 'markAsCompleted'])->name('complete');
        
        // PDF
        Route::get('{receipt}/download', [ReceiptController::class, 'download'])->name('download');
        Route::get('{receipt}/print', [ReceiptController::class, 'print'])->name('print');
        
        // AJAX
        Route::get('generate-number', [ReceiptController::class, 'generateNumber'])->name('generate');
        Route::get('purchase-order/{id}/details', [ReceiptController::class, 'getPurchaseOrderDetails'])->name('po.details');
        Route::get('purchase-order/{id}/receipts', [ReceiptController::class, 'getPOReceipts'])->name('po.receipts');
    });

    /*
    |--------------------------------------------------------------------------
    | Setting Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('settings')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('update', [SettingController::class, 'update'])->name('update');
    });

});



// Regular user authentication
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Logout routes (protected by auth middleware)
Route::middleware('auth')->group(function () {
    // Regular user logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
    
    // Company logout (slogout)
    Route::post('company/slogout', [AuthenticatedSessionController::class, 'slogout'])
                ->name('company.slogout');
    
    // Universal logout
    Route::post('ulogout', [AuthenticatedSessionController::class, 'ulogout'])
                ->name('ulogout');
    
    // Smart logout (auto-detects)
    Route::post('smart-logout', [AuthenticatedSessionController::class, 'smartLogout'])
                ->name('smart.logout');
});

// Optional: Company-specific logout with middleware
Route::middleware('auth:company')->group(function () {
    Route::post('company/logout', [AuthenticatedSessionController::class, 'slogout'])
                ->name('company.logout');
});

// routes/web.php or routes/company.php
Route::post('/company/logout', [ConfirmablePasswordController::class, 'logout'])
    ->name('company.logout')
    ->middleware('auth:company');  // Only authenticated companies can logout


    Route::get('/debug/receipt/{id}', [AdminReceiptController::class, 'debugReceipt']);
require __DIR__.'/auth.php';
