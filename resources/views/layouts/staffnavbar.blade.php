<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
       id="sidenav-main">

    <!-- Sidenav Header -->
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>

        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('company.dashboard') }}">
            <img src="{{ asset('storage/' . Auth::user()->logo) }}"
                 class="navbar-brand-img"
                 width="26"
                 height="26"
                 alt="Company Logo"
                 onerror="this.style.display='none'">

            <span class="ms-1 text-sm text-dark">
                {{ Auth::user()->name }}
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
                    if (str_contains($route, '*')) {
                        $base = str_replace('.*', '', $route);
                        return str_starts_with($current, $base) 
                            ? 'active bg-warning text-white' 
                            : 'text-dark';
                    }
                    return $current === $route 
                        ? 'active bg-warning text-white' 
                        : 'text-dark';
                }
            @endphp

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.dashboard') }}"
                   href="{{ route('company.dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <!-- Quotation -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.quotation.*') }}"
                   href="{{ route('company.quotation.index') }}">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Quotation</span>
                </a>
            </li>

            <!-- Invoice -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.invoice.*') }}"
                   href="{{ route('company.invoice.index') }}">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Invoice</span>
                </a>
            </li>

            <!-- Delivery Note -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.delivery-note.*') }}"
                   href="{{ route('company.delivery-note.index') }}">
                    <i class="material-symbols-rounded opacity-5">local_shipping</i>
                    <span class="nav-link-text ms-1">Delivery Note</span>
                </a>
            </li>

            <!-- Purchase Order -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.purchase-order.*') }}"
                   href="{{ route('company.purchase-order.index') }}">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Purchase Order</span>
                </a>
            </li>

            <!-- Receipt -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.receipt.*') }}"
                   href="{{ route('company.receipt.index') }}">
                    <i class="material-symbols-rounded opacity-5">payments</i>
                    <span class="nav-link-text ms-1">Receipt</span>
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link {{ activeLink('company.setting.*') }}"
                   href="{{ route('company.setting.index') }}">
                    <i class="material-symbols-rounded opacity-5">settings</i>
                    <span class="nav-link-text ms-1">Settings</span>
                </a>
            </li>

        </ul>
    </div>

    <!-- Action Button -->
    @php
        $current = Route::currentRouteName();
        $action = null;
        
        if (str_starts_with($current, 'company.quotation.')) {
            $action = ['label' => 'New Quotation', 'target' => '#createQuotationModal'];
        } 
        elseif (str_starts_with($current, 'company.delivery-note.')) {
            $action = ['label' => 'New Delivery Note', 'target' => '#createDeliveryNoteModal'];
        }
        elseif (str_starts_with($current, 'company.invoice.')) {
            $action = ['label' => 'New Invoice', 'target' => '#createInvoiceModal'];
        }
        elseif (str_starts_with($current, 'company.purchase-order.')) {
            $action = ['label' => 'New Purchase', 'target' => '#createPurchaseOrderModal'];
        }
        elseif (str_starts_with($current, 'company.receipt.')) {
            $action = ['label' => 'New Receipt', 'target' => '#createReceiptModal'];
        }
    @endphp

    @if($action)
        <div class="sidenav-footer mx-3 mt-3">
            <button class="btn bg-gradient-warning w-100 text-white"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="{{ $action['target'] }}">
                <span class="material-symbols-rounded me-2">add</span>
                {{ $action['label'] }}
            </button>
        </div>
    @endif

    <!-- Logout -->
    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3">
            <form method="POST" action="{{ route('company.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger mt-4 w-100">
                    Logout
                </button>
            </form>
        </div>
    </div>

</aside>