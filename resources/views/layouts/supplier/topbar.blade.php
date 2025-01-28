<nav class="navbar navbar-expand-lg px-4 bg-light shadow-sm" style="height: 55px">
    <div class="d-flex align-items-center">
        @auth
            <div class="logo bg-light">
                <img src="{{ asset('img/logo_n.svg') }}" height="35" alt="">
            </div>
            <span>
                <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                &nbsp;
            </span>
            {{-- <h2 class="fs-4 m-0">
                @yield('title', trans('global.dashboard')) - @yield('subTitle')
            </h2> --}}
        @else
            <img src="{{ asset('img/logo_n.svg') }}" alt="" height="50">
            {{-- <h2 class="fs-4 m-0 ms-3">
                {{ config('app.name', 'برنامج مد') }}
            </h2> --}}
        @endauth
    </div>

    <div class="d-flex align-items-center me-auto ms-0">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-dark"></span>
        </button>

        <div class="collapse navbar-collapse navbar-collapse-left" id="navbarSupportedContent">
            <ul class="navbar-nav ms-2 mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a href="" class="nav-link text-dark">تسجيل جديد</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link second-text fw-bold" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell me-2"></i>
                        </a>
                        <ul @class(['p-3', 'dropdown-menu', 'text-end']) @style(['ma-width: 350px', 'max-height:350px', 'overflow:auto'])>
                            @forelse (Auth::user()->notifications as $notification)
                                <li>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="h6 fw-bold">
                                                {{ trans($notification->data['title'], isset($notification->data['parameters']['title']) ? $notification->data['parameters']['title'] : []) }}
                                            </h6>
                                            @if (isset($notification->data['body']))
                                                <p class="lead">
                                                    {{ trans($notification->data['body'], isset($notification->data['parameters']['body']) ? $notification->data['parameters']['body'] : []) }}
                                                </p>
                                            @endif
                                        </div>
                                        <div @style(['max-width: 100px'])>
                                            @unless ($notification->read_at)
                                                <i class="bi bi-circle-fill text-primary" @style(['font-size:14px'])></i>
                                            @else
                                                <i class="bi bi-check-all" @style(['font-size:14px'])></i>
                                            @endunless
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li>{{ trans('global.not found') }}</li>
                            @endforelse
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle second-text fw-bold" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-start text-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    @style(['cursor:pointer'])>
                                    <i class="bi bi-power"></i>
                                    {{ trans('auth.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('supplier.logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
