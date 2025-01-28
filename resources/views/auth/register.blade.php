@extends('layouts.auth.auth')

@section('content')
    {{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

    <div class="login-form text-center mt-5" style="min-height: 450px;">
        <div class="login-logo mb-4">
            <img src="{{ asset('img/logo_n.svg') }}" alt="Logo" class="img-fluid" />
        </div>

        <div class="Heading mb-4">
            <h3 class="mb-2">{{ trans('auth.sign up') }}</h3>
            <p class="mb-4">{{ trans('auth.register note') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3 position-relative">
                <input type="text" @class([
                    'form-control',
                    'py-2',
                    'is-invalid' => $errors->has('name') || $errors->has('loginFailed'),
                ]) placeholder="{{ trans('global.name') }}" id="name"
                    name="name" value="{{ old('name') }}" @required(true) />
                <i class="bi bi-person icon"></i>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="email" @class([
                    'form-control',
                    'py-2',
                    'is-invalid' => $errors->has('email') || $errors->has('loginFailed'),
                ]) placeholder="{{ trans('global.email') }}" id="email"
                    name="email" value="{{ old('email') }}" @required(true) />
                <i class="bi bi-envelope icon"></i>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="password" @class([
                    'form-control',
                    'py-2',
                    'is-invalid' => $errors->has('password') || $errors->has('loginFailed'),
                ]) placeholder="{{ trans('global.password') }}"
                    id="password" name="password" value="{{ old('password') }}" @required(true) />
                <i class="bi bi-lock icon"></i>
                <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="password" @class([
                    'form-control',
                    'py-2',
                    'is-invalid' =>
                        $errors->has('password_confirmation') || $errors->has('loginFailed'),
                ])
                    placeholder="{{ trans('global.password confirmation') }}" id="password_confirmation"
                    name="password_confirmation" value="{{ old('password_confirmation') }}" @required(true) />
                <i class="bi bi-lock icon"></i>
                <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <input type="tel" @class([
                    'form-control',
                    'py-2',
                    'is-invalid' => $errors->has('mobile') || $errors->has('loginFailed'),
                ]) placeholder="{{ trans('global.mobile') }}" id="mobile"
                    name="mobile" value="{{ old('mobile') }}" @required(true) />
                <i class="bi bi-telephone icon"></i>
                @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-import w-100 py-2">
                {{ trans('auth.login') }}
            </button>
            <hr class="hr">
            <div @class([
                'row mb-3',
                'text-end' => $locale == 'ar',
                'text-start' => $locale == 'en',
            ])>
                <div class="col-sm-12 col-md">
                    <a href="{{ route('login') }}">
                        {{ trans('auth.login') }}
                    </a>
                </div>
                <div class="col-sm-12 col-md">
                    <a href="{{ route('welcome') }}">
                        {{ trans('global.home') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
