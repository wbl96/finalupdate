<style>
    .bi {
        vertical-align: -.125em;
        pointer-events: none;
        fill: currentColor;
    }

    .dropdown-toggle {
        outline: 0;
    }

    .nav-flush .nav-link {
        border-radius: 0;
    }

    .btn-toggle {
        display: inline-flex;
        align-items: center;
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgba(0, 0, 0, .65);
        background-color: transparent;
        border: 0;
    }

    .btn-toggle:hover {
        color: rgba(0, 0, 0, .85);
        background-color: #d2f4ea;
    }

    .has-tree.btn-toggle::after {
        width: 1.25em;
        line-height: 0;
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        transition: transform .35s ease;
        transform-origin: .5em 50%;
    }

    .has-tree.btn-toggle[aria-expanded="true"] {
        color: rgba(0, 0, 0, .85);
    }

    .has-tree.btn-toggle[aria-expanded="true"]::after {
        transform: rotate(90deg);
    }

    .btn-toggle-nav a {
        display: inline-flex;
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
        text-decoration: none;
    }

    #sidebar-wrapper li:not(:has(.has-tree)):hover {
        background-color: #d2f4ea80;
    }

    #sidebar-wrapper li * {
        color: var(--fourth-color) !important;
    }

    #sidebar-wrapper li:hover {
        border-radius: 0;
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
    }

    #sidebar-wrapper li:not(:has(.has-tree)):hover a {
        color: #000 !important;
    }

    #sidebar-wrapper li:hover .btn-toggle {
        background-color: #155329 !important;
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
    }

    #sidebar-wrapper li.active {
        background-color: #155329;
        border-radius: 0;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
        color: #006345;
        border-bottom-left-radius: 15px;
        border-top-left-radius: 15px;
    }

    .scrollarea {
        overflow-y: auto;
    }

    .fw-semibold {
        font-weight: 600;
    }

    .lh-tight {
        line-height: 1.25;
    }

    #wrapper .btn:focus {
        box-shadow: unset;
    }

    #sidebar-wrapper li * {
        cursor: pointer;
    }

    .has-tree.btn-toggle::after {
        filter: invert(94%) sepia(7%) saturate(762%) hue-rotate(84deg) brightness(94%) contrast(89%);
    }
</style>
<div class="flex-shrink-0 pt-0" style="min-height:100vmin; margin-top:55px" id="sidebar-wrapper">
    <ul class="list-unstyled p-2 ps-0 py-3">
        <li @class([
            'mb-1',
            'active' => Route::currentRouteName() == 'supplier.dashboard',
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('supplier.dashboard') }}" role="button">
                <label>
                    <i class="fas fa-dashboard me-1"></i>
                    <span class="sidebar-title">{{ trans('global.home') }}</span>
                </label>
            </a>
        </li>

        {{-- @php
            $sb_init = (bool) Str::contains(Route::currentRouteName(), 'clients');
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#suppliers-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fas fa-users me-1"></i>
                    <span class="sidebar-title">{{ trans('clients.the clients') }}</span>
                </label>
            </button>
            <div @class(['collapse', 'show' => $sb_init]) id="suppliers-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li @class([
                        'active' => Route::currentRouteName() == 'supplier.clients.list',
                    ])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-users me-1"></i>
                            <a href="{{ route('supplier.clients.list') }}" class="link-dark rounded">
                                {{ trans('clients.list') }}
                            </a>
                        </label>
                    </li>
                </ul>
            </div>
        </li> --}}

        @php
            $sb_init = (bool) Str::contains(Route::currentRouteName(), 'products');
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#products-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fas fa-box-open me-1"></i>
                    <span class="sidebar-title">{{ trans('products.the products') }}</span>
                </label>
            </button>
            <div @class(['collapse', 'show' => $sb_init]) id="products-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li @class([
                        'active' => Route::currentRouteName() == 'supplier.products.list',
                    ])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-dolly me-1"></i>
                            <a href="{{ route('supplier.products.list') }}" class="link-dark rounded">
                                {{ trans('products.list') }}
                            </a>
                        </label>
                    </li>
                </ul>
            </div>
        </li>
        @php
            $sb_init = (bool) Str::contains(Route::currentRouteName(), 'orders');
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fas fa-box-open me-1"></i>
                    <span class="sidebar-title">{{ trans('orders.the orders') }}</span>
                </label>
            </button>
            <div @class(['collapse', 'show' => $sb_init]) id="orders-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li @class([
                        'active' => Route::currentRouteName() == 'supplier.orders.list',
                    ])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-dolly me-1"></i>
                            <a href="{{ route('supplier.orders.list') }}" class="link-dark rounded">
                                {{ trans('orders.list') }}
                            </a>
                        </label>
                    </li>
                    <li @class([
                        'active' => Route::currentRouteName() == 'supplier.orders.rfq-requests',
                    ])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-dolly me-1"></i>
                            <a href="{{ route('supplier.orders.rfq-requests') }}" class="link-dark rounded">
                                {{ trans('orders.rfq requests') }}
                            </a>
                        </label>
                    </li>
                </ul>
            </div>
        </li>

        @php
            $sb_init = (bool) Str::contains(Route::currentRouteName(), 'reports');
        @endphp
        <li @class(['mb-1', 'active' => $sb_init])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('supplier.reports.index') }}" role="button">
                <label>
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span class="sidebar-title">{{ trans('reports.the reports') }}</span>
                </label>
            </a>
        </li>

        @php
            $sb_init = Route::currentRouteName() == 'supplier.policies.index';
        @endphp
        <li @class(['mb-1', 'active' => $sb_init])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('supplier.policies.index') }}" role="button">
                <label>
                    <i class="bi bi-bookmark"></i>
                    <span class="sidebar-title">{{ trans('policies.suppliers.sale & return policy') }}</span>
                </label>
            </a>
        </li>

        {{-- 
        @php
            $sb_init =
                Str::contains(Route::currentRouteName(), 'user') || Str::contains(Route::currentRouteName(), 'users');
            $userTypes = array_merge(['all'], \App\Models\User::$types);
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="{{ (boolean)$sb_init }}">
                <label>
                    <i class="fas fa-users-cog me-1"></i>
                    <span class="sidebar-title">{{ trans('users.dashboard') }}</span>
                </label>
            </button>
            <div class="collapse {{ $sb_init == 'true' ? 'show' : '' }}" id="users-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @foreach ($userTypes as $userType)
                        <li @class(['active' => request()->is('admin/user/' . $userType)])>
                            <label class="d-flex align-items-center align-content-center ps-4">
                                <i class="fas fa-eye me-1"></i>
                                <a href="{{ route('admin.users.list', $userType) }}"
                                    class="link-dark rounded">
                                    {{ trans("users.{$userType}s") }}
                                </a>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li> 

        @php
            $sb_init = request()->is('admin/services*') ? 'true' : 'false';
        @endphp
        <li class="mb-1 {{ request()->is('admin/services') ? 'active' : '' }}">
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.services.index') }}" role="button">
                <label>
                    <i class="fa fa-project-diagram"></i>
                    <span class="sidebar-title">الخدمات</span>
                </label>
            </a>
        </li> 
    
        <li class="mb-1">
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="#ff" role="button">
                <span class="sidebar-title">No tree</span>
            </a>
        </li>

        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                <span class="sidebar-title">Dashboard</span>
            </button>
            <div class="collapse" id="dashboard-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="#" class="link-dark rounded">Overview</a></li>
                    <li><a href="#" class="link-dark rounded">Weekly</a></li>
                    <li><a href="#" class="link-dark rounded">Monthly</a></li>
                    <li><a href="#" class="link-dark rounded">Annually</a></li>
                </ul>
            </div>
        </li>
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                <span class="sidebar-title">Orders</span>
            </button>
            <div class="collapse" id="orders-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="#" class="link-dark rounded">New</a></li>
                    <li><a href="#" class="link-dark rounded">Processed</a></li>
                    <li><a href="#" class="link-dark rounded">Shipped</a></li>
                    <li><a href="#" class="link-dark rounded">Returned</a></li>
                </ul>
            </div>
        </li>
        <li class="border-top my-3"></li>
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                <span class="sidebar-title">Account</span>
            </button>
            <div class="collapse" id="account-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li><a href="#" class="link-dark rounded">New...</a></li>
                    <li><a href="#" class="link-dark rounded">Profile</a></li>
                    <li><a href="#" class="link-dark rounded">Settings</a></li>
                    <li><a href="#" class="link-dark rounded">Sign out</a></li>
                </ul>
            </div>
        </li>
        --}}
    </ul>
</div>
