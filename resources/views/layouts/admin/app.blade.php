<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- page title --}}
    <title>@yield('title', trans('global.dashboard')) - {{ trans('global.' . config('app.name', 'subscription system')) }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.2.0-web/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    @stack('css')
    {{-- page icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_n.svg') }}">
</head>

<body>
    <div class="d-flex bg-light" id="wrapper">
        @auth
            @include('layouts.admin.sidebare')
        @endauth

        {{-- page content wrapper --}}
        <div id="page-content-wrapper" style="min-height:100vh">
            @auth
                {{-- top bar --}}
                @include('layouts.admin.topbar')
            @endauth
            {{-- page container --}}
            <div class="my-3 container px-4">
                {{-- error messages --}}
                @include('layouts.global.messages')
                {{-- page content --}}
                @yield('content')
            </div>
        </div>
    </div>

    {{-- include loading indicator --}}
    @include('layouts.global.loading_indicatore')
    {{-- include modal delete if user authenticated --}}
    @includeWhen(Auth::check(), 'layouts.global.modal_delete')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
    <script src="{{ asset('assets/js/app_2.js') }}"></script>
    @stack('script')
</body>

</html>
