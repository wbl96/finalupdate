@extends('layouts.store.app')

@section('title', trans('reports.the reports'))

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-4 mb-4">
                <div class="card bg-white shadow-sm border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ trans('reports.report of', ['name' => trans('reports.purchases')]) }}</h5>
                        <div class="icon-container">
                            <i class="bi bi-cart-fill icon"></i>
                        </div>
                    </div>
                    <a href="{{ route('store.reports.purchases') }}" class="stretched-link"></a>
                </div>
            </div>
            <div class="col-12 col-md-4 mb-4">
                <div class="card bg-white shadow-sm border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ trans('reports.report of', ['name' => trans('reports.payments')]) }}</h5>
                        <div class="icon-container">
                            <i class="bi bi-calculator-fill icon"></i>
                        </div>
                    </div>
                    <a href="{{ route('store.reports.payments') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
@endsection
