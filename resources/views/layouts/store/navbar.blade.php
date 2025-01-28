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

                        <div class="wallet-icon-container">
                            <div class="wallet-icon-wrapper">
                                <i class="fas fa-wallet"></i>
                                @if(Auth::user()->wallet)
                                    <div class="wallet-balance">
                                        {{ number_format(Auth::user()->wallet->balance, 0) }}
                                    </div>
                                @endif
                                <div class="tooltip">المحفظة المالية</div>
                            </div>
                        </div>
                    </ul>
                @endauth
            </div>
            <ul class="navbar-nav me-auto">
                <div class="item-container">
                    <li class="nav-item">
                        <a class="navbar-brand nav-link text-white p-0 w-auto d-flex align-items-center logo-container" href="{{ route('store.dashboard') }}">
                            <div class="logo-wrapper">
                                <img src="{{ asset('img/logo_n.svg') }}" alt="Logo" height="55"
                                    class="d-inline-block align-top right-radius logo-image">
                                <div class="logo-content">
                                    <div class="brand-title">
                                        <div class="title-box">
                                            <span class="title-text">وبــل</span>
                                            <div class="title-shadow"></div>
                                        </div>
                                    </div>
                                    <div class="brand-slogan">
                                        <div class="slogan-box">
                                            <div class="slogan-icon">⚡</div>
                                            <span class="slogan-text">طريقك الأذكى لسوق الجملة</span>
                                            <div class="slogan-shine"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    $isActive =
                        Str::contains(Route::currentRouteName(), 'categories.get-products') &&
                        request()->route('category')->id == $cat->id;
                @endphp
                <div class="tab">
                    <a href="{{ Auth::check() ? route('store.categories.get-products', $cat) : route('guest.categories.get-products', $cat) }}"
                        @class(['tab-link', 'active' => $isActive])>{{ $locale == 'ar' ? $cat->name_ar : $cate->name_en }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');

    .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 8px 15px;
        position: relative;
    }

    .logo-image {
        filter: drop-shadow(0 4px 6px rgba(139, 173, 163, 0.2));
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .logo-wrapper:hover .logo-image {
        filter: drop-shadow(0 6px 8px rgba(139, 173, 163, 0.3));
        transform: translateY(-2px) scale(1.02);
    }

    .title-box {
        position: relative;
        display: inline-block;
    }

    .title-text {
        color: #8bada3;
        font-size: 40px;
        font-weight: 800;
        font-family: 'Tajawal', sans-serif;
        position: relative;
        z-index: 2;
        background: linear-gradient(45deg, #8bada3, #9ec2b9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 2px rgba(139, 173, 163, 0.3));
    }

    .title-shadow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #8bada3;
        filter: blur(12px);
        opacity: 0;
        transition: all 0.4s ease;
        z-index: 1;
    }

    .logo-wrapper:hover .title-shadow {
        opacity: 0.2;
        transform: translateY(2px);
    }

    .slogan-box {
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        padding: 4px 8px;
        border-radius: 20px;
        background: rgba(139, 173, 163, 0.1);
        transition: all 0.3s ease;
    }

    .logo-wrapper:hover .slogan-box {
        background: rgba(139, 173, 163, 0.15);
        transform: translateX(5px);
    }

    .slogan-icon {
        font-size: 14px;
        opacity: 0.8;
        transform: rotate(0deg);
        transition: all 0.4s ease;
    }

    .logo-wrapper:hover .slogan-icon {
        transform: rotate(360deg);
        opacity: 1;
    }

    .slogan-text {
        color: #8bada3;
        font-size: 15px;
        font-weight: 600;
        font-family: 'Tajawal', sans-serif;
    }

    .slogan-shine {
        position: absolute;
        top: 0;
        left: 0;
        width: 50px;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transform: skewX(-20deg) translateX(-150%);
        transition: all 0.6s ease;
    }

    .logo-wrapper:hover .slogan-shine {
        transform: skewX(-20deg) translateX(400%);
    }

    @media (max-width: 768px) {
        .title-text {
            font-size: 32px;
        }
        
        .slogan-text {
            font-size: 13px;
        }

        .logo-wrapper {
            gap: 12px;
            padding: 6px 10px;
        }
    }

    @media (max-width: 428px) {  /* يغطي iPhone 12 Pro Max وأصغر */
        .logo-content {
            display: none;
        }

        .logo-wrapper {
            padding: 4px;
        }

        .logo-image {
            height: 45px; /* تكبير حجم اللوجو */
        }

        .navbar {
            padding: 0 10px;
        }

        .navbar-nav {
            padding: 0;
        }

        .item-container {
            padding: 4px;
        }

        /* منع التمرير الأفقي */
        .container-fluid {
            padding-right: 0;
            padding-left: 0;
            max-width: 100vw;
            overflow-x: hidden;
        }
    }

    @media (min-width: 429px) and (max-width: 768px) {
        .logo-wrapper {
            gap: 8px;
            padding: 6px 8px;
        }

        .logo-image {
            height: 45px; /* نفس حجم اللوجو للتناسق */
        }

        .logo-content {
            font-size: 14px;
        }

        .title-text {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .slogan-text {
            font-size: 12px;
        }
    }
</style>
