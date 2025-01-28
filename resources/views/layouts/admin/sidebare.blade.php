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
            'active' => Route::currentRouteName() == 'admin.dashboard',
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.dashboard') }}" role="button">
                <label>
                    <i class="fas fa-dashboard me-1"></i>
                    <span class="sidebar-title">الرئيسية</span>
                </label>
            </a>
        </li>

        @php
            $sb_init =
                Str::contains(Route::currentRouteName(), 'admins') ||
                Str::contains(Route::currentRouteName(), 'admingroups');
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#group-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fas fa-users-cog me-1"></i>
                    <span class="sidebar-title">الإدارة والصلاحيات</span>
                </label>
            </button>
            <div @class(['collapse', 'show' => $sb_init]) id="group-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li @class(['active' => Route::currentRouteName() == 'admin.admins.list'])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-eye me-1"></i>
                            <a href="{{ route('admin.admins.list') }}" class="link-dark rounded">الإداريون</a>
                        </label>
                    </li>
                    {{-- <li class="{{ request()->is('admin/admingroups/*') ? 'active' : '' }}">
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-pencil me-1"></i>
                            <a href="{{ route('admin.admingroups.index') }}" class="link-dark rounded">الصلاحيات</a>
                        </label>
                    </li> --}}
                </ul>
            </div>
        </li>
        @php
            $sb_init =
                Str::contains(Route::currentRouteName(), 'user') || Str::contains(Route::currentRouteName(), 'users');
            $userTypes = ['all', 'supplier', 'store', 'provider'];
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#users-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fas fa-users-cog me-1"></i>
                    <span class="sidebar-title">{{ trans('users.dashboard') }}</span>
                </label>
            </button>
            <div class="collapse {{ $sb_init == 'true' ? 'show' : '' }}" id="users-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @foreach ($userTypes as $userType)
                        <li @class(['active' => request()->is('admins/users/' . $userType)])>
                            <label class="d-flex align-items-center align-content-center ps-4">
                                <i class="fas fa-eye me-1"></i>
                                <a href="{{ route('admin.users.list', $userType) }}" class="link-dark rounded">
                                    {{ trans("users.the {$userType}s") }}
                                </a>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'categories'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.categories.index') }}" role="button">
                <label>
                    <i class="fa fa-project-diagram"></i>
                    <span class="sidebar-title">{{ trans('categories.the categories') }}</span>
                </label>
            </a>
        </li>

        @php
            $sb_init = Str::contains(Route::currentRouteName(), 'products');
            $products = [
                'all products' => 'all',
                'products active' => 'active',
                'products inactive' => 'inactive',
            ];
        @endphp
        <li class="mb-1">
            <button class="btn has-tree btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                data-bs-toggle="collapse" data-bs-target="#products-collapse" aria-expanded="{{ $sb_init }}">
                <label>
                    <i class="fa-product-hunt fab me-1"></i>
                    <span class="sidebar-title">{{ trans('products.list') }}</span>
                </label>
            </button>
            <div class="collapse {{ $sb_init == 'true' ? 'show' : '' }}" id="products-collapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li @class(['active' => request()->is('admins/products/create')])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-plus me-1"></i>
                            <a href="{{ route('admin.products.create') }}" class="link-dark rounded">
                                {{ trans('products.add product') }}
                            </a>
                        </label>
                    </li>
                    @foreach ($products as $key => $product)
                        <li @class(['active' => request()->is('admins/products/' . $product)])>
                            <label class="d-flex align-items-center align-content-center ps-4">
                                <i class="fas fa-eye me-1"></i>
                                <a href="{{ route('admin.products.index') . '?type=' . $product }}"
                                    class="link-dark rounded">
                                    {{ trans("products.{$key}") }}
                                </a>
                            </label>
                        </li>
                    @endforeach
                    <li @class([
                        'active' => request()->is(route('admin.products.requests')),
                    ])>
                        <label class="d-flex align-items-center align-content-center ps-4">
                            <i class="fas fa-eye me-1"></i>
                            <a href="{{ route('admin.products.requests') }}" class="link-dark rounded">
                                {{ trans('products.request add product') }}
                            </a>
                        </label>
                    </li>
                </ul>
            </div>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'services'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.services.index') }}" role="button">
                <label>
                    <i class="fa fa-project-diagram"></i>
                    <span class="sidebar-title">{{ trans('services.the services') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'orders'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.orders.index') }}" role="button">
                <label>
                    <i class="fa fa-list"></i>
                    <span class="sidebar-title">{{ trans('orders.the orders') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'generalSettings'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.settings.generalSettings') }}" role="button">
                <label>
                    <i class="fa fa-gears"></i>
                    <span class="sidebar-title">{{ trans('settings.settings') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'terms'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.site-content.terms.index') }}" role="button">
                <label>
                    <i class="bi bi-bookmark"></i>
                    <span class="sidebar-title">{{ trans('site_content.terms & conditions') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'privacy'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.site-content.privacy.index') }}" role="button">
                <label>
                    <i class="bi bi-bookmark-fill"></i>
                    <span class="sidebar-title">{{ trans('site_content.use & privacy policy') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'about'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.site-content.about.index') }}" role="button">
                <label>
                    <i class="bi bi-patch-check"></i>
                    <span class="sidebar-title">{{ trans('site_content.about us') }}</span>
                </label>
            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'contacts-settings'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.settings.contactsSettings') }}" role="button">
                <label>
                    <i class="fa-brands fa-nfc-symbol"></i>
                    <span class="sidebar-title">{{ trans('settings.contacts info') }}</span>
                </label>

            </a>
        </li>

        <li @class([
            'mb-1',
            'active' => Str::contains(Route::currentRouteName(), 'faqs'),
        ])>
            <a class="btn btn-toggle align-items-center rounded collapsed justify-content-between w-100"
                href="{{ route('admin.site-content.faq.index') }}" role="button">
                <label>
                    <i class="bi bi-question-lg"></i>
                    <span class="sidebar-title">{{ trans('site_content.faqs') }}</span>
                </label>
            </a>
        </li>

        {{--
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
