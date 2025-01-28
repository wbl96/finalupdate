@extends('layouts.auth.auth')

@section('title', trans('auth.login'))

@section('content')
    <div class="login-form">
        <div class="login-logo">
            <img src="{{ asset('img/logo_n.svg') }}" alt="Logo" />
        </div>

        <div class="Heading mb-3">
            <h2 class="text-center mb-2">{{ trans('global.welcome to you') }}</h2>
            <p class="text-center mb-0">{{ trans('auth.login to your account') }}</p>
            <p class="text-center">{{ trans('admins.the admins') }}</p>
        </div>

        @error('loginFailed')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
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

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check form-check-inline form-check-reverse m-0">
                        <label class="form-check-label" for="remember">
                            {{ trans('auth.remember me') }}
                        </label>
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                    </div>
                </div>
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
                        {{ trans('auth.choose account type') }}
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
