<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
       id="sidenav-main">

    <!-- Sidenav Header -->
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>

        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('dashboard') }}">
            <span class="ms-1 text-sm text-dark">
                {{ Auth::user()->name ?? 'User' }}
            </span>
        </a>
    </div>

    <hr class="horizontal dark mt-0 mb-2">

    <!-- Sidebar Menu -->
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">

            @php
                function activeLink($route) {
                    $current = Route::currentRouteName();
                    
                    // Handle wildcard routes
                    if (str_contains($route, '*')) {
                        $base = str_replace('.*', '', $route);
                        return str_starts_with($current, $base) 
                            ? 'active bg-warning text-white' 
                            : 'text-dark';
                    }
                    
                    // Handle exact route matches
                    return $current === $route 
                        ? 'active bg-warning text-white' 
                        : 'text-dark';
                }
                
                // Function to check if any parent route is active
                function isActiveParent($routes) {
                    $current = Route::currentRouteName();
                    foreach ($routes as $route) {
                        if (str_contains($route, '*')) {
                            $base = str_replace('.*', '', $route);
                            if (str_starts_with($current, $base)) {
                                return true;
                            }
                        } elseif ($current === $route) {
                            return true;
                        }
                    }
                    return false;
                }
            @endphp

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('dashboard') }}"
                   href="{{ route('dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Quotation -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.quotation.*') }}"
                   href="{{ route('admin.quotation.index') }}">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Quotation</span>
                </a>
            </li>

            <!-- Invoice -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.invoice.*') }}"
                   href="{{ route('admin.invoice.index') }}">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Invoice</span>
                </a>
            </li>

            <!-- Delivery Note -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.delivery-note.*') }}"
                   href="{{ route('admin.delivery-note.index') }}">
                    <i class="material-symbols-rounded opacity-5">local_shipping</i>
                    <span class="nav-link-text ms-1">Delivery Note</span>
                </a>
            </li>

            <!-- Purchase Order -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.purchase-order.*') }}"
                   href="{{ route('admin.purchase-order.index') }}">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Purchase Order</span>
                </a>
            </li>

            <!-- Receipt -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.receipt.*') }}"
                   href="{{ route('admin.receipt.index') }}">
                    <i class="material-symbols-rounded opacity-5">payments</i>
                    <span class="nav-link-text ms-1">Receipt</span>
                </a>
            </li>

            <!-- 
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.setting.*') }}"
                   href="{{ route('admin.setting.index') }}">
                    <i class="material-symbols-rounded opacity-5">settings</i>
                    <span class="nav-link-text ms-1">Settings</span>
                </a>
            </li>
            Settings -->
            
            <!-- Register Company -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.company') }}"
                   href="{{ route('admin.company') }}">
                    <i class="material-symbols-rounded opacity-5">business</i>
                    <span class="nav-link-text ms-1">Register Company</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ activeLink('admin.company.index') }}"
                   href="{{ route('admin.company.index') }}">
                    <i class="material-symbols-rounded opacity-5">business</i>
                    <span class="nav-link-text ms-1">Companies</span>
                </a>
            </li>

        </ul>
    </div>

    <!-- Action Button -->
    @php
        $current = Route::currentRouteName();
        $action = null;
        
        // Define action buttons based on current route
        if (str_starts_with($current, 'admin.quotation.')) {
            $action = [
                'label' => 'New Quotation', 
                'target' => '#createQuotationModal',
                'route' => route('admin.quotation.create')
            ];
        } 
        elseif (str_starts_with($current, 'admin.delivery-note.')) {
            $action = [
                'label' => 'New Delivery Note', 
                'target' => '#createDeliveryNoteModal',
                'route' => route('admin.delivery-note.store')
            ];
        }
        elseif (str_starts_with($current, 'admin.invoice.')) {
            $action = [
                'label' => 'New Invoice', 
                'target' => '#createInvoiceModal',
                'route' => route('admin.invoice.store')
            ];
        }
        elseif (str_starts_with($current, 'admin.purchase-order.')) {
            $action = [
                'label' => 'New Purchase', 
                'target' => '#createPurchaseOrderModal',
                'route' => route('admin.purchase-order.store')
            ];
        }
        elseif (str_starts_with($current, 'admin.receipt.')) {
            $action = [
                'label' => 'New Receipt', 
                'target' => '#createReceiptModal',
                'route' => route('admin.receipt.store')
            ];
        }
        
        // Check if we're on a creation page (don't show button on create pages)
        $isCreatePage = str_contains($current, '.create') || str_contains($current, '.store');
    @endphp

    @if($action && !$isCreatePage)
        <div class="sidenav-footer mx-3 mt-3">
            <!-- Check if modal exists or use direct link -->
            @if($action['target'])
                <button class="btn bg-gradient-warning w-100 text-white"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="{{ $action['target'] }}">
                    <span class="material-symbols-rounded me-2">add</span>
                    {{ $action['label'] }}
                </button>
            @elseif($action['route'])
                <a href="{{ $action['route'] }}" 
                   class="btn bg-gradient-warning w-100 text-white">
                    <span class="material-symbols-rounded me-2">add</span>
                    {{ $action['label'] }}
                </a>
            @endif
        </div>
    @endif

    <!-- Logout -->
    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger mt-4 w-100">
                    <span class="material-symbols-rounded align-middle me-1">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </div>

</aside>