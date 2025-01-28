@extends('layouts.global.app')

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-4 mb-4">
                <div class="card bg-white shadow-sm border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ trans('dashboard.#of providers') }}</h5>
                        <p class="card-text">{{ $providers }}</p>
                        <div class="icon-container">
                            <i class="bi bi-calculator-fill icon"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.list', 'provider') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4">
                <div class="card shadow-sm border-dark highlight h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ trans('dashboard.#of stores') }}</h5>
                        <p class="card-text">{{ $stores }}</p>
                        <div class="icon-container">
                            <i class="bi bi-cart-fill icon"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.list', 'store') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4">
                <div class="card bg-white shadow-sm border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ trans('dashboard.#of suppliers') }}</h5>
                        <p class="card-text">{{ $suppliers }}</p>
                        <div class="icon-container">
                            <i class="bi bi-calculator-fill icon"></i>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.list', 'supplier') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
@endsection
