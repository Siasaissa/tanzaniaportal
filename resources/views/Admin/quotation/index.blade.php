<!DOCTYPE html>
<html lang="en">
@include('layouts.adminhead')

<body class="g-sidenav-show bg-gray-100">
  @include('layouts.adminnavbar')
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    @include('layouts.topnav')
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h3 class="mb-0 h4 font-weight-bolder">Quotations Management</h3>
              <p class="mb-0 text-sm">Manage all your quotations</p>
            </div>
          </div>
          
        </div>
        
        <!-- Stats Cards -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Quotations</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $totalQuotations }}
                      @if($quotations->where('date', '>=', now()->startOfMonth())->count() > 0)
                      <span class="text-success text-sm font-weight-bolder">+{{ $quotations->where('date', '>=', now()->startOfMonth())->count() }}</span>
                      @endif
                    </h5>
                  </div>
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
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This Month</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $quotations->where('date', '>=', now()->startOfMonth())->count() }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">calendar_month</i>
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
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Value</p>
                    <h5 class="font-weight-bolder mb-0">
                      Tsh{{ number_format($quotations->sum('total'), 2) }}
                    </h5>
                  </div>
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
        
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Average Value</p>
                    <h5 class="font-weight-bolder mb-0">
                      Tsh{{ $totalQuotations > 0 ? number_format($quotations->sum('total') / $totalQuotations, 2) : '0.00' }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">bar_chart</i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quotations Table -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6>Quotations List</h6>
                  <p class="text-sm mb-0">
                    <i class="material-symbols-rounded text-info" style="font-size: 14px;">check_circle</i>
                    <span class="font-weight-bold ms-1">{{ $totalQuotations }} quotations</span> in total
                  </p>
                </div>
                <div class="input-group" style="width: 250px;">
                  <span class="input-group-text text-body">
                    <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                  </span>
                  <input type="text" class="form-control" placeholder="Search quotations..." id="search-quotations">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0" id="quotations-table">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quotation #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($quotations as $quotation)
                    <tr>
                      <td>
                        <div class="d-flex px-3 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $quotation->quotation_number }}</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $quotation->client_name }}</h6>
                            @if($quotation->client_email)
                            <p class="text-xs text-secondary mb-0">{{ $quotation->client_email }}</p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold">{{ $quotation->date->format('M d, Y') }}</span>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold text-success">Tsh{{ number_format($quotation->total, 2) }}</span>
                      </td>
                      <td class="align-middle">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#viewQuotationModal{{ $quotation->id }}" 
                                  title="View">
                            <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                          </button>
                          <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#editQuotationModal{{ $quotation->id }}" 
                                  title="Edit">
                            <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                          </button>
                          <a href="{{ route('admin.quotation.download', $quotation) }}" 
                             class="btn btn-link text-success px-2 mb-0" 
                             title="Download PDF">
                            <i class="material-symbols-rounded" style="font-size: 18px;">download</i>
                          </a>
                          <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#deleteQuotationModal{{ $quotation->id }}" 
                                  title="Delete">
                            <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="5" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">receipt</i>
                          <h6 class="text-secondary">No quotations found</h6>
                          <p class="text-sm text-secondary">Create your first quotation by clicking the button above</p>
                        </div>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div class="p-3">
                {{ $quotations->links('pagination::bootstrap-5') }}
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @include('layouts.footer')
    </div>
  </main>
  
  <!-- Create Quotation Modal -->
  <div class="modal fade" id="createQuotationModal" tabindex="-1" aria-labelledby="createQuotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createQuotationModalLabel">Create New Quotation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.quotation.store') }}" method="POST" id="createQuotationForm">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="quotation_number" class="form-control-label">Quotation Number</label>
                  <input type="text" class="form-control" value="{{ \App\Models\Quotation::generateQuotationNumber(Auth::user()->id) }}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date" class="form-control-label">Date *</label>
                  <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="client_name" class="form-control-label">Client Name *</label>
                  <input type="text" class="form-control" name="client_name" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="client_email" class="form-control-label">Client Email</label>
                  <input type="email" class="form-control" name="client_email">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="client_phone" class="form-control-label">Client Phone</label>
                  <input type="text" class="form-control" name="client_phone">
                </div>
              </div>
            </div>
            
            <!-- Items Section -->
            <div class="row mt-4">
              <div class="col-12">
                <h6>Items *</h6>
                <div id="items-container">
                  <div class="item-row row mb-2">
                    <div class="col-md-5">
                      <input type="text" class="form-control" name="items[0][description]" placeholder="Description" required>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control quantity" name="items[0][quantity]" placeholder="Qty" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                      <input type="number" class="form-control price" name="items[0][price]" placeholder="Price" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-danger remove-item">
                        <i class="material-symbols-rounded">delete</i>
                      </button>
                    </div>
                  </div>
                </div>
                
                <button type="button" class="btn btn-sm bg-gradient-success mt-2" id="add-item">
                  <i class="material-symbols-rounded">add</i> Add Item
                </button>
                
                <div class="row mt-3">
                  <div class="col-md-6 offset-md-6">
                    <div class="form-group">
                      <label for="total" class="form-control-label">Total Amount</label>
                      <input type="number" class="form-control" id="total" name="total" step="0.01" min="0" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-12">
                <div class="form-group">
                  <label for="notes" class="form-control-label">Notes</label>
                  <textarea class="form-control" name="notes" rows="3"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-success">Create Quotation</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- View Quotation Modals -->
  @foreach($quotations as $quotation)
  <div class="modal fade" id="viewQuotationModal{{ $quotation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">View Quotation #{{ $quotation->quotation_number }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Company Information</h6>
              <p class="mb-1"><strong>{{ $quotation->company->name ?? 'Admin' }}</strong></p>
            </div>
            <div class="col-md-6 text-end">
              <h6>Quotation Details</h6>
              <p class="mb-1"><strong>Quotation #:</strong> {{ $quotation->quotation_number }}</p>
              <p class="mb-1"><strong>Date:</strong> {{ $quotation->date->format('F d, Y') }}</p>
            </div>
          </div>
          
          <hr class="my-3">
          
          <div class="row">
            <div class="col-md-6">
              <h6>Client Information</h6>
              <p class="mb-1"><strong>{{ $quotation->client_name }}</strong></p>
              @if($quotation->client_email)
              <p class="mb-1">Email: {{ $quotation->client_email }}</p>
              @endif
              @if($quotation->client_phone)
              <p class="mb-0">Phone: {{ $quotation->client_phone }}</p>
              @endif
            </div>
          </div>
          
          <div class="row mt-4">
            <div class="col-12">
              <h6>Items</h6>
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
                    @php
                      $items = is_string($quotation->items) ? json_decode($quotation->items, true) : $quotation->items;
                    @endphp
                    @if($items)
                      @foreach($items as $item)
                      <tr>
                        <td>{{ $item['description'] ?? '' }}</td>
                        <td>{{ $item['quantity'] ?? 0 }}</td>
                        <td>Tsh{{ number_format($item['price'] ?? 0, 2) }}</td>
                        <td>Tsh{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                      </tr>
                      @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="text-end"><strong>Total:</strong></td>
                      <td><strong>Tsh{{ number_format($quotation->total, 2) }}</strong></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          
          @if($quotation->notes)
          <div class="row mt-4">
            <div class="col-12">
              <h6>Notes</h6>
              <div class="card bg-light p-3">
                {{ $quotation->notes }}
              </div>
            </div>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="{{ route('admin.quotation.print', $quotation) }}" 
             class="btn btn-primary" target="_blank">
            <i class="material-symbols-rounded">print</i> Print
          </a>
          <a href="{{ route('admin.quotation.download', $quotation->id) }}" 
             class="btn btn-success">
            <i class="material-symbols-rounded">download</i> Download PDF
          </a>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  <!-- Edit Quotation Modals -->
  @foreach($quotations as $quotation)
  <div class="modal fade" id="editQuotationModal{{ $quotation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Quotation #{{ $quotation->quotation_number }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.quotation.update', $quotation) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Quotation Number</label>
                <input type="text" class="form-control" value="{{ $quotation->quotation_number }}" readonly>
              </div>
              <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" class="form-control" name="date" 
                       value="{{ $quotation->date->format('Y-m-d') }}" required>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="client_name" class="form-label">Client Name *</label>
                <input type="text" class="form-control" name="client_name" 
                       value="{{ $quotation->client_name }}" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="client_email" class="form-label">Client Email</label>
                <input type="email" class="form-control" name="client_email" 
                       value="{{ $quotation->client_email }}">
              </div>
              <div class="col-md-4 mb-3">
                <label for="client_phone" class="form-label">Client Phone</label>
                <input type="text" class="form-control" name="client_phone" 
                       value="{{ $quotation->client_phone }}">
              </div>
            </div>
            
            <!-- Items Section -->
            <div class="mb-3">
              <label class="form-label">Items *</label>
              <div id="edit-items-container-{{ $quotation->id }}">
                @php
                  $items = is_string($quotation->items) ? json_decode($quotation->items, true) : $quotation->items;
                  $itemCount = 0;
                @endphp
                @if($items)
                  @foreach($items as $index => $item)
                  <div class="row mb-2">
                    <div class="col-md-5">
                      <input type="text" class="form-control" 
                             name="items[{{ $index }}][description]" 
                             value="{{ $item['description'] ?? '' }}" required>
                    </div>
                    <div class="col-md-2">
                      <input type="number" class="form-control edit-quantity" data-quotation="{{ $quotation->id }}"
                             name="items[{{ $index }}][quantity]" 
                             value="{{ $item['quantity'] ?? 1 }}" min="1" required>
                    </div>
                    <div class="col-md-3">
                      <input type="number" class="form-control edit-price" data-quotation="{{ $quotation->id }}"
                             name="items[{{ $index }}][price]" 
                             value="{{ $item['price'] ?? 0 }}" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-quotation="{{ $quotation->id }}">
                        <i class="material-symbols-rounded">delete</i>
                      </button>
                    </div>
                  </div>
                  @php $itemCount++; @endphp
                  @endforeach
                @endif
              </div>
              
              <button type="button" class="btn btn-sm btn-success mt-2 add-edit-item" 
                      data-quotation="{{ $quotation->id }}">
                <i class="material-symbols-rounded">add</i> Add Item
              </button>
              
              <div class="row mt-3">
                <div class="col-md-6 offset-md-6">
                  <label for="total" class="form-label">Total Amount</label>
                  <input type="number" class="form-control edit-total" id="edit-total-{{ $quotation->id }}"
                         name="total" value="{{ number_format($quotation->total, 2) }}" 
                         step="0.01" min="0" readonly>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="notes" class="form-label">Notes</label>
              <textarea class="form-control" name="notes" rows="3">{{ $quotation->notes }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">Update Quotation</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endforeach

  <!-- Delete Confirmation Modals -->
  @foreach($quotations as $quotation)
  <div class="modal fade" id="deleteQuotationModal{{ $quotation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this quotation?</p>
          <p><strong>Quotation #:</strong> {{ $quotation->quotation_number }}</p>
          <p><strong>Client:</strong> {{ $quotation->client_name }}</p>
          <p><strong>Total:</strong> Tsh{{ number_format($quotation->total, 2) }}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form method="POST" action="{{ route('admin.quotation.destroy', $quotation) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize item counters
      let itemCounts = {};
      
      // Search functionality
      const searchInput = document.getElementById('search-quotations');
      if (searchInput) {
        searchInput.addEventListener('input', function(e) {
          const searchTerm = e.target.value.toLowerCase();
          const rows = document.querySelectorAll('#quotations-table tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
          });
        });
      }
      
      // CREATE MODAL - Item Management
      const createItemsContainer = document.getElementById('items-container');
      const addItemBtn = document.getElementById('add-item');
      let createItemCounter = 1;
      
      if (addItemBtn && createItemsContainer) {
        // Add item row
        addItemBtn.addEventListener('click', function() {
          const newRow = document.createElement('div');
          newRow.className = 'item-row row mb-2';
          newRow.innerHTML = `
            <div class="col-md-5">
              <input type="text" class="form-control" name="items[${createItemCounter}][description]" placeholder="Description" required>
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control quantity" name="items[${createItemCounter}][quantity]" min="1" value="1" required>
            </div>
            <div class="col-md-3">
              <input type="number" class="form-control price" name="items[${createItemCounter}][price]" step="0.01" min="0" required>
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-sm btn-danger remove-item">
                <i class="material-symbols-rounded">delete</i>
              </button>
            </div>
          `;
          createItemsContainer.appendChild(newRow);
          createItemCounter++;
        });
        
        // Calculate total for create modal
        function calculateCreateTotal() {
          let total = 0;
          document.querySelectorAll('#items-container .item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity')?.value) || 0;
            const price = parseFloat(row.querySelector('.price')?.value) || 0;
            total += quantity * price;
          });
          const totalInput = document.getElementById('total');
          if (totalInput) {
            totalInput.value = total.toFixed(2);
          }
        }
        
        // Event delegation for remove items and calculate total
        document.addEventListener('click', function(e) {
          // Remove item in create modal
          if (e.target.closest('.remove-item')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('#items-container .item-row').length > 1) {
              row.remove();
              calculateCreateTotal();
            }
          }
          
          // Remove item in edit modals
          if (e.target.closest('.remove-edit-item')) {
            const row = e.target.closest('.row');
            const quotationId = e.target.closest('.remove-edit-item').getAttribute('data-quotation');
            const container = document.getElementById(`edit-items-container-${quotationId}`);
            
            if (container && container.querySelectorAll('.row').length > 1) {
              row.remove();
              calculateEditTotal(quotationId);
            }
          }
          
          // Add item in edit modals
          if (e.target.closest('.add-edit-item')) {
            const quotationId = e.target.closest('.add-edit-item').getAttribute('data-quotation');
            addEditItemRow(quotationId);
          }
        });
        
        document.addEventListener('input', function(e) {
          // Calculate total for create modal
          if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            calculateCreateTotal();
          }
          
          // Calculate total for edit modals
          if (e.target.classList.contains('edit-quantity') || e.target.classList.contains('edit-price')) {
            const quotationId = e.target.getAttribute('data-quotation');
            if (quotationId) {
              calculateEditTotal(quotationId);
            }
          }
        });
        
        // Initialize create modal total
        calculateCreateTotal();
      }
      
      // EDIT MODAL - Item Management
      function addEditItemRow(quotationId) {
        const container = document.getElementById(`edit-items-container-${quotationId}`);
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
            <input type="number" class="form-control edit-quantity" data-quotation="${quotationId}"
                   name="items[${itemCount}][quantity]" min="1" value="1" required>
          </div>
          <div class="col-md-3">
            <input type="number" class="form-control edit-price" data-quotation="${quotationId}"
                   name="items[${itemCount}][price]" step="0.01" min="0" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-quotation="${quotationId}">
              <i class="material-symbols-rounded">delete</i>
            </button>
          </div>
        `;
        container.appendChild(newRow);
      }
      
      function calculateEditTotal(quotationId) {
        const container = document.getElementById(`edit-items-container-${quotationId}`);
        if (!container) return;
        
        let total = 0;
        container.querySelectorAll('.row').forEach(row => {
          const quantity = parseFloat(row.querySelector('.edit-quantity')?.value) || 0;
          const price = parseFloat(row.querySelector('.edit-price')?.value) || 0;
          total += quantity * price;
        });
        
        const totalInput = document.getElementById(`edit-total-${quotationId}`);
        if (totalInput) {
          totalInput.value = total.toFixed(2);
        }
      }
      
      // Initialize edit modal totals
      @foreach($quotations as $quotation)
      calculateEditTotal({{ $quotation->id }});
      @endforeach
      
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