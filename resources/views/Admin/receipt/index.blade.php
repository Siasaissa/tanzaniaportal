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
              <h3 class="mb-0 h4 font-weight-bolder">Goods Receipts</h3>
              <p class="mb-0 text-sm">Manage goods received against purchase orders</p>
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
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Receipts</p>
                  <h5 class="font-weight-bolder mb-0">{{ $stats['total'] ?? 0 }}</h5>
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
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Draft</p>
                  <h5 class="font-weight-bolder mb-0">{{ $stats['draft'] ?? 0 }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">draft</i>
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
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Completed</p>
                  <h5 class="font-weight-bolder mb-0">{{ $stats['completed'] ?? 0 }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">inventory</i>
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
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Value</p>
                  <h5 class="font-weight-bolder mb-0">Tsh{{ number_format($stats['total_value'] ?? 0, 2) }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">attach_money</i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Receipts Table -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6>Receipts List</h6>
                  <p class="text-sm mb-0">
                    <i class="material-symbols-rounded text-info" style="font-size: 14px;">list</i>
                    <span class="font-weight-bold ms-1">{{ $receipts->total() }} receipts</span>
                  </p>
                </div>
                <div class="input-group" style="width: 250px;">
                  <span class="input-group-text text-body">
                    <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                  </span>
                  <input type="text" class="form-control" placeholder="Search receipts..." id="search-receipts" 
                         oninput="filterTable(this.value, 'receipts-table')">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0" id="receipts-table">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receipt #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">PO #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Supplier</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Items/Qty</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($receipts as $receipt)
                    <tr>
                      <td>
                        <div class="d-flex px-3 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $receipt->receipt_number }}</h6>
                            <p class="text-xs text-secondary mb-0">
                              <i class="material-symbols-rounded" style="font-size: 12px;">category</i>
                              @if($receipt->receipt_type == 'full_delivery')
                                Full Delivery
                              @elseif($receipt->receipt_type == 'partial_delivery')
                                Partial Delivery
                              @elseif($receipt->receipt_type == 'return')
                                Return
                              @elseif($receipt->receipt_type == 'damaged_goods')
                                Damaged Goods
                              @else
                                {{ $receipt->receipt_type }}
                              @endif
                            </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $receipt->purchaseOrder->po_number ?? 'N/A' }}</h6>
                            @if($receipt->purchaseOrder)
                            <p class="text-xs text-secondary mb-0">
                              <i class="material-symbols-rounded" style="font-size: 10px;">calendar_month</i>
                              {{ $receipt->purchaseOrder->po_date->format('M d, Y') }}
                            </p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $receipt->supplier_name }}</h6>
                            @if($receipt->supplier_contact_person)
                            <p class="text-xs text-secondary mb-0">{{ $receipt->supplier_contact_person }}</p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold">{{ $receipt->receipt_date->format('M d, Y') }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="d-flex flex-column">
                          <span class="text-xs font-weight-bold">{{ $receipt->total_items_received }} items</span>
                          <small class="text-xs text-info">{{ $receipt->total_quantity_received }} units</small>
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold text-success">Tsh{{ number_format($receipt->total_amount, 2) }}</span>
                      </td>
                      <td class="align-middle">
                        @php
                          $statusColor = match($receipt->status) {
                            'draft' => 'secondary',
                            'partial' => 'warning',
                            'completed' => 'info',
                            'verified' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary'
                          };
                        @endphp
                        <span class="badge badge-sm bg-gradient-{{ $statusColor }}">
                          {{ ucfirst($receipt->status) }}
                        </span>
                        @if($receipt->condition != 'good')
                        <br>
                        @php
                          $conditionColor = $receipt->condition == 'damaged' ? 'danger' : 
                                           ($receipt->condition == 'poor' ? 'warning' : 'info');
                        @endphp
                        <small class="badge badge-sm bg-{{ $conditionColor }}">
                          {{ ucfirst($receipt->condition) }}
                        </small>
                        @endif
                      </td>
                      <td class="align-middle">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#viewReceiptModal{{ $receipt->id }}" 
                                  title="View">
                            <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                          </button>
                          @if($receipt->status === 'draft')
                          <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#editReceiptModal{{ $receipt->id }}" 
                                  title="Edit">
                            <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                          </button>
                          @endif
                          @if($receipt->status === 'completed')
                          <button type="button" class="btn btn-link text-success px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#verifyReceiptModal{{ $receipt->id }}" 
                                  title="Verify">
                            <i class="material-symbols-rounded" style="font-size: 18px;">verified</i>
                          </button>
                          @endif
                          @if($receipt->status === 'draft')
                          <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#deleteReceiptModal{{ $receipt->id }}" 
                                  title="Delete">
                            <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                          </button>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="8" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">receipt</i>
                          <h6 class="text-secondary">No receipts found</h6>
                          <p class="text-sm text-secondary">Create your first receipt by clicking the button above</p>
                        </div>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if($receipts->hasPages())
              <div class="p-3">
                {{ $receipts->links('pagination::bootstrap-5') }}
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
  <!-- RECEIPT MODALS -->
  <!-- =========================== -->
  
  <!-- Create Receipt Modal -->
  <div class="modal fade" id="createReceiptModal" tabindex="-1" aria-labelledby="createReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createReceiptModalLabel">Create Goods Receipt</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.receipt.store') }}" method="POST" id="createReceiptForm">
          @csrf
          <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <!-- Basic Information -->
            <h6 class="text-sm font-weight-bold mb-3">Basic Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="receipt_number" class="form-control-label">Receipt Number</label>
                  <input type="text" class="form-control form-control-sm" id="receipt_number" name="receipt_number" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="receipt_date" class="form-control-label">Receipt Date *</label>
                  <input type="date" class="form-control form-control-sm" name="receipt_date" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="purchase_order_id" class="form-control-label">Select Purchase Order *</label>
                  <select class="form-control form-control-sm" name="purchase_order_id" id="purchase_order_id" required
                          onchange="handlePOSelection(this)">
                    <option value="">-- Select Purchase Order --</option>
                    @foreach($purchaseOrders ?? [] as $po)
                    <option value="{{ $po->id }}"
                            data-po-number="{{ $po->po_number }}"
                            data-supplier="{{ $po->supplier_name }}">
                      {{ $po->po_number }} - {{ $po->supplier_name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="receipt_type" class="form-control-label">Receipt Type *</label>
                  <select class="form-control form-control-sm" name="receipt_type" id="receipt_type" required
                          onchange="toggleReturnReason(this.value === 'return')">
                    <option value="full_delivery">Full Delivery</option>
                    <option value="partial_delivery">Partial Delivery</option>
                    <option value="return">Return</option>
                    <option value="damaged_goods">Damaged Goods</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- PO Information Display -->
            <div id="po-info" class="alert alert-info d-none">
              <div class="row">
                <div class="col-md-6">
                  <p class="mb-1"><strong>PO #:</strong> <span id="po-number-display"></span></p>
                  <p class="mb-1"><strong>Supplier:</strong> <span id="supplier-display"></span></p>
                </div>
                <div class="col-md-6">
                  <p class="mb-1"><strong>Ordered:</strong> <span id="total-ordered-display"></span> units</p>
                  <p class="mb-0"><strong>Remaining:</strong> <span id="remaining-display"></span> units</p>
                </div>
              </div>
            </div>
            
            <!-- Items Section -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Received Items *</h6>
            <div id="receipt-items-container">
              <!-- Items will be populated from PO selection -->
            </div>
            
            <!-- Delivery Information -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Delivery Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="delivery_note_number" class="form-control-label">Delivery Note Number</label>
                  <input type="text" class="form-control form-control-sm" name="delivery_note_number">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="vehicle_number" class="form-control-label">Vehicle Number</label>
                  <input type="text" class="form-control form-control-sm" name="vehicle_number">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="driver_name" class="form-control-label">Driver Name</label>
                  <input type="text" class="form-control form-control-sm" name="driver_name">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="driver_contact" class="form-control-label">Driver Contact</label>
                  <input type="text" class="form-control form-control-sm" name="driver_contact">
                </div>
              </div>
            </div>
            
            <!-- Quality & Storage -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Quality & Storage</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="condition" class="form-control-label">Condition *</label>
                  <select class="form-control form-control-sm" name="condition" required>
                    <option value="excellent">Excellent</option>
                    <option value="good" selected>Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                    <option value="damaged">Damaged</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="storage_location" class="form-control-label">Storage Location</label>
                  <input type="text" class="form-control form-control-sm" name="storage_location">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="bin_location" class="form-control-label">Bin/Rack Location</label>
                  <input type="text" class="form-control form-control-sm" name="bin_location">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-control-label">Total Received</label>
                  <input type="text" class="form-control form-control-sm bg-light" id="total-received-summary" value="0 units / Tsh0.00" readonly>
                </div>
              </div>
            </div>
            
            <!-- Notes -->
            <div class="row g-2">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="quality_notes" class="form-control-label">Quality Notes</label>
                  <textarea class="form-control form-control-sm" name="quality_notes" rows="2"></textarea>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="notes" class="form-control-label">Additional Notes</label>
                  <textarea class="form-control form-control-sm" name="notes" rows="2"></textarea>
                </div>
              </div>
            </div>
            
            <!-- Return Reason (only for returns) -->
            <div class="row g-2 d-none" id="return-reason-section">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="return_reason" class="form-control-label">Return Reason *</label>
                  <textarea class="form-control form-control-sm" name="return_reason" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-success btn-sm">Create Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- View Receipt Modals -->
  @foreach($receipts ?? [] as $receipt)
  @if($receipt)
  <div class="modal fade" id="viewReceiptModal{{ $receipt->id }}" tabindex="-1" aria-labelledby="viewReceiptModalLabel{{ $receipt->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-gradient-info">
          <h5 class="modal-title text-white" id="viewReceiptModalLabel{{ $receipt->id }}">View Goods Receipt</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-3">Receipt Information</h6>
                <div class="row">
                  <div class="col-6">
                    <p class="text-xs mb-1"><strong>Receipt #:</strong></p>
                    <p class="text-xs mb-1"><strong>PO #:</strong></p>
                    <p class="text-xs mb-1"><strong>Date:</strong></p>
                    <p class="text-xs mb-1"><strong>Type:</strong></p>
                  </div>
                  <div class="col-6">
                    <p class="text-xs mb-1 text-dark">{{ $receipt->receipt_number }}</p>
                    <p class="text-xs mb-1 text-dark">{{ $receipt->purchaseOrder->po_number ?? 'N/A' }}</p>
                    <p class="text-xs mb-1 text-dark">{{ $receipt->receipt_date->format('M d, Y') }}</p>
                    @if($receipt->receipt_type == 'full_delivery')
                      <span class="badge badge-sm bg-info">Full Delivery</span>
                    @elseif($receipt->receipt_type == 'partial_delivery')
                      <span class="badge badge-sm bg-warning">Partial Delivery</span>
                    @elseif($receipt->receipt_type == 'return')
                      <span class="badge badge-sm bg-danger">Return</span>
                    @elseif($receipt->receipt_type == 'damaged_goods')
                      <span class="badge badge-sm bg-danger">Damaged Goods</span>
                    @else
                      <span class="badge badge-sm bg-secondary">{{ $receipt->receipt_type }}</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-3">Status & Condition</h6>
                <div class="row">
                  <div class="col-6">
                    <p class="text-xs mb-1"><strong>Status:</strong></p>
                    <p class="text-xs mb-1"><strong>Condition:</strong></p>
                    <p class="text-xs mb-1"><strong>Received By:</strong></p>
                    <p class="text-xs mb-1"><strong>Received Date:</strong></p>
                  </div>
                  <div class="col-6">
                    @php
                      $statusColor = match($receipt->status) {
                        'draft' => 'secondary',
                        'partial' => 'warning',
                        'completed' => 'info',
                        'verified' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary'
                      };
                    @endphp
                    <span class="badge badge-sm bg-gradient-{{ $statusColor }}">
                      {{ ucfirst($receipt->status) }}
                    </span>
                    @php
                      $conditionColor = $receipt->condition == 'damaged' ? 'danger' : 
                                       ($receipt->condition == 'poor' ? 'warning' : 'info');
                    @endphp
                    <span class="badge badge-sm bg-{{ $conditionColor }}">
                      {{ ucfirst($receipt->condition) }}
                    </span>
                    <p class="text-xs mb-1 text-dark">{{ $receipt->received_by_name ?? 'N/A' }}</p>
                    <p class="text-xs mb-1 text-dark">{{ $receipt->created_at->format('M d, Y H:i') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-3">Supplier Information</h6>
                <p class="text-xs mb-1"><strong>Supplier:</strong> {{ $receipt->supplier_name }}</p>
                <p class="text-xs mb-1"><strong>Contact Person:</strong> {{ $receipt->supplier_contact_person ?? 'N/A' }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-3">Delivery Information</h6>
                <p class="text-xs mb-1"><strong>Delivery Note:</strong> {{ $receipt->delivery_note_number ?? 'N/A' }}</p>
                <p class="text-xs mb-1"><strong>Vehicle #:</strong> {{ $receipt->vehicle_number ?? 'N/A' }}</p>
                <p class="text-xs mb-1"><strong>Driver:</strong> {{ $receipt->driver_name ?? 'N/A' }}</p>
                <p class="text-xs mb-1"><strong>Driver Contact:</strong> {{ $receipt->driver_contact ?? 'N/A' }}</p>
              </div>
            </div>
          </div>
          
          @if($receipt->storage_location || $receipt->bin_location)
          <div class="card card-body border mb-4">
            <h6 class="text-sm font-weight-bold mb-3">Storage Information</h6>
            <div class="row">
              @if($receipt->storage_location)
              <div class="col-md-6">
                <p class="text-xs mb-1"><strong>Storage Location:</strong> {{ $receipt->storage_location }}</p>
              </div>
              @endif
              @if($receipt->bin_location)
              <div class="col-md-6">
                <p class="text-xs mb-1"><strong>Bin/Rack:</strong> {{ $receipt->bin_location }}</p>
              </div>
              @endif
            </div>
          </div>
          @endif
          
          <h6 class="text-sm font-weight-bold mb-3">Received Items</h6>
          <div class="table-responsive">
            <table class="table table-sm table-bordered">
              <thead class="bg-light">
                <tr>
                  <th class="text-xs font-weight-bold">Item Description</th>
                  <th class="text-xs font-weight-bold text-center">Ordered Qty</th>
                  <th class="text-xs font-weight-bold text-center">Received Qty</th>
                  <th class="text-xs font-weight-bold text-center">Unit</th>
                  <th class="text-xs font-weight-bold text-center">Price</th>
                  <th class="text-xs font-weight-bold text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                @php
                  // $receipt->items is already an array due to Laravel casting
                  $items = is_array($receipt->items) ? $receipt->items : [];
                @endphp
                @if(count($items) > 0)
                  @foreach($items as $item)
                  @php
                    $quantityReceived = $item['quantity_received'] ?? 0;
                    $price = $item['price'] ?? 0;
                    $total = $quantityReceived * $price;
                  @endphp
                  <tr>
                    <td class="text-xs">{{ $item['description'] ?? 'N/A' }}</td>
                    <td class="text-xs text-center">{{ number_format($item['quantity_ordered'] ?? 0) }}</td>
                    <td class="text-xs text-center">{{ number_format($quantityReceived) }}</td>
                    <td class="text-xs text-center">{{ $item['unit'] ?? 'pcs' }}</td>
                    <td class="text-xs text-center">Tsh{{ number_format($price, 2) }}</td>
                    <td class="text-xs text-center">Tsh{{ number_format($total, 2) }}</td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="6" class="text-center text-xs text-muted py-2">No items found</td>
                  </tr>
                @endif
              </tbody>
              <tfoot class="bg-light">
                <tr>
                  <td colspan="2" class="text-xs font-weight-bold">Summary</td>
                  <td class="text-xs text-center font-weight-bold">{{ $receipt->total_quantity_received }} units</td>
                  <td class="text-xs text-center font-weight-bold">{{ $receipt->total_items_received }} items</td>
                  <td colspan="2" class="text-xs text-center font-weight-bold text-success">Total: Tsh{{ number_format($receipt->total_amount, 2) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
          
          @if($receipt->quality_notes || $receipt->notes)
          <div class="row mt-3">
            @if($receipt->quality_notes)
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-2">Quality Notes</h6>
                <p class="text-xs text-muted">{{ $receipt->quality_notes }}</p>
              </div>
            </div>
            @endif
            @if($receipt->notes)
            <div class="col-md-6">
              <div class="card card-body border">
                <h6 class="text-sm font-weight-bold mb-2">Additional Notes</h6>
                <p class="text-xs text-muted">{{ $receipt->notes }}</p>
              </div>
            </div>
            @endif
          </div>
          @endif
          
          @if($receipt->return_reason)
          <div class="card card-body border mt-3 bg-light">
            <h6 class="text-sm font-weight-bold mb-2 text-danger">Return Reason</h6>
            <p class="text-xs text-muted">{{ $receipt->return_reason }}</p>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endif
  @endforeach
  
  <!-- Edit Receipt Modals -->
  @foreach($receipts ?? [] as $receipt)
  @if($receipt && $receipt->status === 'draft')
  <div class="modal fade" id="editReceiptModal{{ $receipt->id }}" tabindex="-1" aria-labelledby="editReceiptModalLabel{{ $receipt->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-gradient-warning">
          <h5 class="modal-title text-white" id="editReceiptModalLabel{{ $receipt->id }}">Edit Goods Receipt</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.receipt.update', $receipt) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <div class="alert alert-info">
              <div class="d-flex">
                <i class="material-symbols-rounded me-2">info</i>
                <span class="text-sm">Editing receipt: {{ $receipt->receipt_number }}</span>
              </div>
            </div>
            
            <h6 class="text-sm font-weight-bold mb-3">Basic Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-control-label">Receipt Number</label>
                  <input type="text" class="form-control form-control-sm bg-light" value="{{ $receipt->receipt_number }}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="receipt_date_{{ $receipt->id }}" class="form-control-label">Receipt Date *</label>
                  <input type="date" class="form-control form-control-sm" name="receipt_date" 
                         id="receipt_date_{{ $receipt->id }}" 
                         value="{{ $receipt->receipt_date->format('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-control-label">PO Number</label>
                  <input type="text" class="form-control form-control-sm bg-light" 
                         value="{{ $receipt->purchaseOrder->po_number ?? 'N/A' }}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="receipt_type_{{ $receipt->id }}" class="form-control-label">Receipt Type *</label>
                  <select class="form-control form-control-sm" name="receipt_type" 
                          id="receipt_type_{{ $receipt->id }}" required>
                    <option value="full_delivery" {{ $receipt->receipt_type == 'full_delivery' ? 'selected' : '' }}>Full Delivery</option>
                    <option value="partial_delivery" {{ $receipt->receipt_type == 'partial_delivery' ? 'selected' : '' }}>Partial Delivery</option>
                    <option value="return" {{ $receipt->receipt_type == 'return' ? 'selected' : '' }}>Return</option>
                    <option value="damaged_goods" {{ $receipt->receipt_type == 'damaged_goods' ? 'selected' : '' }}>Damaged Goods</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Items Section -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Received Items *</h6>
            @php
              // $receipt->items is already an array due to Laravel casting
              $items = is_array($receipt->items) ? $receipt->items : [];
            @endphp
            <div id="edit-items-container-{{ $receipt->id }}">
              @foreach($items as $index => $item)
              <div class="item-row mb-3 p-2 border rounded">
                <div class="row g-2">
                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" 
                           value="{{ $item['description'] ?? '' }}" readonly>
                    <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}">
                    <input type="hidden" name="items[{{ $index }}][price]" value="{{ $item['price'] ?? 0 }}">
                    <input type="hidden" name="items[{{ $index }}][unit]" value="{{ $item['unit'] ?? 'pcs' }}">
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm" 
                           value="{{ $item['quantity_ordered'] ?? 0 }}" readonly>
                    <input type="hidden" name="items[{{ $index }}][quantity_ordered]" value="{{ $item['quantity_ordered'] ?? 0 }}">
                  </div>
                  <div class="col-md-3">
                    <input type="number" class="form-control form-control-sm" 
                           name="items[{{ $index }}][quantity_received]" 
                           min="0" 
                           value="{{ $item['quantity_received'] ?? 0 }}" 
                           required>
                  </div>
                  <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" 
                           value="{{ $item['unit'] ?? 'pcs' }}" readonly>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
            
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Delivery Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="delivery_note_number_{{ $receipt->id }}" class="form-control-label">Delivery Note Number</label>
                  <input type="text" class="form-control form-control-sm" name="delivery_note_number" 
                         id="delivery_note_number_{{ $receipt->id }}" 
                         value="{{ $receipt->delivery_note_number }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="vehicle_number_{{ $receipt->id }}" class="form-control-label">Vehicle Number</label>
                  <input type="text" class="form-control form-control-sm" name="vehicle_number" 
                         id="vehicle_number_{{ $receipt->id }}" 
                         value="{{ $receipt->vehicle_number }}">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="driver_name_{{ $receipt->id }}" class="form-control-label">Driver Name</label>
                  <input type="text" class="form-control form-control-sm" name="driver_name" 
                         id="driver_name_{{ $receipt->id }}" 
                         value="{{ $receipt->driver_name }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="driver_contact_{{ $receipt->id }}" class="form-control-label">Driver Contact</label>
                  <input type="text" class="form-control form-control-sm" name="driver_contact" 
                         id="driver_contact_{{ $receipt->id }}" 
                         value="{{ $receipt->driver_contact }}">
                </div>
              </div>
            </div>
            
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Quality & Storage</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="condition_{{ $receipt->id }}" class="form-control-label">Condition *</label>
                  <select class="form-control form-control-sm" name="condition" 
                          id="condition_{{ $receipt->id }}" required>
                    <option value="excellent" {{ $receipt->condition == 'excellent' ? 'selected' : '' }}>Excellent</option>
                    <option value="good" {{ $receipt->condition == 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ $receipt->condition == 'fair' ? 'selected' : '' }}>Fair</option>
                    <option value="poor" {{ $receipt->condition == 'poor' ? 'selected' : '' }}>Poor</option>
                    <option value="damaged" {{ $receipt->condition == 'damaged' ? 'selected' : '' }}>Damaged</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="storage_location_{{ $receipt->id }}" class="form-control-label">Storage Location</label>
                  <input type="text" class="form-control form-control-sm" name="storage_location" 
                         id="storage_location_{{ $receipt->id }}" 
                         value="{{ $receipt->storage_location }}">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="bin_location_{{ $receipt->id }}" class="form-control-label">Bin/Rack Location</label>
                  <input type="text" class="form-control form-control-sm" name="bin_location" 
                         id="bin_location_{{ $receipt->id }}" 
                         value="{{ $receipt->bin_location }}">
                </div>
              </div>
            </div>
            
            <!-- Notes -->
            <div class="row g-2">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="quality_notes_{{ $receipt->id }}" class="form-control-label">Quality Notes</label>
                  <textarea class="form-control form-control-sm" name="quality_notes" 
                            id="quality_notes_{{ $receipt->id }}" rows="2">{{ $receipt->quality_notes }}</textarea>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-12">
                <div class="form-group mb-3">
                  <label for="notes_{{ $receipt->id }}" class="form-control-label">Additional Notes</label>
                  <textarea class="form-control form-control-sm" name="notes" 
                            id="notes_{{ $receipt->id }}" rows="2">{{ $receipt->notes }}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-warning btn-sm">Update Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
  @endforeach
  
  <!-- Verify Receipt Modals -->
  @foreach($receipts ?? [] as $receipt)
  @if($receipt && $receipt->status === 'completed')
  <div class="modal fade" id="verifyReceiptModal{{ $receipt->id }}" tabindex="-1" aria-labelledby="verifyReceiptModalLabel{{ $receipt->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-gradient-success">
          <h5 class="modal-title text-white" id="verifyReceiptModalLabel{{ $receipt->id }}">Verify Goods Receipt</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.receipt.status.update', $receipt) }}" method="POST">
          @csrf
          <input type="hidden" name="status" value="verified">
          <div class="modal-body">
            <div class="text-center mb-3">
              <i class="material-symbols-rounded text-success" style="font-size: 64px;">verified</i>
            </div>
            <p class="text-center">Are you sure you want to verify this receipt?</p>
            
            <div class="card card-body border mb-3">
              <div class="row">
                <div class="col-6">
                  <p class="text-xs mb-1"><strong>Receipt #:</strong></p>
                  <p class="text-xs mb-1"><strong>Supplier:</strong></p>
                  <p class="text-xs mb-1"><strong>Total Items:</strong></p>
                  <p class="text-xs mb-1"><strong>Total Amount:</strong></p>
                </div>
                <div class="col-6">
                  <p class="text-xs mb-1 text-dark">{{ $receipt->receipt_number }}</p>
                  <p class="text-xs mb-1 text-dark">{{ $receipt->supplier_name }}</p>
                  <p class="text-xs mb-1 text-dark">{{ $receipt->total_items_received }} items</p>
                  <p class="text-xs mb-1 text-success">Tsh{{ number_format($receipt->total_amount, 2) }}</p>
                </div>
              </div>
            </div>
            
            <div class="form-group mb-3">
              <label for="verification_notes_{{ $receipt->id }}" class="form-control-label">Verification Notes (Optional)</label>
              <textarea class="form-control form-control-sm" name="verification_notes" 
                        id="verification_notes_{{ $receipt->id }}" rows="2" 
                        placeholder="Add any notes about the verification..."></textarea>
            </div>
            
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="confirm_verification" 
                     id="confirm_verification_{{ $receipt->id }}" required>
              <label class="form-check-label text-sm" for="confirm_verification_{{ $receipt->id }}">
                I confirm that I have physically verified the goods and all quantities are correct.
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-success btn-sm">Verify Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
  @endforeach
  
  <!-- Delete Receipt Modals -->
  @foreach($receipts ?? [] as $receipt)
  @if($receipt && $receipt->status === 'draft')
  <div class="modal fade" id="deleteReceiptModal{{ $receipt->id }}" tabindex="-1" aria-labelledby="deleteReceiptModalLabel{{ $receipt->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-gradient-danger">
          <h5 class="modal-title text-white" id="deleteReceiptModalLabel{{ $receipt->id }}">Delete Goods Receipt</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.receipt.destroy', $receipt) }}" method="POST">
          @csrf
          @method('DELETE')
          <div class="modal-body">
            <div class="text-center mb-3">
              <i class="material-symbols-rounded text-danger" style="font-size: 64px;">warning</i>
            </div>
            <p class="text-center text-danger font-weight-bold">Are you sure you want to delete this receipt?</p>
            
            <div class="card card-body border border-danger mb-3">
              <div class="row">
                <div class="col-6">
                  <p class="text-xs mb-1"><strong>Receipt #:</strong></p>
                  <p class="text-xs mb-1"><strong>Supplier:</strong></p>
                  <p class="text-xs mb-1"><strong>Date:</strong></p>
                  <p class="text-xs mb-1"><strong>Status:</strong></p>
                </div>
                <div class="col-6">
                  <p class="text-xs mb-1 text-dark">{{ $receipt->receipt_number }}</p>
                  <p class="text-xs mb-1 text-dark">{{ $receipt->supplier_name }}</p>
                  <p class="text-xs mb-1 text-dark">{{ $receipt->receipt_date->format('M d, Y') }}</p>
                  <span class="badge badge-sm bg-gradient-secondary">
                    {{ ucfirst($receipt->status) }}
                  </span>
                </div>
              </div>
            </div>
            
            <div class="alert alert-warning">
              <div class="d-flex">
                <i class="material-symbols-rounded me-2">error</i>
                <span class="text-sm">
                  <strong>Warning:</strong> This action cannot be undone. All receipt data will be permanently deleted.
                </span>
              </div>
            </div>
            
            <div class="form-group mb-3">
              <label for="delete_reason_{{ $receipt->id }}" class="form-control-label">Delete Reason (Required)</label>
              <textarea class="form-control form-control-sm" name="delete_reason" 
                        id="delete_reason_{{ $receipt->id }}" rows="2" 
                        placeholder="Please provide a reason for deleting this receipt..." required></textarea>
            </div>
            
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="confirm_delete" 
                     id="confirm_delete_{{ $receipt->id }}" required>
              <label class="form-check-label text-sm text-danger" for="confirm_delete_{{ $receipt->id }}">
                I understand that this action is irreversible and I want to proceed with deletion.
              </label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-danger btn-sm">Delete Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
  @endforeach
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    // Simple table filter
    function filterTable(searchTerm, tableId) {
        const search = searchTerm.toLowerCase();
        const table = document.getElementById(tableId);
        if (!table) return;
        
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(search) ? '' : 'none';
        });
    }
    
    // Handle PO selection
    function handlePOSelection(selectElement) {
        if (!selectElement.value) {
            document.getElementById('po-info').classList.add('d-none');
            document.getElementById('receipt-items-container').innerHTML = '';
            updateTotalReceived();
            return;
        }
        
        // Show loading
        const itemsContainer = document.getElementById('receipt-items-container');
        itemsContainer.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-sm text-muted mt-2">Loading PO details...</p>
            </div>
        `;
        
        // Fetch PO details
        fetch(`/admin/receipts/purchase-order/${selectElement.value}/details`)
            .then(response => response.json())
            .then(data => {
                // Show PO info
                const poInfo = document.getElementById('po-info');
                document.getElementById('po-number-display').textContent = data.po?.po_number || 'N/A';
                document.getElementById('supplier-display').textContent = data.po?.supplier_name || 'N/A';
                document.getElementById('total-ordered-display').textContent = data.total_ordered || 0;
                document.getElementById('remaining-display').textContent = data.remaining || 0;
                poInfo.classList.remove('d-none');
                
                // Populate items
                populateItems(data.items || []);
            })
            .catch(error => {
                console.error('Error:', error);
                itemsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        Error loading PO details. Please try again.
                    </div>
                `;
            });
    }
    
    // Simple populate items
    // Simple populate items
function populateItems(items) {
    const container = document.getElementById('receipt-items-container');
    let html = '';
    
    if (items.length === 0) {
        html = '<div class="alert alert-info">No items found in this purchase order.</div>';
    } else {
        items.forEach((item, index) => {
            const description = item.description || '';
            const quantityOrdered = item.quantity_ordered || 0;
            const price = parseFloat(item.price) || 0;
            const remaining = item.remaining || 0;
            const unit = item.unit || 'pc';
            
            html += `
                <div class="item-row mb-3 p-2 border rounded">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control form-control-sm" 
                                   value="${description}" readonly>
                            <input type="hidden" name="items[${index}][description]" value="${description}">
                            <input type="hidden" name="items[${index}][price]" value="${price}">
                            <input type="hidden" name="items[${index}][unit]" value="${unit}">
                            <input type="hidden" name="items[${index}][quantity_ordered]" value="${quantityOrdered}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control form-control-sm" 
                                   value="${quantityOrdered}" readonly>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control form-control-sm receipt-quantity" 
                                   name="items[${index}][quantity_received]" 
                                   min="0" 
                                   max="${remaining}"
                                   value="0" 
                                   data-price="${price}"
                                   required
                                   oninput="calculateTotal()">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control form-control-sm" value="${unit}" readonly>
                        </div>
                        <div class="col-md-1">
                            <span class="badge badge-sm bg-info">Max: ${remaining}</span>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12">
                            <small class="text-muted">
                                Price: Tsh${price.toFixed(2)} | 
                                Total: <span id="item-total-${index}">Tsh0.00</span>
                            </small>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    container.innerHTML = html;
    calculateTotal();
}
    // Calculate totals
    function calculateTotal() {
        let totalQuantity = 0;
        let totalAmount = 0;
        
        // Calculate each item
        document.querySelectorAll('.receipt-quantity').forEach((input, index) => {
            const quantity = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            const itemTotal = quantity * price;
            
            // Update item total display
            const itemTotalElement = document.getElementById(`item-total-${index}`);
            if (itemTotalElement) {
                itemTotalElement.textContent = `Tsh${itemTotal.toFixed(2)}`;
            }
            
            totalQuantity += quantity;
            totalAmount += itemTotal;
        });
        
        // Update summary
        document.getElementById('total-received-summary').value = 
            `${totalQuantity} units / Tsh${totalAmount.toFixed(2)}`;
    }
    
    // Toggle return reason
    function toggleReturnReason(show) {
        const section = document.getElementById('return-reason-section');
        const textarea = section.querySelector('textarea');
        section.classList.toggle('d-none', !show);
        if (show) {
            textarea.setAttribute('required', 'required');
        } else {
            textarea.removeAttribute('required');
        }
    }
    
    // Generate receipt number on modal open
    document.getElementById('createReceiptModal').addEventListener('show.bs.modal', function() {
        fetch('{{ route("admin.receipt.generate") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('receipt_number').value = data.receipt_number;
            })
            .catch(() => {
                // Fallback if API fails
                const date = new Date();
                const random = Math.floor(Math.random() * 10000);
                document.getElementById('receipt_number').value = `RC-${date.getFullYear()}${String(date.getMonth()+1).padStart(2,'0')}${String(date.getDate()).padStart(2,'0')}-${random}`;
            });
    });
    
    // Show success/error messages
    @if(session('success'))
    showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
    showToast('{{ session('error') }}', 'danger');
    @endif
    
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const container = document.getElementById('toast-container') || (() => {
            const div = document.createElement('div');
            div.id = 'toast-container';
            div.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(div);
            return div;
        })();
        
        container.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
    }
  </script>
</body>
</html>