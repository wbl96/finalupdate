@extends('layouts.store.app')

@section('title', trans('reports.the reports'))

@section('content')
    <div class="container">
        <h3>{{ trans('reports.report of', ['name' => trans('reports.purchases')]) }}</h3>
        <div class="report-buttons mb-3">
            @php
                $currentType = request()->get('period', 'all');
            @endphp
            <a href="{{ route('store.reports.purchases', ['period' => 'all']) }}" @class(['btn btn-success', 'active' => $currentType === 'all'])>شامل</a>
            <a href="{{ route('store.reports.purchases', ['period' => 'daily']) }}" @class(['btn btn-success', 'active' => $currentType === 'daily'])>يومي</a>
            <a href="{{ route('store.reports.purchases', ['period' => 'weekly']) }}" @class(['btn btn-success', 'active' => $currentType === 'weekly'])>أسبوعي</a>
            <a href="{{ route('store.reports.purchases', ['period' => 'monthly']) }}" @class(['btn btn-success', 'active' => $currentType === 'monthly'])>شهري</a>
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
                        <th>رقم الطلب</th>
                        <th>اجمالي المبلغ</th>
                        <th>الحالة</th>
                        <th>تاريح الطلب</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id + 10000 }}</td>
                            <td>{{ number_format($order->total_price, 2) . ' ' . trans('global.SAR') }}</td>
                            <td>{{ trans('orders.' . $order->status) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
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
