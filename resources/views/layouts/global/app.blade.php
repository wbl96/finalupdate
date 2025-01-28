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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('css')
    {{-- page icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_n.svg') }}">
    <style>
        .table thead *:not(input) {
            background-color: #6c927f !important;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="d-flex bg-light" id="wrapper">
        @auth
            @yield('sidebar')
        @endauth

        {{-- page content wrapper --}}
        <div id="page-content-wrapper" style="min-height:100vh">
            @auth
                {{-- top bar --}}
                @yield('topbar')
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

    <audio src="{{asset('assets/sound/level-up-191997.mp3')}}" controls id="audio" class="d-none"></audio>

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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");

        toggleButton.onclick = function() {
            el.classList.toggle("toggled");
        };
    </script>
    <script src="{{ asset('assets/js/app_2.js') }}"></script>
    @stack('script')

    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-messaging.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyB18bAU1e77zdI_X7BkCt7Jtd1M2oJyx40",
            authDomain: "wbl-web.firebaseapp.com",
            projectId: "wbl-web",
            storageBucket: "wbl-web.firebasestorage.app",
            messagingSenderId: "188305287604",
            appId: "1:188305287604:web:2e847ed16e7e3c0803aae1",
            measurementId: "G-PPR6NRQL39"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging();

        navigator.serviceWorker.register("{{ asset('assets/js/serviceWorker.js') }}").then(registration => {
            getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: 'BDQtIF6bQywLF1fYa0afK35KbmbgxQ-Pa8-x_UYxyXXLEgJfH5XjP35vgKZ5HFKVVJFU_gc8e6mmLxD5Mwf1KWA'
            }).then((currentToken) => {
                if (currentToken) {
                    // Send the token to your server and update the UI if necessary
                    updateFCM(currentToken)
                } else {
                    // Show permission request UI
                    console.log('No registration token available. Request permission to generate one.');
                    // ...
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });
        })

    </script>
    <script>
        function updateFCM(fcm_token){
            if ('{{ auth("supplier")->user() }}') {
                url = "{{ route('supplier.updateFCM') }}"
            } else if('{{ auth("admin")->user() }}') {
                url = "{{ route('admin.updateFCM') }}"
            } else {
                return false
            }
            $.ajax({
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                url: url,
                type: 'post',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'fcm_token': fcm_token,
                },
                dataType: 'json'
            });
        }
    </script>
</body>

</html>
