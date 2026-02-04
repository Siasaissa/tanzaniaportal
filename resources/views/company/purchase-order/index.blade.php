<!DOCTYPE html>
<html lang="en">
@include('layouts.adminhead')

<body class="g-sidenav-show bg-gray-100">
  @include('layouts.staffnavbar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('layouts.topnav')
    
    <div class="container-fluid py-4">
      <!-- Header -->
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="mb-0 h4 font-weight-bolder">Purchase Orders</h3>
              <p class="mb-0 text-sm">Manage your company's purchase orders</p>
            </div>
            
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Total PO</p>
                  <h5 class="font-weight-bolder mb-0">{{ $stats['total'] }}</h5>
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
        
        
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Completed</p>
                  <h5 class="font-weight-bolder mb-0">{{ $stats['completed'] }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">done_all</i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Value</p>
                  <h5 class="font-weight-bolder mb-0">Tsh{{ number_format($stats['total_value'], 2) }}</h5>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">attach_money</i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Purchase Orders Table -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6>Purchase Orders List</h6>
                  <p class="text-sm mb-0">
                    <i class="material-symbols-rounded text-info" style="font-size: 14px;">list</i>
                    <span class="font-weight-bold ms-1">{{ $purchaseOrders->total() }} purchase orders</span>
                  </p>
                </div>
                <div class="input-group" style="width: 250px;">
                  <span class="input-group-text text-body">
                    <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                  </span>
                  <input type="text" class="form-control" placeholder="Search PO..." id="search-po">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0" id="po-table">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">PO #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Supplier</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                      <td>
                        <div class="d-flex px-3 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $po->po_number }}</h6>
                            <p class="text-xs text-secondary mb-0">
                              <i class="material-symbols-rounded" style="font-size: 12px;">calendar_month</i>
                              {{ $po->po_date->format('M d, Y') }}
                            </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $po->supplier_name }}</h6>
                            @if($po->supplier_contact_person)
                            <p class="text-xs text-secondary mb-0">{{ $po->supplier_contact_person }}</p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold">{{ $po->po_date->format('M d, Y') }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="d-flex flex-column">
                          <span class="text-xs font-weight-bold text-success">Tsh{{ number_format($po->total_amount, 2) }}</span>
                          @if($po->tax_amount > 0)
                          <small class="text-xs text-info">Tax: Tsh{{ number_format($po->tax_amount, 2) }}</small>
                          @endif
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="badge badge-sm bg-gradient-{{ $po->status_color }}">
                          <i class="material-symbols-rounded" style="font-size: 12px; vertical-align: middle;">
                            {{ $po->status_icon }}
                          </i>
                          {{ str_replace('_', ' ', ucfirst($po->status)) }}
                        </span>
                      </td>
                      <td class="align-middle">
                        @if($po->expected_delivery_date)
                        <span class="text-xs font-weight-bold {{ $po->days_until_delivery < 0 ? 'text-danger' : ($po->days_until_delivery < 3 ? 'text-warning' : '') }}">
                          {{ $po->expected_delivery_date->format('M d, Y') }}
                          @if($po->days_until_delivery !== null)
                          <br>
                          <small class="{{ $po->days_until_delivery < 0 ? 'text-danger' : ($po->days_until_delivery < 3 ? 'text-warning' : 'text-success') }}">
                            {{ abs($po->days_until_delivery) }} days {{ $po->days_until_delivery < 0 ? 'ago' : 'left' }}
                          </small>
                          @endif
                        </span>
                        @else
                        <span class="text-xs text-secondary">Not set</span>
                        @endif
                      </td>
                      <td class="align-middle">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#viewPOModal{{ $po->id }}" 
                                  title="View">
                            <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                          </button>
                          @if($po->can_edit)
                          <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#editPOModal{{ $po->id }}" 
                                  title="Edit">
                            <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                          </button>
                          @endif
                          @if($po->can_approve)
                          <button type="button" class="btn btn-link text-success px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#approvePOModal{{ $po->id }}" 
                                  title="Approve">
                            <i class="material-symbols-rounded" style="font-size: 18px;">check_circle</i>
                          </button>
                          @endif
                          <a href="{{ route('company.purchase-order.download', $po) }}" 
                             class="btn btn-link text-primary px-2 mb-0" 
                             title="Download PDF">
                            <i class="material-symbols-rounded" style="font-size: 18px;">download</i>
                          </a>
                          @if($po->can_edit)
                          <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#deletePOModal{{ $po->id }}" 
                                  title="Delete">
                            <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                          </button>
                          @endif
                        </div>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">receipt</i>
                          <h6 class="text-secondary">No purchase orders found</h6>
                          <p class="text-sm text-secondary">Create your first purchase order by clicking the button above</p>
                        </div>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if($purchaseOrders->hasPages())
              <div class="p-3">
                {{ $purchaseOrders->links('pagination::bootstrap-5') }}
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
  <!-- ALL MODALS ON SAME PAGE -->
  <!-- =========================== -->
  
  <!-- 1. CREATE PURCHASE ORDER MODAL -->
  <div class="modal fade" id="createPurchaseOrderModal" tabindex="-1" aria-labelledby="createPurchaseOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createPurchaseOrderModalLabel">Create New Purchase Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('company.purchase-order.store') }}" method="POST" id="createPOForm">
          @csrf
          <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <!-- Basic Information -->
            <h6 class="text-sm font-weight-bold mb-3">Basic Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="po_number" class="form-control-label">PO Number</label>
                  <input type="text" class="form-control form-control-sm" id="po_number" name="po_number" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="po_date" class="form-control-label">PO Date *</label>
                  <input type="date" class="form-control form-control-sm" name="po_date" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="expected_delivery_date" class="form-control-label">Expected Delivery Date</label>
                  <input type="date" class="form-control form-control-sm" name="expected_delivery_date">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="status" class="form-control-label">Status *</label>
                  <select class="form-control form-control-sm" name="status" required>
                    <option value="draft">Draft</option>
                    <option value="pending_approval">Pending Approval</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Supplier Information -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Supplier Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="supplier_name" class="form-control-label">Supplier Name *</label>
                  <input type="text" class="form-control form-control-sm" name="supplier_name" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="supplier_contact_person" class="form-control-label">Contact Person</label>
                  <input type="text" class="form-control form-control-sm" name="supplier_contact_person">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="supplier_email" class="form-control-label">Supplier Email</label>
                  <input type="email" class="form-control form-control-sm" name="supplier_email">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="supplier_phone" class="form-control-label">Supplier Phone</label>
                  <input type="text" class="form-control form-control-sm" name="supplier_phone">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="payment_terms" class="form-control-label">Payment Terms *</label>
                  <select class="form-control form-control-sm" name="payment_terms" required>
                    <option value="net_30">Net 30 Days</option>
                    <option value="net_15">Net 15 Days</option>
                    <option value="net_45">Net 45 Days</option>
                    <option value="net_60">Net 60 Days</option>
                    <option value="upon_delivery">Upon Delivery</option>
                    <option value="advance_payment">Advance Payment</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="delivery_method" class="form-control-label">Delivery Method</label>
                  <select class="form-control form-control-sm" name="delivery_method">
                    <option value="">-- Select --</option>
                    <option value="pickup">Pickup</option>
                    <option value="delivery">Delivery</option>
                    <option value="courier">Courier</option>
                    <option value="freight">Freight</option>
                  </select>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-12">
                <div class="form-group mb-3">
                  <label for="supplier_address" class="form-control-label">Supplier Address</label>
                  <textarea class="form-control form-control-sm" name="supplier_address" rows="2"></textarea>
                </div>
              </div>
            </div>
            
            <!-- Items Section -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Order Items *</h6>
            <div id="po-items-container">
              <div class="item-row mb-3 p-2 border rounded">
                <div class="row g-2">
                  <div class="col-md-5">
                    <input type="text" class="form-control form-control-sm" name="items[0][description]" placeholder="Description" required>
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm po-quantity" name="items[0][quantity]" 
                           placeholder="Qty" min="1" value="1" required>
                  </div>
                  <div class="col-md-2">
                    <select class="form-control form-control-sm" name="items[0][unit]">
                      <option value="">Unit</option>
                      <option value="pcs">Pcs</option>
                      <option value="kg">Kg</option>
                      <option value="l">L</option>
                      <option value="m">M</option>
                      <option value="box">Box</option>
                      <option value="set">Set</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm po-price" name="items[0][price]" 
                           placeholder="Price" step="0.01" min="0" required>
                  </div>
                  <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-danger remove-po-item w-100">
                      <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <button type="button" class="btn btn-sm bg-gradient-success mt-2" id="add-po-item">
              <i class="material-symbols-rounded" style="font-size: 16px;">add</i> Add Item
            </button>
            
            <!-- Financial Information -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Financial Information</h6>
            <div class="row g-2">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="tax_rate" class="form-control-label">Tax Rate (%)</label>
                  <input type="number" class="form-control form-control-sm" id="tax_rate" name="tax_rate" 
                         step="0.01" min="0" max="100" value="0">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="discount" class="form-control-label">Discount</label>
                  <input type="number" class="form-control form-control-sm" id="discount" name="discount" 
                         step="0.01" min="0" value="0">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label for="shipping_cost" class="form-control-label">Shipping Cost</label>
                  <input type="number" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" 
                         step="0.01" min="0" value="0">
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-control-label">Subtotal</label>
                  <input type="text" class="form-control form-control-sm bg-light" id="po-subtotal" value="0.00" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-control-label">Tax Amount</label>
                  <input type="text" class="form-control form-control-sm bg-light" id="po-tax-amount" value="0.00" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group mb-3">
                  <label class="form-control-label">Total Amount</label>
                  <input type="text" class="form-control form-control-sm bg-light" id="po-total-amount" 
                         name="total_amount" value="0.00" readonly>
                </div>
              </div>
            </div>
            
            <!-- Shipping & Notes -->
            <h6 class="text-sm font-weight-bold mb-3 mt-4">Shipping & Additional Information</h6>
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="shipping_address" class="form-control-label">Shipping Address</label>
                  <textarea class="form-control form-control-sm" name="shipping_address" rows="2"></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="shipping_instructions" class="form-control-label">Shipping Instructions</label>
                  <textarea class="form-control form-control-sm" name="shipping_instructions" rows="2"></textarea>
                </div>
              </div>
            </div>
            
            <div class="row g-2">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="notes" class="form-control-label">Notes</label>
                  <textarea class="form-control form-control-sm" name="notes" rows="2"></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label for="terms_conditions" class="form-control-label">Terms & Conditions</label>
                  <textarea class="form-control form-control-sm" name="terms_conditions" rows="2"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-success btn-sm">Create Purchase Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- 2. VIEW/EDIT/APPROVE/DELETE MODALS FOR EACH PO -->
  @foreach($purchaseOrders as $po)
    <!-- View PO Modal {{ $po->id }} -->
    <div class="modal fade" id="viewPOModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Purchase Order #{{ $po->po_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Supplier Information</h6>
                <p><strong>{{ $po->supplier_name }}</strong></p>
                @if($po->supplier_contact_person)
                <p>Contact: {{ $po->supplier_contact_person }}</p>
                @endif
                @if($po->supplier_email)
                <p>Email: {{ $po->supplier_email }}</p>
                @endif
                @if($po->supplier_phone)
                <p>Phone: {{ $po->supplier_phone }}</p>
                @endif
                @if($po->supplier_address)
                <p style="white-space: pre-line;">{{ $po->supplier_address }}</p>
                @endif
              </div>
              <div class="col-md-6">
                <h6>PO Details</h6>
                <p><strong>PO Date:</strong> {{ $po->po_date->format('M d, Y') }}</p>
                @if($po->expected_delivery_date)
                <p><strong>Expected Delivery:</strong> {{ $po->expected_delivery_date->format('M d, Y') }}</p>
                @endif
                <p><strong>Status:</strong> 
                  <span class="badge badge-sm bg-gradient-{{ $po->status_color }}">
                    {{ str_replace('_', ' ', ucfirst($po->status)) }}
                  </span>
                </p>
                <p><strong>Payment Terms:</strong> {{ $po->payment_terms_text }}</p>
                @if($po->delivery_method)
                <p><strong>Delivery Method:</strong> {{ ucfirst($po->delivery_method) }}</p>
                @endif
              </div>
            </div>
            
            <hr class="my-3">
            
            <h6>Order Items</h6>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($po->formatted_items as $item)
                  <tr>
                    <td>{{ $item['description'] ?? '' }}</td>
                    <td>{{ $item['quantity'] ?? 0 }}</td>
                    <td>{{ $item['unit'] ?? '-' }}</td>
                    <td>Tsh{{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td>Tsh{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                    <td><strong>Tsh{{ number_format($po->subtotal, 2) }}</strong></td>
                  </tr>
                  @if($po->tax_amount > 0)
                  <tr>
                    <td colspan="4" class="text-end"><strong>Tax ({{ $po->tax_rate }}%):</strong></td>
                    <td><strong>Tsh{{ number_format($po->tax_amount, 2) }}</strong></td>
                  </tr>
                  @endif
                  @if($po->discount > 0)
                  <tr>
                    <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                    <td><strong>Tsh{{ number_format($po->discount, 2) }}</strong></td>
                  </tr>
                  @endif
                  @if($po->shipping_cost > 0)
                  <tr>
                    <td colspan="4" class="text-end"><strong>Shipping Cost:</strong></td>
                    <td><strong>Tsh{{ number_format($po->shipping_cost, 2) }}</strong></td>
                  </tr>
                  @endif
                  <tr class="table-success">
                    <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                    <td><strong>Tsh{{ number_format($po->total_amount, 2) }}</strong></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            
            @if($po->shipping_address || $po->notes || $po->terms_conditions)
            <hr class="my-3">
            
            @if($po->shipping_address)
            <div class="mb-3">
              <h6>Shipping Address</h6>
              <div style="white-space: pre-line;">{{ $po->shipping_address }}</div>
            </div>
            @endif
            
            @if($po->shipping_instructions)
            <div class="mb-3">
              <h6>Shipping Instructions</h6>
              <p>{{ $po->shipping_instructions }}</p>
            </div>
            @endif
            
            @if($po->notes)
            <div class="mb-3">
              <h6>Notes</h6>
              <div style="white-space: pre-line;">{{ $po->notes }}</div>
            </div>
            @endif
            
            @if($po->terms_conditions)
            <div class="mb-3">
              <h6>Terms & Conditions</h6>
              <div style="white-space: pre-line;">{{ $po->terms_conditions }}</div>
            </div>
            @endif
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="{{ route('company.purchase-order.print', $po) }}" class="btn btn-primary" target="_blank">
              <i class="material-symbols-rounded">print</i> Print
            </a>
            <a href="{{ route('company.purchase-order.download', $po) }}" class="btn btn-success">
              <i class="material-symbols-rounded">download</i> Download PDF
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Edit PO Modal {{ $po->id }} -->
    @if($po->can_edit)
    <div class="modal fade" id="editPOModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Purchase Order #{{ $po->po_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('company.purchase-order.update', $po) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
              <div class="row g-2">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label class="form-control-label">PO Number</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $po->po_number }}" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="edit_po_date{{ $po->id }}" class="form-control-label">PO Date *</label>
                    <input type="date" class="form-control form-control-sm" name="po_date" 
                           id="edit_po_date{{ $po->id }}" value="{{ $po->po_date->format('Y-m-d') }}" required>
                  </div>
                </div>
              </div>
              
              <div class="row g-2">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="edit_expected_delivery_date{{ $po->id }}" class="form-control-label">Expected Delivery Date</label>
                    <input type="date" class="form-control form-control-sm" name="expected_delivery_date" 
                           id="edit_expected_delivery_date{{ $po->id }}" 
                           value="{{ $po->expected_delivery_date ? $po->expected_delivery_date->format('Y-m-d') : '' }}">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="edit_status{{ $po->id }}" class="form-control-label">Status *</label>
                    <select class="form-control form-control-sm" name="status" 
                            id="edit_status{{ $po->id }}" required>
                      <option value="draft" {{ $po->status == 'draft' ? 'selected' : '' }}>Draft</option>
                      <option value="pending_approval" {{ $po->status == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- Items Section for Edit -->
              <h6 class="text-sm font-weight-bold mb-3 mt-4">Order Items *</h6>
              <div id="edit-po-items-container-{{ $po->id }}">
                @foreach($po->formatted_items as $index => $item)
                <div class="item-row mb-3 p-2 border rounded">
                  <div class="row g-2">
                    <div class="col-md-5">
                      <input type="text" class="form-control form-control-sm edit-po-item-desc" 
                             name="items[{{ $index }}][description]" 
                             value="{{ $item['description'] ?? '' }}" required>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control form-control-sm edit-po-quantity" 
                             data-po="{{ $po->id }}"
                             name="items[{{ $index }}][quantity]" 
                             value="{{ $item['quantity'] ?? 1 }}" min="1" required>
                    </div>
                    <div class="col-md-2">
                      <select class="form-control form-control-sm" name="items[{{ $index }}][unit]">
                        <option value="">Unit</option>
                        <option value="pcs" {{ ($item['unit'] ?? '') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="kg" {{ ($item['unit'] ?? '') == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="l" {{ ($item['unit'] ?? '') == 'l' ? 'selected' : '' }}>L</option>
                        <option value="m" {{ ($item['unit'] ?? '') == 'm' ? 'selected' : '' }}>M</option>
                        <option value="box" {{ ($item['unit'] ?? '') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="set" {{ ($item['unit'] ?? '') == 'set' ? 'selected' : '' }}>Set</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control form-control-sm edit-po-price" 
                             data-po="{{ $po->id }}"
                             name="items[{{ $index }}][price]" 
                             value="{{ $item['price'] ?? 0 }}" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-1">
                      <button type="button" class="btn btn-sm btn-danger remove-edit-po-item" 
                              data-po="{{ $po->id }}">
                        <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                      </button>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              
              <button type="button" class="btn btn-sm bg-gradient-success mt-2 add-edit-po-item" 
                      data-po="{{ $po->id }}">
                <i class="material-symbols-rounded" style="font-size: 16px;">add</i> Add Item
              </button>
              
              <!-- Totals for Edit -->
              <div class="row g-2 mt-3">
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label class="form-control-label">Subtotal</label>
                    <input type="text" class="form-control form-control-sm bg-light" 
                           id="edit-po-subtotal-{{ $po->id }}" value="{{ number_format($po->subtotal, 2) }}" readonly>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label class="form-control-label">Total Amount</label>
                    <input type="text" class="form-control form-control-sm bg-light" 
                           id="edit-po-total-amount-{{ $po->id }}" 
                           name="total_amount" value="{{ number_format($po->total_amount, 2) }}" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-warning btn-sm">Update Purchase Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
    
    <!-- Approve PO Modal {{ $po->id }} -->
    @if($po->can_approve)
    <div class="modal fade" id="approvePOModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Approve Purchase Order #{{ $po->po_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('company.purchase-order.status.update', $po) }}" method="POST">
            @csrf
            <div class="modal-body">
              <p>Are you sure you want to approve this purchase order?</p>
              <div class="alert alert-info">
                <p class="mb-1"><strong>Supplier:</strong> {{ $po->supplier_name }}</p>
                <p class="mb-1"><strong>Total Amount:</strong> Tsh{{ number_format($po->total_amount, 2) }}</p>
                <p class="mb-0"><strong>Payment Terms:</strong> {{ $po->payment_terms_text }}</p>
              </div>
              <input type="hidden" name="status" value="approved">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success btn-sm">Approve Purchase Order</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
    
    <!-- Delete PO Modal {{ $po->id }} -->
    @if($po->can_edit)
    <div class="modal fade" id="deletePOModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Delete Purchase Order #{{ $po->po_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to delete this purchase order?</p>
            <div class="alert alert-warning">
              <p class="mb-1"><strong>PO #:</strong> {{ $po->po_number }}</p>
              <p class="mb-1"><strong>Supplier:</strong> {{ $po->supplier_name }}</p>
              <p class="mb-0"><strong>Total Amount:</strong> Tsh{{ number_format($po->total_amount, 2) }}</p>
            </div>
            <p class="text-danger"><small>This action cannot be undone.</small></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <form action="{{ route('company.purchase-order.destroy', $po) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Delete Purchase Order</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    @endif
  @endforeach

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Search functionality
      const searchInput = document.getElementById('search-po');
      if (searchInput) {
        searchInput.addEventListener('input', function(e) {
          const searchTerm = e.target.value.toLowerCase();
          const rows = document.querySelectorAll('#po-table tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
          });
        });
      }
      
      // ============================================
      // CREATE MODAL FUNCTIONALITY
      // ============================================
      
      // PO Items Management for Create Modal
      let poItemCounter = 1;
      const addPoItemBtn = document.getElementById('add-po-item');
      const poItemsContainer = document.getElementById('po-items-container');
      
      if (addPoItemBtn && poItemsContainer) {
        // Add item row
        addPoItemBtn.addEventListener('click', function() {
          const newRow = document.createElement('div');
          newRow.className = 'item-row mb-3 p-2 border rounded';
          newRow.innerHTML = `
            <div class="row g-2">
              <div class="col-md-5">
                <input type="text" class="form-control form-control-sm" name="items[${poItemCounter}][description]" placeholder="Description" required>
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control form-control-sm po-quantity" name="items[${poItemCounter}][quantity]" 
                       min="1" value="1" required>
              </div>
              <div class="col-md-2">
                <select class="form-control form-control-sm" name="items[${poItemCounter}][unit]">
                  <option value="">Unit</option>
                  <option value="pcs">Pcs</option>
                  <option value="kg">Kg</option>
                  <option value="l">L</option>
                  <option value="m">M</option>
                  <option value="box">Box</option>
                  <option value="set">Set</option>
                </select>
              </div>
              <div class="col-md-2">
                <input type="number" class="form-control form-control-sm po-price" name="items[${poItemCounter}][price]" 
                       step="0.01" min="0" required>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger remove-po-item w-100">
                  <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                </button>
              </div>
            </div>
          `;
          poItemsContainer.appendChild(newRow);
          poItemCounter++;
        });
      }
      
      // Remove item and calculate totals for Create Modal
      document.addEventListener('click', function(e) {
        // Remove PO item in Create Modal
        if (e.target.closest('.remove-po-item')) {
          const row = e.target.closest('.item-row');
          if (document.querySelectorAll('#po-items-container .item-row').length > 1) {
            row.remove();
            calculatePOTotals();
          }
        }
        
        // Remove item in Edit Modals
        if (e.target.closest('.remove-edit-po-item')) {
          const row = e.target.closest('.item-row');
          const poId = e.target.closest('.remove-edit-po-item').getAttribute('data-po');
          const container = document.getElementById(`edit-po-items-container-${poId}`);
          
          if (container && container.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calculateEditPOTotals(poId);
          }
        }
        
        // Add item in Edit Modals
        if (e.target.closest('.add-edit-po-item')) {
          const poId = e.target.closest('.add-edit-po-item').getAttribute('data-po');
          addEditPOItemRow(poId);
        }
      });
      
      // Calculate PO totals for Create Modal
      function calculatePOTotals() {
        let subtotal = 0;
        
        // Calculate from items
        document.querySelectorAll('#po-items-container .item-row').forEach(row => {
          const quantity = parseFloat(row.querySelector('.po-quantity')?.value) || 0;
          const price = parseFloat(row.querySelector('.po-price')?.value) || 0;
          subtotal += quantity * price;
        });
        
        const taxRate = parseFloat(document.getElementById('tax_rate')?.value) || 0;
        const discount = parseFloat(document.getElementById('discount')?.value) || 0;
        const shippingCost = parseFloat(document.getElementById('shipping_cost')?.value) || 0;
        
        const taxAmount = (subtotal * taxRate) / 100;
        const total = subtotal + taxAmount - discount + shippingCost;
        
        // Update display
        const subtotalInput = document.getElementById('po-subtotal');
        const taxAmountInput = document.getElementById('po-tax-amount');
        const totalInput = document.getElementById('po-total-amount');
        
        if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
        if (taxAmountInput) taxAmountInput.value = taxAmount.toFixed(2);
        if (totalInput) totalInput.value = total.toFixed(2);
      }
      
      // Add event listeners for inputs that affect totals in Create Modal
      document.addEventListener('input', function(e) {
        if (e.target.classList.contains('po-quantity') || 
            e.target.classList.contains('po-price') ||
            e.target.id === 'tax_rate' ||
            e.target.id === 'discount' ||
            e.target.id === 'shipping_cost') {
          calculatePOTotals();
        }
        
        // Calculate totals for Edit Modals
        if (e.target.classList.contains('edit-po-quantity') || 
            e.target.classList.contains('edit-po-price')) {
          const poId = e.target.getAttribute('data-po');
          if (poId) {
            calculateEditPOTotals(poId);
          }
        }
      });
      
      // Generate PO number on modal show
      const createModal = document.getElementById('createPurchaseOrderModal');
      if (createModal) {
        createModal.addEventListener('show.bs.modal', function() {
          // Reset form
          const form = document.getElementById('createPOForm');
          if (form) form.reset();
          
          // Reset items container
          poItemsContainer.innerHTML = `
            <div class="item-row mb-3 p-2 border rounded">
              <div class="row g-2">
                <div class="col-md-5">
                  <input type="text" class="form-control form-control-sm" name="items[0][description]" placeholder="Description" required>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm po-quantity" name="items[0][quantity]" 
                         placeholder="Qty" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                  <select class="form-control form-control-sm" name="items[0][unit]">
                    <option value="">Unit</option>
                    <option value="pcs">Pcs</option>
                    <option value="kg">Kg</option>
                    <option value="l">L</option>
                    <option value="m">M</option>
                    <option value="box">Box</option>
                    <option value="set">Set</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control form-control-sm po-price" name="items[0][price]" 
                         placeholder="Price" step="0.01" min="0" required>
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-sm btn-danger remove-po-item w-100">
                    <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
                  </button>
                </div>
              </div>
            </div>
          `;
          
          poItemCounter = 1;
          
          // Generate PO number
          generatePONumber();
          
          // Reset totals
          document.getElementById('po-subtotal').value = '0.00';
          document.getElementById('po-tax-amount').value = '0.00';
          document.getElementById('po-total-amount').value = '0.00';
        });
      }
      
      // Initialize totals for Create Modal
      calculatePOTotals();
      
      // ============================================
      // EDIT MODAL FUNCTIONALITY
      // ============================================
      
      function addEditPOItemRow(poId) {
        const container = document.getElementById(`edit-po-items-container-${poId}`);
        if (!container) return;
        
        const rows = container.querySelectorAll('.item-row');
        const itemCount = rows.length;
        
        const newRow = document.createElement('div');
        newRow.className = 'item-row mb-3 p-2 border rounded';
        newRow.innerHTML = `
          <div class="row g-2">
            <div class="col-md-5">
              <input type="text" class="form-control form-control-sm edit-po-item-desc" 
                     name="items[${itemCount}][description]" placeholder="Description" required>
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control form-control-sm edit-po-quantity" 
                     data-po="${poId}"
                     name="items[${itemCount}][quantity]" min="1" value="1" required>
            </div>
            <div class="col-md-2">
              <select class="form-control form-control-sm" name="items[${itemCount}][unit]">
                <option value="">Unit</option>
                <option value="pcs">Pcs</option>
                <option value="kg">Kg</option>
                <option value="l">L</option>
                <option value="m">M</option>
                <option value="box">Box</option>
                <option value="set">Set</option>
              </select>
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control form-control-sm edit-po-price" 
                     data-po="${poId}"
                     name="items[${itemCount}][price]" step="0.01" min="0" required>
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-sm btn-danger remove-edit-po-item" 
                      data-po="${poId}">
                <i class="material-symbols-rounded" style="font-size: 16px;">delete</i>
              </button>
            </div>
          </div>
        `;
        container.appendChild(newRow);
      }
      
      function calculateEditPOTotals(poId) {
        const container = document.getElementById(`edit-po-items-container-${poId}`);
        if (!container) return;
        
        let subtotal = 0;
        container.querySelectorAll('.item-row').forEach(row => {
          const quantity = parseFloat(row.querySelector('.edit-po-quantity')?.value) || 0;
          const price = parseFloat(row.querySelector('.edit-po-price')?.value) || 0;
          subtotal += quantity * price;
        });
        
        const subtotalInput = document.getElementById(`edit-po-subtotal-${poId}`);
        const totalInput = document.getElementById(`edit-po-total-amount-${poId}`);
        
        if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
        if (totalInput) totalInput.value = subtotal.toFixed(2); // Simple total for edit
      }
      
      // Initialize edit modal totals for each PO
      @foreach($purchaseOrders as $po)
        @if($po->can_edit)
          calculateEditPOTotals({{ $po->id }});
        @endif
      @endforeach
      
      // ============================================
      // HELPER FUNCTIONS
      // ============================================
      
      // Generate PO Number Function
      function generatePONumber() {
        // Try AJAX first, then fallback
        fetch('{{ route("company.purchase-order.generate") }}')
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            document.getElementById('po_number').value = data.po_number || data;
          })
          .catch(error => {
            console.log('AJAX failed, using local generation:', error);
            // Fallback local generation
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            const poNumber = `PO-${year}${month}${day}-${random}`;
            
            document.getElementById('po_number').value = poNumber;
          });
      }
      
      // Toast notifications
      @if(session('success'))
      showToast('{{ session('success') }}', 'success');
      @endif
      
      @if(session('error'))
      showToast('{{ session('error') }}', 'danger');
      @endif
    });
    
    function showToast(message, type = 'info') {
      const toast = document.createElement('div');
      toast.className = `toast align-items-center text-white bg-${type}`;
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