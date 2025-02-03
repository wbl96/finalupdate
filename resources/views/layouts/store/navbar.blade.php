<nav class="navbar navbar-expand-lg py-0 ">
    <div class="container-fluid">

        <div class="d-flex justify-content-between w-100 align-items-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" onclick="showNavbar()">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex">
                <div class="collapse navbar-collapse align-items-stretch" id="navbarNav">
                    <ul class="navbar-nav">
                        @auth
                            <div @class([
                                'item-container',
                                'active' =>
                                    Route::currentRouteName() == 'store.dashboard' ||
                                    Route::currentRouteName() == 'store.categories.get-products',
                            ])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('store.dashboard') }}">المتجر</a>
                                </li>
                            </div>
                            {{-- <div @class([
                                'item-container',
                                'active' => Route::currentRouteName() == 'store.suppliers.list',
                            ])>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('store.suppliers.list') }}">{{ trans('users.the suppliers') }}</a>
                                </li>
                            </div> --}}
                            <div @class([
                                'item-container',
                                'active' =>
                                    Str::contains(Route::currentRouteName(), 'orders') ||
                                    Route::currentRouteName() == 'store.cart.show',
                            ])>
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('store.orders.index') }}">{{ trans('orders.the orders') }}</a>
                                </li>
                            </div>
                            <div @class([
                                'item-container',
                                'active' => Str::contains(Route::currentRouteName(), 'reports'),
                            ])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('store.reports.index') }}">التقارير</a>
                                </li>
                            </div>
                            <div class="item-container position-relative">
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" role="button" onclick="toggleContactMenu(event)">
                                        <i class="fas fa-headset text-primary"></i> اتصل بنا
                                    </a>
                                    <div class="contact-menu" id="contactMenu">
                                        <div class="contact-menu-header d-md-none">
                                            <h6 class="text-center mb-0">تواصل معنا</h6>
                                        </div>
                                        <div class="contact-menu-items">
                                            <a class="contact-item email-item" href="mailto:support@wbl.sa">
                                                <i class="fas fa-envelope me-2"></i> 
                                                <div class="d-md-none">
                                                    <div class="item-title">البريد الإلكتروني</div>
                                                    <div class="item-subtitle">support@wbl.sa</div>
                                                </div>
                                                <span class="d-none d-md-block">support@wbl.sa</span>
                                            </a>
                                            <a class="contact-item whatsapp-item" href="https://wa.me/966559796744" target="_blank">
                                                <i class="fab fa-whatsapp me-2"></i>
                                                <div class="d-md-none">
                                                    <div class="item-title">واتساب</div>
                                                    <div class="item-subtitle">0559796744</div>
                                                </div>
                                                <span class="d-none d-md-block">0559796744</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <div @class([
                                'item-container',
                                'active' => Route::currentRouteName() == 'store.wallet.index',
                            ])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('store.wallet.index') }}">
                                        المحفظة
                                        @if(Auth::user()->wallet)
                                            <span class="badge bg-success">
                                                {{ number_format(Auth::user()->wallet->balance, 0) }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            </div>
                        @else
                        
                            <div @class(['item-container'])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('store.showlogin') }}">تسجيل الدخول كتاجر</a>
                                </li>
                            </div>
                            <div @class(['item-container'])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('supplier.showlogin') }}">تسجيل الدخول كمورد</a>
                                </li>
                            </div>
                            <div @class(['item-container'])>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">تسجيل جديد</a>
                                </li>
                            </div>
                        @endauth
                    </ul>
                </div>

                @auth
                    <ul class="navbar-nav d-flex align-items-center flex-row px-0">
                        <li class="nav-item me-3 d-flex">
                            <a href="{{ route('store.rfq.index') }}" class="cart-icon-container">
                                <div class="cart-icon-wrapper">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span class="rfq-count">{{ Auth::user()->rfq->count() ?? 0 }}</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item d-flex">
                            <a href="{{ route('store.cart.show') }}" class="cart-icon-container">
                                <div class="cart-icon-wrapper">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="cart-count">{{ Auth::user()->cart?->items->count() ?? 0 }}</span>
                                </div>
                            </a>
                        </li>

                        <li class="store-icon-container">
                            <a href="#" class="store-icon">
                                <div class="store-icon-wrapper">
                                    <i class="fas fa-store"></i>
                                    <span class="store-status">مفتوح</span>
                                </div>
                            </a>
                            <div class="store-tooltip">
                                <h6>{{ Auth::user()->name }}</h6>
                                <p>تصنيف المتجر: <i class="fas fa-star"></i> 4.5</p>
                                <div class="d-flex justify-content-around">
                                    <button class="btn btn-sm btn-outline-light">إدارة المتجر</button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل
                                        الخروج</button>
                                    <form id="logout-form" action="{{ route('store.logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                        {{-- {{ csrf_token() }} --}}
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endauth
            </div>
            <ul class="navbar-nav me-auto">
                <div class="item-container">
                    <li class="nav-item">
                        <a class="navbar-brand nav-link text-white p-0 w-auto" href="{{ route('store.dashboard') }}">
                            <img src="{{ asset('img/logo_n.svg') }}" alt="Logo" height="55"
                                class="d-inline-block align-top right-radius">
                        </a>
                    </li>
                </div>
            </ul>
        </div>
    </div>
</nav>

@php
    $allowedRoutes = ['store.dashboard', 'store.categories.get-products', 'welcome', 'guest.categories.get-products'];
@endphp
@if (in_array(Route::currentRouteName(), $allowedRoutes))
    <div @class([
        'container-fluid bg-white',
        'overflow-x-scroll' => $categories->count() > 10,
    ]) @style('overflow-y: auto;')>
        <div class="section-tabs">
            <div class="tab">
                <a href="{{ Auth::check() ? route('store.dashboard') : route('welcome') }}"
                    @class([
                        'tab-link',
                        'active' => in_array(Route::currentRouteName(), [
                            'welcome',
                            'store.dashboard',
                        ]),
                    ])>جميع المنتجات
                </a>
            </div>
            @foreach ($categories as $cat)
                @php
                    $subCategories = \App\Models\ProductsSubCategories::where('category_id', $cat->id)->get();
                    $isActive =
                        Str::contains(Route::currentRouteName(), 'categories.get-products') &&
                        request()->route('category')->id == $cat->id;
                @endphp
                <div class="tab dropdown">
                    <a href="{{ Auth::check() ? route('store.categories.get-products', $cat) : route('guest.categories.get-products', $cat) }}"
                        @class(['tab-link', 'active' => $isActive])>
                        {{ $locale == 'ar' ? $cat->name_ar : $cat->name_en }}
                        @if($subCategories->count() > 0)
                            <i class="fas fa-chevron-down ms-1"></i>
                        @endif
                    </a>
                    @if($subCategories->count() > 0)
                        <div class="dropdown-content">
                            <div class="mobile-header">
                                <div class="header-content">
                                    <span class="category-title">{{ $locale == 'ar' ? $cat->name_ar : $cat->name_en }}</span>
                                    <span class="category-count">الأقسام الفرعية</span>
                                </div>
                                <button class="mobile-close-btn" onclick="closeDropdown(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="subcategories-list">
                                <a href="{{ Auth::check() 
                                    ? route('store.categories.get-products', ['category' => $cat]) 
                                    : route('guest.categories.get-products', ['category' => $cat]) }}"
                                   class="sub-category featured-category">
                                    <div class="category-info">
                                        <span class="sub-category-name">جميع منتجات {{ $locale == 'ar' ? $cat->name_ar : $cat->name_en }}</span>
                                        <span class="category-description">عرض كافة المنتجات في هذا القسم</span>
                                    </div>
                                    <i class="fas fa-chevron-left category-arrow"></i>
                                </a>

                                @foreach($subCategories as $subCat)
                                    <a href="{{ Auth::check() 
                                        ? route('store.categories.get-products', ['category' => $cat, 'sub_category_id' => $subCat->id]) 
                                        : route('guest.categories.get-products', ['category' => $cat, 'sub_category_id' => $subCat->id]) }}"
                                       class="sub-category">
                                        <div class="category-info">
                                            <span class="sub-category-name">{{ $locale == 'ar' ? $subCat->name_ar : $subCat->name_en }}</span>
                                        </div>
                                        <i class="fas fa-chevron-left category-arrow"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
  

<div id="dropdown-portal"></div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const portal = document.getElementById('dropdown-portal');
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('.tab-link');
            const content = dropdown.querySelector('.dropdown-content');
            
            if (content) {
                portal.appendChild(content);
                
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    document.querySelectorAll('.dropdown-content').forEach(menu => {
                        menu.style.display = 'none';
                    });
                    
                    const rect = link.getBoundingClientRect();
                    content.style.top = `${rect.bottom + 10}px`;
                    content.style.left = `${rect.left}px`;
                    
                    if (content.style.display !== 'block') {
                        content.style.display = 'block';
                        document.body.classList.add('dropdown-open');
                    } else {
                        content.style.display = 'none';
                        document.body.classList.remove('dropdown-open');
                    }
                });
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-content')) {
                document.querySelectorAll('.dropdown-content').forEach(menu => {
                    menu.style.display = 'none';
                });
                document.body.classList.remove('dropdown-open');
            }
        });
    });
    </script>




@endif
