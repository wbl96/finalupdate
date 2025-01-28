@extends('layouts.global.app')

@section('title', trans('reports.the reports'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@push('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css"
        rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <h3>{{ trans('reports.report of', ['name' => trans('reports.products')]) }}</h3>
        <div class="report-buttons mb-3">
            @php
                $currentType = request()->get('period', 'all');
            @endphp
            <a href="{{ route('supplier.reports.products', ['period' => 'all']) }}" @class(['btn btn-success', 'active' => $currentType === 'all'])>شامل</a>
            <a href="{{ route('supplier.reports.products', ['period' => 'daily']) }}" @class(['btn btn-success', 'active' => $currentType === 'daily'])>يومي</a>
            <a href="{{ route('supplier.reports.products', ['period' => 'weekly']) }}"
                @class(['btn btn-success', 'active' => $currentType === 'weekly'])>أسبوعي</a>
            <a href="{{ route('supplier.reports.products', ['period' => 'monthly']) }}" @class(['btn btn-success', 'active' => $currentType === 'monthly'])>شهري</a>
        </div>

        @if ($message)
            <div class="alert alert-danger mt-4">
                {{ $message }}
            </div>
        @else
            <div class="my-3">
                {{ $products->onEachSide(2)->links() }}
            </div>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>إجمالي الكمية</th>
                        <th>المباع</th>
                        <th>المتوفر</th>
                        <th>سعر البيع</th>
                        <th>إجمالي المبيعات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name_ar }}</td>
                            <td>{{ $product->qty }}</td>
                            <td>{{ $product->total_sold }}</td>
                            <td>{{ $product->available_quantity }}</td>
                            <td>{{ $product->price . ' ' . trans('global.SAR') }}</td>
                            <td>{{ $product->total_sold * $product->price . ' ' . trans('global.SAR') }}</td>
                        </tr>
                    @endforeach
                    <tr style="border-top: 2px solid">
                        <td colspan="1">
                            اجمالى
                        </td>
                        <td>{{ $products->sum('qty') }}</td>
                        <td>{{ $products->sum('total_sold') }}</td>
                        <td>{{ $products->sum('available_quantity') }}</td>
                        <td>{{ number_format($products->sum('price'), 2) . ' ' . trans('global.SAR') }}</td>
                        </td>
                        <td>{{ $products->sum(fn($product) => $product->total_sold * $product->price) . ' ' . trans('global.SAR') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="my-3">
                {{ $products->onEachSide(2)->links() }}
            </div>
        @endif
    </div>
@endsection
