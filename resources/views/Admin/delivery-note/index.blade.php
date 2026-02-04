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
              <h3 class="mb-0 h4 font-weight-bolder">Delivery Notes Management</h3>
              <p class="mb-0 text-sm">Manage all your delivery notes</p>
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
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Delivery Notes</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $totalDeliveryNotes }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">local_shipping</i>
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
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $deliveryNotes->where('status', 'pending')->count() }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">pending_actions</i>
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
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Dispatched</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $deliveryNotes->where('status', 'dispatched')->count() }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                    <i class="material-symbols-rounded opacity-10">directions_car</i>
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
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Delivered</p>
                    <h5 class="font-weight-bolder mb-0">
                      {{ $deliveryNotes->where('status', 'delivered')->count() }}
                    </h5>
                  </div>
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
      </div>

      <!-- Delivery Notes Table -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6>Delivery Notes List</h6>
                  <p class="text-sm mb-0">
                    <i class="material-symbols-rounded text-info" style="font-size: 14px;">local_shipping</i>
                    <span class="font-weight-bold ms-1">{{ $totalDeliveryNotes }} delivery notes</span> in total
                  </p>
                </div>
                <div class="input-group" style="width: 250px;">
                  <span class="input-group-text text-body">
                    <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                  </span>
                  <input type="text" class="form-control" placeholder="Search delivery notes..." id="search-delivery-notes">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0" id="delivery-notes-table">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Note #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quotation #</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($deliveryNotes as $note)
                    <tr>
                      <td>
                        <div class="d-flex px-3 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ $note->delivery_note_number }}</h6>
                            <p class="text-xs text-secondary mb-0">
                              <i class="material-symbols-rounded" style="font-size: 12px;">calendar_month</i>
                              {{ $note->created_at->format('M d, Y') }}
                            </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $note->quotation->quotation_number ?? 'N/A' }}</h6>
                            @if($note->quotation)
                            <p class="text-xs text-secondary mb-0">
                              Total: Tsh{{ number_format($note->quotation->total, 2) }}
                            </p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex px-2">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ $note->quotation->client_name ?? 'N/A' }}</h6>
                            @if($note->quotation && $note->quotation->client_email)
                            <p class="text-xs text-secondary mb-0">{{ $note->quotation->client_email }}</p>
                            @endif
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <span class="text-xs font-weight-bold">{{ $note->delivery_date->format('M d, Y') }}</span>
                      </td>
                      <td class="align-middle">
                        <span class="badge badge-sm bg-gradient-{{ $note->status_color }}">
                          <i class="material-symbols-rounded" style="font-size: 12px; vertical-align: middle;">
                            {{ $note->status_icon }}
                          </i>
                          {{ ucfirst($note->status) }}
                        </span>
                      </td>
                      <td class="align-middle">
                        <div class="btn-group" role="group">
                          <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#viewDeliveryNoteModal{{ $note->id }}" 
                                  title="View">
                            <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                          </button>
                          <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#editDeliveryNoteModal{{ $note->id }}" 
                                  title="Edit">
                            <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                          </button>
                          <a href="{{ route('admin.delivery-note.download', $note) }}" 
                             class="btn btn-link text-success px-2 mb-0" 
                             title="Download PDF">
                            <i class="material-symbols-rounded" style="font-size: 18px;">download</i>
                          </a>
                          <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                  data-bs-toggle="modal" data-bs-target="#deleteDeliveryNoteModal{{ $note->id }}" 
                                  title="Delete">
                            <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                          </button>
                        </div>
                      </td>
                    </tr>
                    
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editDeliveryNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Delivery Note #{{ $note->delivery_note_number }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form action="{{ route('admin.delivery-note.update', $note) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-6 mb-3">
                                  <label class="form-label">Delivery Note Number</label>
                                  <input type="text" class="form-control" value="{{ $note->delivery_note_number }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                  <label class="form-label">Quotation Number</label>
                                  <input type="text" class="form-control" value="{{ $note->quotation->quotation_number ?? 'N/A' }}" readonly>
                                </div>
                              </div>
                              
                              <div class="row">
                                <div class="col-md-6 mb-3">
                                  <label for="delivery_date" class="form-label">Delivery Date *</label>
                                  <input type="date" class="form-control" name="delivery_date" 
                                         value="{{ $note->delivery_date->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                  <label for="dispatch_date" class="form-label">Dispatch Date</label>
                                  <input type="date" class="form-control" name="dispatch_date" 
                                         value="{{ $note->dispatch_date ? $note->dispatch_date->format('Y-m-d') : '' }}">
                                </div>
                              </div>
                              
                              <div class="row">
                                <div class="col-md-4 mb-3">
                                  <label for="status" class="form-label">Status *</label>
                                  <select class="form-control" name="status" required>
                                    <option value="pending" {{ $note->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="dispatched" {{ $note->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                                    <option value="delivered" {{ $note->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $note->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                  </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                  <label for="vehicle_number" class="form-label">Vehicle Number</label>
                                  <input type="text" class="form-control" name="vehicle_number" 
                                         value="{{ $note->vehicle_number }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                  <label for="driver_name" class="form-label">Driver Name</label>
                                  <input type="text" class="form-control" name="driver_name" 
                                         value="{{ $note->driver_name }}">
                                </div>
                              </div>
                              
                              <div class="row">
                                <div class="col-md-6 mb-3">
                                  <label for="delivery_contact_person" class="form-label">Contact Person</label>
                                  <input type="text" class="form-control" name="delivery_contact_person" 
                                         value="{{ $note->delivery_contact_person }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                  <label for="delivery_contact_phone" class="form-label">Contact Phone</label>
                                  <input type="text" class="form-control" name="delivery_contact_phone" 
                                         value="{{ $note->delivery_contact_phone }}">
                                </div>
                              </div>
                              
                              <div class="mb-3">
                                <label for="delivery_address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" name="delivery_address" rows="3">{{ $note->delivery_address }}</textarea>
                              </div>
                              
                              <!-- Items Section -->
                              <div class="mb-3">
                                <label class="form-label">Items *</label>
                                <div id="edit-items-container-{{ $note->id }}">
                                  @foreach($note->formatted_items as $index => $item)
                                  <div class="row mb-2">
                                    <div class="col-md-5">
                                      <input type="text" class="form-control" 
                                             name="items[{{ $index }}][description]" 
                                             value="{{ $item['description'] ?? '' }}" required>
                                    </div>
                                    <div class="col-md-2">
                                      <input type="number" class="form-control edit-quantity" data-note="{{ $note->id }}"
                                             name="items[{{ $index }}][quantity]" 
                                             value="{{ $item['quantity'] ?? 1 }}" min="0" required>
                                    </div>
                                    <div class="col-md-3">
                                      <input type="number" class="form-control edit-price" data-note="{{ $note->id }}"
                                             name="items[{{ $index }}][price]" 
                                             value="{{ $item['price'] ?? 0 }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                      <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-note="{{ $note->id }}">
                                        <i class="material-symbols-rounded">delete</i>
                                      </button>
                                    </div>
                                  </div>
                                  @endforeach
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-success mt-2 add-edit-item" 
                                        data-note="{{ $note->id }}">
                                  <i class="material-symbols-rounded">add</i> Add Item
                                </button>
                                
                                <div class="row mt-3">
                                  <div class="col-md-6 offset-md-6">
                                    <label for="total" class="form-label">Total Amount</label>
                                    <input type="number" class="form-control edit-total" id="edit-total-{{ $note->id }}"
                                           name="total"
                                           value="{{ number_format($note->total, 2, '.', '') }}" 
                                           step="0.01" min="0" readonly>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="mb-3">
                                <label for="delivery_notes" class="form-label">Delivery Notes</label>
                                <textarea class="form-control" name="delivery_notes" rows="3">{{ $note->delivery_notes }}</textarea>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-warning">Update Delivery Note</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteDeliveryNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <p>Are you sure you want to delete this delivery note?</p>
                            <p><strong>Delivery Note #:</strong> {{ $note->delivery_note_number }}</p>
                            <p><strong>Quotation #:</strong> {{ $note->quotation->quotation_number ?? 'N/A' }}</p>
                            <p><strong>Client:</strong> {{ $note->quotation->client_name ?? 'N/A' }}</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('company.delivery-note.destroy', $note) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    @empty
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">local_shipping</i>
                          <h6 class="text-secondary">No delivery notes found</h6>
                          <p class="text-sm text-secondary">Create your first delivery note by clicking the button above</p>
                        </div>
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <!-- Pagination -->
              <div class="p-3">
                {{ $deliveryNotes->links('pagination::bootstrap-5') }}
              </div>
            </div>
          </div>
        </div>
      </div>
      
      @include('layouts.footer')
    </div>
  </main>
  
  <!-- Create Delivery Note Modal -->
  <div class="modal fade" id="createDeliveryNoteModal" tabindex="-1" aria-labelledby="createDeliveryNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createDeliveryNoteModalLabel">Create New Delivery Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.delivery-note.store') }}" method="POST" id="createDeliveryNoteForm">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="delivery_note_number" class="form-control-label">Delivery Note Number</label>
                  <input type="text" class="form-control" id="delivery_note_number" name="delivery_note_number" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="quotation_id" class="form-control-label">Select Quotation *</label>
                  <select class="form-control" name="quotation_id" id="quotation_id" required>
                    <option value="">-- Select Quotation --</option>
                    @foreach($availableQuotations as $quotation)
                    <option value="{{ $quotation->id }}" 
                            data-client-name="{{ $quotation->client_name }}"
                            data-client-email="{{ $quotation->client_email }}"
                            data-client-phone="{{ $quotation->client_phone }}"
                            data-items="{{ json_encode($quotation->formatted_items) }}"
                            data-total="{{ $quotation->total }}">
                      {{ $quotation->quotation_number }} - {{ $quotation->client_name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="delivery_date" class="form-control-label">Delivery Date *</label>
                  <input type="date" class="form-control" name="delivery_date" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="dispatch_date" class="form-control-label">Dispatch Date</label>
                  <input type="date" class="form-control" name="dispatch_date">
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="delivery_address" class="form-control-label">Delivery Address</label>
                  <textarea class="form-control" name="delivery_address" id="delivery_address" rows="3" 
                            placeholder="Enter delivery address..."></textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="delivery_contact_person" class="form-control-label">Contact Person</label>
                      <input type="text" class="form-control" name="delivery_contact_person" id="delivery_contact_person">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="delivery_contact_phone" class="form-control-label">Contact Phone</label>
                      <input type="text" class="form-control" name="delivery_contact_phone" id="delivery_contact_phone">
                    </div>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="vehicle_number" class="form-control-label">Vehicle Number</label>
                      <input type="text" class="form-control" name="vehicle_number">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="driver_name" class="form-control-label">Driver Name</label>
                      <input type="text" class="form-control" name="driver_name">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row mt-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="driver_contact" class="form-control-label">Driver Contact</label>
                  <input type="text" class="form-control" name="driver_contact">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="status" class="form-control-label">Status</label>
                  <select class="form-control" name="status">
                    <option value="pending">Pending</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Items Section -->
            <div class="row mt-4">
              <div class="col-12">
                <h6>Delivery Items *</h6>
                <div id="items-container">
                  <!-- Items will be populated from quotation selection -->
                </div>
                
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
                  <label for="delivery_notes" class="form-control-label">Delivery Notes</label>
                  <textarea class="form-control" name="delivery_notes" rows="3" 
                            placeholder="Any special instructions for delivery..."></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn bg-gradient-success">Create Delivery Note</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Modal -->
   @foreach ( $deliveryNotes as $note)

    <div class="modal fade" id="viewDeliveryNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">View Delivery Note #{{ $note->delivery_note_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                <h6>Company Information</h6>
                <p class="mb-1"><strong>{{ $note->company->name ?? 'Admin'}}</strong></p>
                
                </div>
                <div class="col-md-6 text-end">
                <h6>Delivery Note Details</h6>
                <p class="mb-1"><strong>Delivery Note #:</strong> {{ $note->delivery_note_number }}</p>
                <p class="mb-1"><strong>Quotation #:</strong> {{ $note->quotation->quotation_number ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Delivery Date:</strong> {{ $note->delivery_date->format('F d, Y') }}</p>
                @if($note->dispatch_date)
                <p class="mb-1"><strong>Dispatch Date:</strong> {{ $note->dispatch_date->format('F d, Y') }}</p>
                @endif
                </div>
            </div>
            
            <hr class="my-3">
            
            <div class="row">
                <div class="col-md-6">
                <h6>Delivery Information</h6>
                @if($note->delivery_address)
                <p class="mb-1"><strong>Delivery Address:</strong></p>
                <p class="mb-1">{!! nl2br(str_replace('<br />', "\n", e($note->delivery_address))) !!}</p>
                @endif
                @if($note->delivery_contact_person)
                <p class="mb-1"><strong>Contact Person:</strong> {{ $note->delivery_contact_person }}</p>
                @endif
                @if($note->delivery_contact_phone)
                <p class="mb-0"><strong>Contact Phone:</strong> {{ $note->delivery_contact_phone }}</p>
                @endif
                </div>
                <div class="col-md-6">
                <h6>Transport Information</h6>
                @if($note->vehicle_number)
                <p class="mb-1"><strong>Vehicle #:</strong> {{ $note->vehicle_number }}</p>
                @endif
                @if($note->driver_name)
                <p class="mb-1"><strong>Driver:</strong> {{ $note->driver_name }}</p>
                @endif
                @if($note->driver_contact)
                <p class="mb-0"><strong>Driver Contact:</strong> {{ $note->driver_contact }}</p>
                @endif
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                <h6>Delivery Items</h6>
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
                        @foreach($note->formatted_items as $item)
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
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>Tsh{{ number_format($note->total, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
                </div>
            </div>
            
            @if($note->delivery_notes)
            <div class="row mt-4">
                <div class="col-12">
                <h6>Delivery Notes</h6>
                <div class="card bg-light p-3">
                    {{ $note->delivery_notes }}
                </div>
                </div>
            </div>
            @endif
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="{{ route('admin.delivery-note.print', $note) }}" 
                class="btn btn-primary" target="_blank">
                <i class="material-symbols-rounded">print</i> Print
            </a>
            <a href="{{ route('admin.delivery-note.download', $note) }}" 
                class="btn btn-success">
                <i class="material-symbols-rounded">download</i> Download PDF
            </a>
            </div>
        </div>
        </div>
    </div>
       @endforeach


  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Search functionality
      const searchInput = document.getElementById('search-delivery-notes');
      if (searchInput) {
        searchInput.addEventListener('input', function(e) {
          const searchTerm = e.target.value.toLowerCase();
          const rows = document.querySelectorAll('#delivery-notes-table tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
          });
        });
      }
      
      // Generate delivery note number on modal show
      const createModal = document.getElementById('createDeliveryNoteModal');
      if (createModal) {
        createModal.addEventListener('show.bs.modal', function() {
          // Reset form
          const form = document.getElementById('createDeliveryNoteForm');
          if (form) form.reset();
          document.getElementById('items-container').innerHTML = '';
          document.getElementById('total').value = '0.00';
          
          // Generate delivery note number via AJAX (only AJAX call we keep)
          fetch('{{ route("admin.delivery-note.generate") }}')
            .then(response => response.json())
            .then(data => {
              document.getElementById('delivery_note_number').value = data.delivery_note_number;
            });
        });
      }
      
      // Handle quotation selection in create modal - NO AJAX NEEDED
      const quotationSelect = document.getElementById('quotation_id');
      if (quotationSelect) {
        quotationSelect.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          const itemsContainer = document.getElementById('items-container');
          const totalInput = document.getElementById('total');
          
          // Clear existing items
          itemsContainer.innerHTML = '';
          
          if (!this.value) {
            totalInput.value = '0.00';
            document.getElementById('delivery_contact_person').value = '';
            document.getElementById('delivery_contact_phone').value = '';
            document.getElementById('delivery_address').value = '';
            return;
          }
          
          // Get data from data attributes
          const clientName = selectedOption.getAttribute('data-client-name') || '';
          const clientEmail = selectedOption.getAttribute('data-client-email') || '';
          const clientPhone = selectedOption.getAttribute('data-client-phone') || '';
          const itemsJson = selectedOption.getAttribute('data-items') || '[]';
          const quotationTotal = selectedOption.getAttribute('data-total') || '0.00';
          
          // Populate client info
          document.getElementById('delivery_contact_person').value = clientName;
          document.getElementById('delivery_contact_phone').value = clientPhone;
          
          // Set delivery address with client info
          let address = `${clientName}`;
          if (clientEmail) address += `\nEmail: ${clientEmail}`;
          if (clientPhone) address += `\nPhone: ${clientPhone}`;
          document.getElementById('delivery_address').value = address;
          
          // Parse items JSON from data attribute
          try {
            const items = JSON.parse(itemsJson);
            let totalAmount = 0;
            
            items.forEach((item, index) => {
              const itemRow = document.createElement('div');
              itemRow.className = 'item-row row mb-2';
              itemRow.innerHTML = `
                <div class="col-md-5">
                  <input type="text" class="form-control" 
                         name="items[${index}][description]" 
                         value="${item.description || ''}" readonly>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control original-quantity" 
                         value="${item.quantity || 0}" readonly>
                </div>
                <div class="col-md-2">
                  <input type="number" class="form-control delivered-quantity" 
                         name="items[${index}][quantity]" 
                         value="${item.quantity || 0}" min="0" 
                         max="${item.quantity || 0}" required>
                </div>
                <div class="col-md-3">
                  <input type="number" class="form-control price" 
                         name="items[${index}][price]" 
                         value="${item.price || 0}" step="0.01" readonly>
                </div>
              `;
              itemsContainer.appendChild(itemRow);
              
              totalAmount += (item.quantity || 0) * (item.price || 0);
              
              // Add event listener for quantity changes
              const qtyInput = itemRow.querySelector('.delivered-quantity');
              qtyInput.addEventListener('input', function() {
                calculateCreateTotal();
              });
            });
            
            // Set total
            totalInput.value = parseFloat(totalAmount).toFixed(2);
          } catch (error) {
            console.error('Error parsing items JSON:', error);
            itemsContainer.innerHTML = '<div class="alert alert-danger">Error loading quotation items. Please try again.</div>';
          }
        });
      }
      
      // Calculate total for create form
      function calculateCreateTotal() {
        const itemsContainer = document.getElementById('items-container');
        const totalInput = document.getElementById('total');
        let total = 0;
        
        itemsContainer.querySelectorAll('.item-row').forEach(row => {
          const quantity = parseFloat(row.querySelector('.delivered-quantity')?.value) || 0;
          const price = parseFloat(row.querySelector('.price')?.value) || 0;
          total += quantity * price;
        });
        
        totalInput.value = parseFloat(total).toFixed(2);
      }
      
      // Edit modal functionality
      function addEditItemRow(noteId) {
        const container = document.getElementById(`edit-items-container-${noteId}`);
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
            <input type="number" class="form-control edit-quantity" data-note="${noteId}"
                   name="items[${itemCount}][quantity]" min="0" value="0" required>
          </div>
          <div class="col-md-3">
            <input type="number" class="form-control edit-price" data-note="${noteId}"
                   name="items[${itemCount}][price]" step="0.01" min="0" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger remove-edit-item" data-note="${noteId}">
              <i class="material-symbols-rounded">delete</i>
            </button>
          </div>
        `;
        container.appendChild(newRow);
        
        // Add event listeners
        const qtyInput = newRow.querySelector('.edit-quantity');
        const priceInput = newRow.querySelector('.edit-price');
        
        qtyInput.addEventListener('input', () => calculateEditTotal(noteId));
        priceInput.addEventListener('input', () => calculateEditTotal(noteId));
      }
      
      function calculateEditTotal(noteId) {
        const container = document.getElementById(`edit-items-container-${noteId}`);
        const totalInput = document.getElementById(`edit-total-${noteId}`);
        
        if (!container || !totalInput) return;
        
        let total = 0;
        container.querySelectorAll('.row').forEach(row => {
          const quantity = parseFloat(row.querySelector('.edit-quantity')?.value) || 0;
          const price = parseFloat(row.querySelector('.edit-price')?.value) || 0;
          total += quantity * price;
        });
        
        totalInput.value = parseFloat(total).toFixed(2);
      }
      
      // Initialize edit modal totals
      @foreach($deliveryNotes as $note)
      calculateEditTotal({{ $note->id }});
      @endforeach
      
      // Event delegation for edit modal item management
      document.addEventListener('click', function(e) {
        // Remove item in edit modals
        if (e.target.closest('.remove-edit-item')) {
          const row = e.target.closest('.row');
          const noteId = e.target.closest('.remove-edit-item').getAttribute('data-note');
          const container = document.getElementById(`edit-items-container-${noteId}`);
          
          if (container && container.querySelectorAll('.row').length > 1) {
            row.remove();
            calculateEditTotal(noteId);
          }
        }
        
        // Add item in edit modals
        if (e.target.closest('.add-edit-item')) {
          const noteId = e.target.closest('.add-edit-item').getAttribute('data-note');
          addEditItemRow(noteId);
        }
      });
      
      // Add input event listeners for existing edit modal items
      @foreach($deliveryNotes as $note)
      const editContainer{{ $note->id }} = document.getElementById(`edit-items-container-{{ $note->id }}`);
      if (editContainer{{ $note->id }}) {
        editContainer{{ $note->id }}.querySelectorAll('.edit-quantity').forEach(input => {
          input.addEventListener('input', () => calculateEditTotal({{ $note->id }}));
        });
        editContainer{{ $note->id }}.querySelectorAll('.edit-price').forEach(input => {
          input.addEventListener('input', () => calculateEditTotal({{ $note->id }}));
        });
      }
      @endforeach
    });
  </script>
</body>
</html>