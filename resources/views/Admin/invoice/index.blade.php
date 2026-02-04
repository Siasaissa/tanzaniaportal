<!DOCTYPE html>
<html lang="en">
@include('layouts.adminhead')

<body class="g-sidenav-show bg-gray-100">
    @include('layouts.adminnavbar')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('layouts.topnav')
        
        <div class="container-fluid py-4">
            <!-- Header -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="mb-0 h4 font-weight-bolder">Invoice Management</h3>
                            <p class="mb-0 text-sm">Manage all your invoices</p>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Invoices</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalInvoices }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">receipt</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Amount</p>
                                    <h5 class="font-weight-bolder mb-0">Tsh{{ number_format($totalAmount, 2) }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">payments</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Amount Paid</p>
                                    <h5 class="font-weight-bolder mb-0">Tsh{{ number_format($totalPaid, 2) }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">check_circle</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Balance Due</p>
                                    <h5 class="font-weight-bolder mb-0">Tsh{{ number_format($totalBalance, 2) }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">balance</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Invoices List</h6>
                                    <p class="text-sm mb-0">
                                        <i class="material-symbols-rounded text-info" style="font-size: 14px;">receipt</i>
                                        <span class="font-weight-bold ms-1">{{ $totalInvoices }} invoices</span> in total
                                    </p>
                                </div>
                                <div class="input-group" style="width: 250px;">
                                    <span class="input-group-text text-body">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search invoices..." id="search-invoices">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center justify-content-center mb-0" id="invoices-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Invoice #</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Delivery Note #</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Due Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $invoice->invoice_number }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            <i class="material-symbols-rounded" style="font-size: 12px;">calendar_month</i>
                                                            {{ $invoice->invoice_date->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2">
                                                    <div class="my-auto">
                                                        <!-- Use optional() helper for safe access -->
                                                        @php
                                                            $deliveryNote = optional($invoice->deliveryNote);
                                                            $deliveryNoteNumber = $deliveryNote->delivery_note_number ?? 'N/A';
                                                        @endphp
                                                        <h6 class="mb-0 text-sm">{{ $deliveryNoteNumber }}</h6>
                                                        @if($deliveryNote && $deliveryNote->delivery_date)
                                                        <p class="text-xs text-secondary mb-0">
                                                            <i class="material-symbols-rounded" style="font-size: 10px;">local_shipping</i>
                                                            Delivered: {{ $deliveryNote->delivery_date->format('M d, Y') }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2">
                                                    <div class="my-auto">
                                                        <h6 class="mb-0 text-sm">{{ $invoice->client_name }}</h6>
                                                        @if($invoice->client_email)
                                                        <p class="text-xs text-secondary mb-0">{{ $invoice->client_email }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-xs font-weight-bold {{ $invoice->is_overdue ? 'text-danger' : '' }}">
                                                    {{ $invoice->due_date->format('M d, Y') }}
                                                    @if($invoice->is_overdue)
                                                    <br><small class="text-danger">{{ $invoice->days_overdue }} days overdue</small>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column">
                                                    <span class="text-xs font-weight-bold">Tsh{{ number_format($invoice->total, 2) }}</span>
                                                    <small class="text-xs text-success">Paid: Tsh{{ number_format($invoice->amount_paid, 2) }}</small>
                                                    <small class="text-xs text-warning">Balance: Tsh{{ number_format($invoice->balance, 2) }}</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="badge badge-sm bg-gradient-{{ $invoice->payment_status_color }}">
                                                        <i class="material-symbols-rounded" style="font-size: 12px; vertical-align: middle;">
                                                            {{ $invoice->payment_status_icon }}
                                                        </i>
                                                        {{ ucfirst($invoice->payment_status) }}
                                                    </span>
                                                    <span class="badge badge-sm bg-gradient-{{ $invoice->status_color }}">
                                                        {{ ucfirst($invoice->status) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#viewInvoiceModal{{ $invoice->id }}" 
                                                            title="View">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                                                    </button>
                                                    <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#editInvoiceModal{{ $invoice->id }}" 
                                                            title="Edit">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                                                    </button>
                                                    <button type="button" class="btn btn-link text-primary px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#recordPaymentModal{{ $invoice->id }}" 
                                                            title="Record Payment"
                                                            {{ $invoice->is_paid ? 'disabled' : '' }}>
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">payments</i>
                                                    </button>
                                                    <a href="{{ route('admin.invoice.download', $invoice) }}" 
                                                       class="btn btn-link text-success px-2 mb-0" 
                                                       title="Download PDF">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">download</i>
                                                    </a>
                                                    <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteInvoiceModal{{ $invoice->id }}" 
                                                            title="Delete">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">receipt</i>
                                                    <h6 class="text-secondary">No invoices found</h6>
                                                    <p class="text-sm text-secondary">Create your first invoice by clicking the button above</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($invoices->hasPages())
                            <div class="p-3">
                                {{ $invoices->links('pagination::bootstrap-5') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @include('layouts.footer')
        </div>
    </main>
    
    <!-- =========================== -->
    <!-- MODALS SECTION -->
    <!-- =========================== -->
    
    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInvoiceModalLabel">Create New Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.invoice.store') }}" method="POST" id="createInvoiceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_number" class="form-control-label">Invoice Number</label>
                                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery_note_id" class="form-control-label">Select Delivery Note *</label>
                                    <select class="form-control" name="delivery_note_id" id="delivery_note_id" required>
                                        <option value="">-- Select Delivery Note --</option>
                                        @foreach($availableDeliveryNotes as $deliveryNote)
                                        @php
                                            $quotation = optional($deliveryNote->quotation);
                                        @endphp
                                        <option value="{{ $deliveryNote->id }}" 
                                                data-items="{{ json_encode($deliveryNote->formatted_items) }}"
                                                data-total="{{ $deliveryNote->total }}"
                                                data-client-name="{{ $quotation->client_name ?? '' }}"
                                                data-client-email="{{ $quotation->client_email ?? '' }}"
                                                data-client-phone="{{ $quotation->client_phone ?? '' }}"
                                                data-delivery-address="{{ $deliveryNote->delivery_address ?? '' }}">
                                            {{ $deliveryNote->delivery_note_number }} - {{ $quotation->client_name ?? 'No Client' }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_date" class="form-control-label">Invoice Date *</label>
                                    <input type="date" class="form-control" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date" class="form-control-label">Due Date *</label>
                                    <input type="date" class="form-control" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax_rate" class="form-control-label">Tax Rate (%)</label>
                                    <input type="number" class="form-control" id="tax_rate" name="tax_rate" 
                                           step="0.01" min="0" max="100" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount" class="form-control-label">Discount</label>
                                    <input type="number" class="form-control" id="discount" name="discount" 
                                           step="0.01" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="draft">Draft</option>
                                        <option value="sent">Sent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Items Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6>Invoice Items *</h6>
                                <div id="items-container">
                                    <!-- Items will be populated from delivery note selection -->
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6 offset-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-control-label">Subtotal</label>
                                                <input type="number" class="form-control" id="subtotal" 
                                                       value="0.00" step="0.01" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-control-label">Total</label>
                                                <input type="number" class="form-control" id="total" 
                                                       name="total" value="0.00" step="0.01" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-control-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="3" 
                                              placeholder="Additional notes..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="terms" class="form-control-label">Terms & Conditions</label>
                                    <textarea class="form-control" name="terms" rows="3" 
                                              placeholder="Payment terms and conditions..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn bg-gradient-success">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Invoice Modals for each invoice -->
    @foreach($invoices as $invoice)
    
    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Invoice #{{ $invoice->invoice_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Company Information</h6>
                            <p class="mb-1"><strong>{{ $invoice->company->name ?? Auth::user()->name }}</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>Invoice Details</h6>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                            @php
                                $deliveryNote = optional($invoice->deliveryNote);
                            @endphp
                            <p class="mb-1"><strong>Delivery Note #:</strong> {{ $deliveryNote->delivery_note_number ?? 'N/A' }}</p>
                            <p class="mb-1"><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</p>
                            <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Bill To</h6>
                            <p class="mb-1"><strong>{{ $invoice->client_name }}</strong></p>
                            @if($invoice->client_email)
                            <p class="mb-1">Email: {{ $invoice->client_email }}</p>
                            @endif
                            @if($invoice->client_phone)
                            <p class="mb-1">Phone: {{ $invoice->client_phone }}</p>
                            @endif
                            @if($invoice->client_address)
                            <p class="mb-0">{{ nl2br($invoice->client_address) }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>Payment Status</h6>
                            <span class="badge badge-lg bg-gradient-{{ $invoice->payment_status_color }} mb-2">
                                {{ ucfirst($invoice->payment_status) }}
                            </span>
                            <p class="mb-1"><strong>Invoice Status:</strong> {{ ucfirst($invoice->status) }}</p>
                            @if($invoice->payment_method)
                            <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}</p>
                            @endif
                            @if($invoice->transaction_reference)
                            <p class="mb-0"><strong>Reference:</strong> {{ $invoice->transaction_reference }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Invoice Items</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->formatted_items as $item)
                                        <tr>
                                            <td>{{ $item['description'] ?? '' }}</td>
                                            <td>{{ $item['quantity'] ?? 0 }}</td>
                                            <td>Tsh{{ number_format($item['price'] ?? 0, 2) }}</td>
                                            <td>Tsh{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->subtotal, 2) }}</strong></td>
                                        </tr>
                                        @if($invoice->tax > 0)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Tax ({{ $invoice->tax_rate }}%):</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->tax, 2) }}</strong></td>
                                        </tr>
                                        @endif
                                        @if($invoice->discount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->discount, 2) }}</strong></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->total, 2) }}</strong></td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="3" class="text-end"><strong>Amount Paid:</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->amount_paid, 2) }}</strong></td>
                                        </tr>
                                        <tr class="table-warning">
                                            <td colspan="3" class="text-end"><strong>Balance Due:</strong></td>
                                            <td><strong>Tsh{{ number_format($invoice->balance, 2) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    @if($invoice->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Notes</h6>
                            <div class="card bg-light p-3">
                                {{ $invoice->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($invoice->terms)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Terms & Conditions</h6>
                            <div class="card bg-light p-3">
                                {{ $invoice->terms }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('admin.invoice.print', $invoice) }}" 
                       class="btn btn-primary" target="_blank">
                        <i class="material-symbols-rounded">print</i> Print
                    </a>
                    <a href="{{ route('admin.invoice.download', $invoice) }}" 
                       class="btn btn-success">
                        <i class="material-symbols-rounded">download</i> Download PDF
                    </a>
                    @if(!$invoice->is_paid)
                    <button type="button" class="btn btn-warning" 
                            data-bs-toggle="modal" data-bs-target="#recordPaymentModal{{ $invoice->id }}">
                        <i class="material-symbols-rounded">payments</i> Record Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Invoice #{{ $invoice->invoice_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.invoice.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Delivery Note Number</label>
                                @php
                                    $deliveryNote = optional($invoice->deliveryNote);
                                @endphp
                                <input type="text" class="form-control" value="{{ $deliveryNote->delivery_note_number ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date *</label>
                                <input type="date" class="form-control" name="invoice_date" 
                                       value="{{ $invoice->invoice_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date *</label>
                                <input type="date" class="form-control" name="due_date" 
                                       value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_name" class="form-label">Client Name *</label>
                                <input type="text" class="form-control" name="client_name" 
                                       value="{{ $invoice->client_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="client_email" class="form-label">Client Email</label>
                                <input type="email" class="form-control" name="client_email" 
                                       value="{{ $invoice->client_email }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_phone" class="form-label">Client Phone</label>
                                <input type="text" class="form-control" name="client_phone" 
                                       value="{{ $invoice->client_phone }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" id="edit-tax-rate-{{ $invoice->id }}"
                                       name="tax_rate" step="0.01" min="0" max="100"
                                       value="{{ $invoice->tax_rate }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="discount" class="form-label">Discount</label>
                                <input type="number" class="form-control" id="edit-discount-{{ $invoice->id }}"
                                       name="discount" step="0.01" min="0"
                                       value="{{ $invoice->discount }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="client_address" class="form-label">Client Address</label>
                            <textarea class="form-control" name="client_address" rows="2">{{ $invoice->client_address }}</textarea>
                        </div>
                        
                        <!-- Items Section -->
                        <div class="mb-3">
                            <label class="form-label">Items *</label>
                            <div id="edit-items-container-{{ $invoice->id }}">
                                @foreach($invoice->formatted_items as $index => $item)
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" 
                                               name="items[{{ $index }}][description]" 
                                               value="{{ $item['description'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control edit-quantity" data-invoice="{{ $invoice->id }}"
                                               name="items[{{ $index }}][quantity]" 
                                               value="{{ $item['quantity'] ?? 1 }}" min="0" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control edit-price" data-invoice="{{ $invoice->id }}"
                                               name="items[{{ $index }}][price]" 
                                               value="{{ $item['price'] ?? 0 }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-invoice="{{ $invoice->id }}">
                                            <i class="material-symbols-rounded">delete</i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-success mt-2 add-edit-item" 
                                    data-invoice="{{ $invoice->id }}">
                                <i class="material-symbols-rounded">add</i> Add Item
                            </button>
                            
                            <div class="row mt-3">
                                <div class="col-md-6 offset-md-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label">Subtotal</label>
                                            <input type="number" class="form-control" id="edit-subtotal-{{ $invoice->id }}"
                                                   value="{{ $invoice->subtotal }}" step="0.01" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Total</label>
                                            <input type="number" class="form-control" id="edit-total-{{ $invoice->id }}"
                                                   name="total"
                                                   value="{{ $invoice->total }}" 
                                                   step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3">{{ $invoice->notes }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="terms" class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" name="terms" rows="3">{{ $invoice->terms }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="viewed" {{ $invoice->status == 'viewed' ? 'selected' : '' }}>Viewed</option>
                                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-control" name="payment_method">
                                    <option value="">-- Select Method --</option>
                                    <option value="cash" {{ $invoice->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bank_transfer" {{ $invoice->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cheque" {{ $invoice->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="mobile_money" {{ $invoice->payment_method == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="credit_card" {{ $invoice->payment_method == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="other" {{ $invoice->payment_method == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div class="modal fade" id="recordPaymentModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment - {{ $invoice->invoice_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.invoice.record-payment', $invoice) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Invoice Total</label>
                            <input type="text" class="form-control" value="Tsh{{ number_format($invoice->total, 2) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Paid</label>
                            <input type="text" class="form-control" value="Tsh{{ number_format($invoice->amount_paid, 2) }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Balance Due</label>
                            <input type="text" class="form-control bg-light" value="Tsh{{ number_format($invoice->balance, 2) }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="amount" class="form-label">Payment Amount *</label>
                            <input type="number" class="form-control" name="amount" 
                                   step="0.01" min="0.01" max="{{ $invoice->balance }}" 
                                   value="{{ number_format($invoice->balance, 2, '.', '') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method *</label>
                            <select class="form-control" name="payment_method" required>
                                <option value="">-- Select Method --</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="transaction_reference" class="form-label">Transaction Reference</label>
                            <input type="text" class="form-control" name="transaction_reference">
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date *</label>
                            <input type="date" class="form-control" name="payment_date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Record Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Invoice Modal -->
    <div class="modal fade" id="deleteInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this invoice?</p>
                    <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                    @php
                        $deliveryNote = optional($invoice->deliveryNote);
                    @endphp
                    <p><strong>Delivery Note #:</strong> {{ $deliveryNote->delivery_note_number ?? 'N/A' }}</p>
                    <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
                    <p><strong>Total:</strong> Tsh{{ number_format($invoice->total, 2) }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.invoice.destroy', $invoice) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @endforeach

    <!-- =========================== -->
    <!-- JAVASCRIPT SECTION -->
    <!-- =========================== -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('search-invoices');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('#invoices-table tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
            
            // Generate invoice number on modal show
            const createModal = document.getElementById('createInvoiceModal');
            if (createModal) {
                createModal.addEventListener('show.bs.modal', function() {
                    // Reset form
                    const form = document.getElementById('createInvoiceForm');
                    if (form) form.reset();
                    document.getElementById('items-container').innerHTML = '';
                    document.getElementById('subtotal').value = '0.00';
                    document.getElementById('total').value = '0.00';
                    
                    // Generate invoice number via AJAX
                    fetch('{{ route("admin.invoice.generate") }}')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('invoice_number').value = data.invoice_number;
                        })
                        .catch(error => console.error('Error generating invoice number:', error));
                });
            }
            
            // Handle delivery note selection in create modal
            const deliveryNoteSelect = document.getElementById('delivery_note_id');
            if (deliveryNoteSelect) {
                deliveryNoteSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const itemsContainer = document.getElementById('items-container');
                    const subtotalInput = document.getElementById('subtotal');
                    const totalInput = document.getElementById('total');
                    const taxRateInput = document.getElementById('tax_rate');
                    
                    // Clear existing items
                    itemsContainer.innerHTML = '';
                    
                    if (!this.value) {
                        subtotalInput.value = '0.00';
                        totalInput.value = '0.00';
                        return;
                    }
                    
                    // Get data from data attributes
                    const itemsJson = selectedOption.getAttribute('data-items') || '[]';
                    
                    try {
                        const items = JSON.parse(itemsJson);
                        let subtotal = 0;
                        
                        items.forEach((item, index) => {
                            const itemRow = document.createElement('div');
                            itemRow.className = 'item-row row mb-2';
                            itemRow.innerHTML = `
                                <div class="col-md-5">
                                    <input type="text" class="form-control" 
                                           value="${item.description || ''}" readonly>
                                    <input type="hidden" name="items[${index}][description]" 
                                           value="${item.description || ''}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" 
                                           value="${item.quantity || 0}" readonly>
                                    <input type="hidden" name="items[${index}][quantity]" 
                                           value="${item.quantity || 0}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control price" 
                                           value="${item.price || 0}" step="0.01" readonly>
                                    <input type="hidden" name="items[${index}][price]" 
                                           value="${item.price || 0}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" 
                                           value="Tsh${((item.price || 0) * (item.quantity || 0)).toFixed(2)}" readonly>
                                </div>
                            `;
                            itemsContainer.appendChild(itemRow);
                            
                            subtotal += (item.quantity || 0) * (item.price || 0);
                        });
                        
                        // Calculate tax and total
                        const taxRate = parseFloat(taxRateInput.value) || 0;
                        const discount = parseFloat(document.getElementById('discount').value) || 0;
                        const tax = (subtotal * taxRate) / 100;
                        const total = subtotal + tax - discount;
                        
                        // Set values
                        subtotalInput.value = parseFloat(subtotal).toFixed(2);
                        totalInput.value = parseFloat(total).toFixed(2);
                        
                    } catch (error) {
                        console.error('Error parsing items JSON:', error);
                        itemsContainer.innerHTML = '<div class="alert alert-danger">Error loading delivery note items.</div>';
                    }
                });
            }
            
            // Calculate tax and total when tax rate or discount changes
            const taxRateInput = document.getElementById('tax_rate');
            const discountInput = document.getElementById('discount');
            
            if (taxRateInput && discountInput) {
                const calculateTotals = () => {
                    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
                    const taxRate = parseFloat(taxRateInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    const tax = (subtotal * taxRate) / 100;
                    const total = subtotal + tax - discount;
                    
                    document.getElementById('total').value = parseFloat(total).toFixed(2);
                };
                
                taxRateInput.addEventListener('input', calculateTotals);
                discountInput.addEventListener('input', calculateTotals);
            }
            
            // Edit modal functionality
            function addEditItemRow(invoiceId) {
                const container = document.getElementById(`edit-items-container-${invoiceId}`);
                if (!container) return;
                
                const rows = container.querySelectorAll('.row');
                const itemCount = rows.length;
                
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2';
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="items[${itemCount}][description]" placeholder="Description" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control edit-quantity" data-invoice="${invoiceId}"
                               name="items[${itemCount}][quantity]" min="0" value="0" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control edit-price" data-invoice="${invoiceId}"
                               name="items[${itemCount}][price]" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-invoice="${invoiceId}">
                            <i class="material-symbols-rounded">delete</i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                
                // Add event listeners
                const qtyInput = newRow.querySelector('.edit-quantity');
                const priceInput = newRow.querySelector('.edit-price');
                
                qtyInput.addEventListener('input', () => calculateEditTotals(invoiceId));
                priceInput.addEventListener('input', () => calculateEditTotals(invoiceId));
            }
            
            function calculateEditTotals(invoiceId) {
                const container = document.getElementById(`edit-items-container-${invoiceId}`);
                const subtotalInput = document.getElementById(`edit-subtotal-${invoiceId}`);
                const totalInput = document.getElementById(`edit-total-${invoiceId}`);
                const taxRateInput = document.getElementById(`edit-tax-rate-${invoiceId}`);
                const discountInput = document.getElementById(`edit-discount-${invoiceId}`);
                
                if (!container || !subtotalInput) return;
                
                let subtotal = 0;
                container.querySelectorAll('.row').forEach(row => {
                    const quantity = parseFloat(row.querySelector('.edit-quantity')?.value) || 0;
                    const price = parseFloat(row.querySelector('.edit-price')?.value) || 0;
                    subtotal += quantity * price;
                });
                
                const taxRate = parseFloat(taxRateInput?.value) || 0;
                const discount = parseFloat(discountInput?.value) || 0;
                const tax = (subtotal * taxRate) / 100;
                const total = subtotal + tax - discount;
                
                subtotalInput.value = parseFloat(subtotal).toFixed(2);
                if (totalInput) {
                    totalInput.value = parseFloat(total).toFixed(2);
                }
            }
            
            // Initialize edit modal totals
            @foreach($invoices as $invoice)
            calculateEditTotals({{ $invoice->id }});
            @endforeach
            
            // Event delegation for edit modal item management
            document.addEventListener('click', function(e) {
                // Remove item in edit modals
                if (e.target.closest('.remove-edit-item')) {
                    const row = e.target.closest('.row');
                    const invoiceId = e.target.closest('.remove-edit-item').getAttribute('data-invoice');
                    const container = document.getElementById(`edit-items-container-${invoiceId}`);
                    
                    if (container && container.querySelectorAll('.row').length > 1) {
                        row.remove();
                        calculateEditTotals(invoiceId);
                    }
                }
                
                // Add item in edit modals
                if (e.target.closest('.add-edit-item')) {
                    const invoiceId = e.target.closest('.add-edit-item').getAttribute('data-invoice');
                    addEditItemRow(invoiceId);
                }
            });
            
            // Add input event listeners for existing edit modal items
            @foreach($invoices as $invoice)
            const editContainer{{ $invoice->id }} = document.getElementById(`edit-items-container-{{ $invoice->id }}`);
            const editTaxRate{{ $invoice->id }} = document.getElementById(`edit-tax-rate-{{ $invoice->id }}`);
            const editDiscount{{ $invoice->id }} = document.getElementById(`edit-discount-{{ $invoice->id }}`);
            
            if (editContainer{{ $invoice->id }}) {
                editContainer{{ $invoice->id }}.querySelectorAll('.edit-quantity').forEach(input => {
                    input.addEventListener('input', () => calculateEditTotals({{ $invoice->id }}));
                });
                editContainer{{ $invoice->id }}.querySelectorAll('.edit-price').forEach(input => {
                    input.addEventListener('input', () => calculateEditTotals({{ $invoice->id }}));
                });
            }
            
            if (editTaxRate{{ $invoice->id }}) {
                editTaxRate{{ $invoice->id }}.addEventListener('input', () => calculateEditTotals({{ $invoice->id }}));
            }
            
            if (editDiscount{{ $invoice->id }}) {
                editDiscount{{ $invoice->id }}.addEventListener('input', () => calculateEditTotals({{ $invoice->id }}));
            }
            @endforeach
        });
    </script>
</body>
</html>