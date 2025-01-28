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
        @else
            <img src="{{ asset('img/logo_n.svg') }}" alt="" height="50">
        @endauth
    </div>

    <div class="d-flex align-items-center me-auto ms-0">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-dark"></span>
        </button>

        <div class="collapse navbar-collapse  navbar-collapse-left" id="navbarSupportedContent">
            <ul class="navbar-nav ms-2 mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a href="" class="nav-link text-dark">تسجيل جديد</a>
                    </li>
                @else
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

                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                    @csrf
                                    {{-- {{ csrf_token() }} --}}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
