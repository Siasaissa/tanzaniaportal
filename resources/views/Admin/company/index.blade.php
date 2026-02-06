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
                            <h3 class="mb-0 h4 font-weight-bolder">Company Management</h3>
                            <p class="mb-0 text-sm">Manage all registered companies</p>
                        </div>
                        <!-- Removed Create button as per requirement -->
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Companies</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalCompanies }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">business</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Active</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $activeCompanies }}</h5>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Inactive</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $inactiveCompanies }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">pause_circle</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingCompanies }}</h5>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="material-symbols-rounded opacity-10">schedule</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Companies List</h6>
                                    <p class="text-sm mb-0">
                                        <i class="material-symbols-rounded text-info" style="font-size: 14px;">business</i>
                                        <span class="font-weight-bold ms-1">{{ $totalCompanies }} companies</span> registered
                                    </p>
                                </div>
                                <div class="input-group" style="width: 250px;">
                                    <span class="input-group-text text-body">
                                        <i class="material-symbols-rounded" style="font-size: 16px;">search</i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search companies..." id="search-companies">
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0" id="companies-table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Registered</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($companies as $company)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-1">
                                                    <div>
                                                        @if($company->logo)
                                                        <img src="{{ asset('storage/' . $company->logo) }}" class="avatar avatar-sm me-3" alt="{{ $company->name }}">
                                                        @else
                                                        <div class="avatar avatar-sm me-3 bg-gradient-primary">
                                                            <i class="material-symbols-rounded text-white">business</i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $company->name }}</h6>
                                                        @if($company->provider)
                                                        <p class="text-xs text-secondary mb-0">
                                                            <i class="material-symbols-rounded" style="font-size: 12px;">link</i>
                                                            {{ ucfirst($company->provider) }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2">
                                                    <div class="my-auto">
                                                        <h6 class="mb-0 text-sm">{{ $company->email }}</h6>
                                                        @if($company->phone)
                                                        <p class="text-xs text-secondary mb-0">{{ $company->phone }}</p>
                                                        @endif
                                                        @if($company->last_login_at)
                                                        <p class="text-xs text-secondary mb-0">
                                                            <i class="material-symbols-rounded" style="font-size: 10px;">login</i>
                                                            Last login: {{ $company->last_login_at->diffForHumans() }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2">
                                                    <div class="my-auto">
                                                        @if($company->company_desc)
                                                        <p class="text-sm mb-0">{{ \Illuminate\Support\Str::limit($company->company_desc, 100) }}</p>
                                                        @else
                                                        <p class="text-xs text-secondary mb-0">No description</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'inactive' => 'warning',
                                                        'pending' => 'info'
                                                    ];
                                                    $statusIcons = [
                                                        'active' => 'check_circle',
                                                        'inactive' => 'pause_circle',
                                                        'pending' => 'schedule'
                                                    ];
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $statusColors[$company->status] ?? 'secondary' }}">
                                                    <i class="material-symbols-rounded" style="font-size: 12px; vertical-align: middle;">
                                                        {{ $statusIcons[$company->status] ?? 'help' }}
                                                    </i>
                                                    {{ ucfirst($company->status) }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-xs font-weight-bold">
                                                    {{ $company->created_at->format('M d, Y') }}
                                                </span>
                                                <br>
                                                <small class="text-xs text-secondary">
                                                    {{ $company->created_at->diffForHumans() }}
                                                </small>
                                                @if($company->email_verified_at)
                                                <br>
                                                <small class="text-xs text-success">
                                                    <i class="material-symbols-rounded" style="font-size: 10px;">verified</i>
                                                    Verified
                                                </small>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-link text-info px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#viewCompanyModal{{ $company->id }}" 
                                                            title="View">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">visibility</i>
                                                    </button>
                                                    <button type="button" class="btn btn-link text-warning px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#editCompanyModal{{ $company->id }}" 
                                                            title="Edit">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">edit</i>
                                                    </button>
                                                    <button type="button" class="btn btn-link text-danger px-2 mb-0" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteCompanyModal{{ $company->id }}" 
                                                            title="Delete">
                                                        <i class="material-symbols-rounded" style="font-size: 18px;">delete</i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="material-symbols-rounded text-secondary mb-2" style="font-size: 48px;">business</i>
                                                    <h6 class="text-secondary">No companies found</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($companies->hasPages())
                            <div class="p-3">
                                {{ $companies->links('pagination::bootstrap-5') }}
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
    
    @foreach($companies as $company)
    
    <!-- View Company Modal -->
    <div class="modal fade" id="viewCompanyModal{{ $company->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $company->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" class="img-fluid rounded mb-3" alt="{{ $company->name }}">
                            @else
                            <div class="avatar avatar-xxl bg-gradient-primary mb-3">
                                <i class="material-symbols-rounded text-white" style="font-size: 48px;">business</i>
                            </div>
                            @endif
                            <span class="badge bg-gradient-{{ 
                                $company->status == 'active' ? 'success' : 
                                ($company->status == 'inactive' ? 'warning' : 'info') 
                            }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </div>
                        <div class="col-md-9">
                            <h6>Company Information</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Name:</strong> {{ $company->name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $company->email }}</p>
                                    @if($company->phone)
                                    <p class="mb-1"><strong>Phone:</strong> {{ $company->phone }}</p>
                                    @endif
                                    @if($company->provider)
                                    <p class="mb-1"><strong>Provider:</strong> {{ ucfirst($company->provider) }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Registered:</strong> {{ $company->created_at->format('F d, Y') }}</p>
                                    @if($company->email_verified_at)
                                    <p class="mb-1"><strong>Verified:</strong> {{ $company->email_verified_at->format('F d, Y') }}</p>
                                    @endif
                                    @if($company->last_login_at)
                                    <p class="mb-0"><strong>Last Login:</strong> {{ $company->last_login_at->format('M d, Y h:i A') }}</p>
                                    <p class="mb-0 text-sm text-muted">IP: {{ $company->last_login_ip ?? 'N/A' }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($company->company_desc)
                            <div class="mb-3">
                                <h6>Description</h6>
                                <div class="card bg-light p-3">
                                    {{ $company->company_desc }}
                                </div>
                            </div>
                            @endif
                            
                            <h6>Statistics</h6>
                            <div class="row mb-3 text-center">

                            <div class="col-md-3 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h6 class="mb-0">{{ $company->quotations()->count()  }}</h6>
                                        <small class="text-muted">Quotations</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h6 class="mb-0">{{ $company->invoice()->count() }}</h6>
                                        <small class="text-muted">Invoice</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h6 class="mb-0">{{ $company->deliveryNotes()->count() }}</h6>
                                        <small class="text-muted">Delivery Note</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h6 class="mb-0">{{ $company->PurchaseOrders()->count() }}</h6>
                                        <small class="text-muted">Purchase Order</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <h6 class="mb-0">{{ $company->receipts()->count() }}</h6>
                                        <small class="text-muted">Receipt</small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" 
                            data-bs-toggle="modal" data-bs-target="#editCompanyModal{{ $company->id }}"
                            data-bs-dismiss="modal">
                        <i class="material-symbols-rounded">edit</i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Company - {{ $company->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.company.update', $company) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" class="form-control" name="name" value="{{ $company->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-control" name="email" value="{{ $company->email }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{ $company->phone }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Provider</label>
                                <input type="text" class="form-control" name="provider" value="{{ $company->provider }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="active" {{ $company->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $company->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="pending" {{ $company->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Logo</label>
                                @if($company->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $company->logo) }}" class="img-thumbnail" style="max-height: 60px;">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="remove_logo" id="removeLogo{{ $company->id }}" value="1">
                                        <label class="form-check-label" for="removeLogo{{ $company->id }}">
                                            Remove current logo
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                <small class="text-muted">Max 2MB. Allowed: jpeg, png, jpg, gif</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Company Description</label>
                            <textarea class="form-control" name="company_desc" rows="3">{{ $company->company_desc }}</textarea>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password">
                                <small class="text-muted">Leave empty to keep current password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Company Modal -->
    <div class="modal fade" id="deleteCompanyModal{{ $company->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this company?</p>
                    <p><strong>Company:</strong> {{ $company->name }}</p>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Registered:</strong> {{ $company->created_at->format('M d, Y') }}</p>
                    
                    @if($company->quotations()->exists())
                    <div class="alert alert-warning mt-3">
                        <i class="material-symbols-rounded">warning</i>
                        This company has {{ $company->quotations()->count() }} quotations. 
                        Deleting it will remove all related quotations.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.company.destroy', $company) }}" method="POST">
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
            const searchInput = document.getElementById('search-companies');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('#companies-table tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
            
            // Handle logo removal checkbox
            document.querySelectorAll('[name="remove_logo"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const fileInput = this.closest('.modal-body').querySelector('input[type="file"]');
                    if (this.checked) {
                        fileInput.disabled = true;
                    } else {
                        fileInput.disabled = false;
                    }
                });
            });
            
            // Password validation
            document.querySelectorAll('form').forEach(form => {
                const password = form.querySelector('input[name="password"]');
                const confirmPassword = form.querySelector('input[name="password_confirmation"]');
                
                if (password && confirmPassword) {
                    function validatePasswords() {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('Passwords do not match');
                        } else {
                            confirmPassword.setCustomValidity('');
                        }
                    }
                    
                    password.addEventListener('input', validatePasswords);
                    confirmPassword.addEventListener('input', validatePasswords);
                }
            });
            
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