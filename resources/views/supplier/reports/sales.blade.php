@extends('layouts.global.app')

@section('title', trans('reports.the reports'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@section('content')
    <div class="container">
        <h3>{{ trans('reports.report of', ['name' => trans('reports.sales')]) }}</h3>
        <div class="report-buttons mb-3">
            @php
                $currentType = request()->get('period', 'all');
            @endphp
            <a href="{{ route('supplier.reports.sales', ['period' => 'all']) }}" @class(['btn btn-success', 'active' => $currentType === 'all'])>شامل</a>
            <a href="{{ route('supplier.reports.sales', ['period' => 'daily']) }}" @class(['btn btn-success', 'active' => $currentType === 'daily'])>يومي</a>
            <a href="{{ route('supplier.reports.sales', ['period' => 'weekly']) }}" @class(['btn btn-success', 'active' => $currentType === 'weekly'])>أسبوعي</a>
            <a href="{{ route('supplier.reports.sales', ['period' => 'monthly']) }}" @class(['btn btn-success', 'active' => $currentType === 'monthly'])>شهري</a>
        </div>

        @if ($message)
            <div class="alert alert-danger mt-4">
                {{ $message }}
            </div>
        @else
            <div class="my-3">
                {{ $orders->onEachSide(2)->links() }}
            </div>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>المتجر</th>
                        <th>رقم الطلب</th>
                        <th>اجمالي الطلب</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>تاريح الطلب</th>
                        <th>تاريخ الاستحقاق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $invoice = $order->invoice;
                        @endphp
                        <tr>
                            <td>{{ $order->store->name }}</td>
                            <td>{{ $order->id + 10000 }}</td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td>{{ number_format($invoice->remaining_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->copy()->addDays(35)->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-3">
                {{ $orders->onEachSide(2)->links() }}
            </div>
        @endif
    </div>
@endsection
