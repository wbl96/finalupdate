@extends('layouts.store.app')

@section('title', trans('reports.the reports'))

@section('content')
    <div class="container">
        <h3>{{ trans('reports.report of', ['name' => trans('reports.payments')]) }}</h3>
        <div class="report-buttons mb-3">
            @php
                $currentType = request()->get('period', 'all');
            @endphp
            <a href="{{ route('store.reports.payments', ['period' => 'all']) }}" @class(['btn btn-success', 'active' => $currentType === 'all'])>شامل</a>
            <a href="{{ route('store.reports.payments', ['period' => 'daily']) }}" @class(['btn btn-success', 'active' => $currentType === 'daily'])>يومي</a>
            <a href="{{ route('store.reports.payments', ['period' => 'weekly']) }}" @class(['btn btn-success', 'active' => $currentType === 'weekly'])>أسبوعي</a>
            <a href="{{ route('store.reports.payments', ['period' => 'monthly']) }}" @class(['btn btn-success', 'active' => $currentType === 'monthly'])>شهري</a>
        </div>

        @if ($message)
            <div class="alert alert-danger mt-4">
                {{ $message }}
            </div>
        @else
            <div class="my-3">
                {{ $payments->onEachSide(2)->links() }}
            </div>
            <table class="table table-bordered mt-4" id="paymentsTable">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>المدفوع</th>
                        <th>تاريخ الدفع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id + 10000 }}</td>
                            <td>{{ number_format($payment->total_price, 2) . ' ' . trans('global.SAR') }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</td>
                            <td>{{ trans('orders.' . $payment->status) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="1">
                            اجمالى المبلغ
                        </td>
                        <td colspan="1" class="text-center">
                            {{ number_format($payments->sum('total_price'), 2) . ' ' . trans('global.SAR') }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="my-3">
                {{ $payments->onEachSide(2)->links() }}
            </div>
        @endif
    </div>
@endsection
