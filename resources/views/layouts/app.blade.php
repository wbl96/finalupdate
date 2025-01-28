<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
        }
        
        .brand-title {
            color: #0d6efd;  /* Bootstrap primary color */
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 2px;
        }
        
        .brand-slogan {
            color: #6c757d;  /* Bootstrap muted color */
            font-size: 12px;
            font-weight: 400;
        }
        
        @media (max-width: 768px) {
            .brand-title {
                font-size: 20px;
            }
            
            .brand-slogan {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div style="position: fixed; top: 0; right: 0; background: red; color: white; padding: 5px; z-index: 9999;">
            Test Overlay
        </div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="brand-wrapper">
                        <img src="{{ asset('assets/images/logo.png') }}" 
                             alt="وبل" 
                             height="40"
                             onerror="this.style.display='none'"  {{-- This will hide the image if it fails to load --}}
                        >
                        <div class="brand-text">
                            <span class="brand-title">وبل</span>
                            <span class="brand-slogan">طريقك الأذكى لسوق الجملة</span>
                        </div>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
