<!DOCTYPE html>
<html lang="{{ session('lang') ?? 'ar' }}" dir="{{ session('lang') == 'en' ? 'ltr' : 'rtl' }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.2.0-web/css/all.min.css') }}" />
    {{-- page title --}}
    <title>@yield('title', trans('auth.login')) - {{ trans('global.' . config('app.name', 'subscription system')) }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    @stack('css')
    {{-- page icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_n.svg') }}">
</head>

<body>
    @yield('content')

    <div @class(['position-absolute', 'bottom-0']) @style(['z-index: -1'])>
        <img src="{{ asset('img/sketch.png') }}" class="img-fluid">
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector("#togglePassword");
            const passwordField = document.querySelector("#password");

            if (togglePassword)
                togglePassword.addEventListener("click", function() {
                    const type =
                        passwordField.getAttribute("type") === "password" ? "text" : "password";
                    passwordField.setAttribute("type", type);

                    this.classList.toggle("bi-eye");
                    this.classList.toggle("bi-eye-slash");
                });
        });
    </script>

    <script src="{{ asset('assets/js/app_2.js') }}"></script>
    @stack('script')
</body>
