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
        <h3>{{ trans('reports.report of', ['name' => trans('reports.payments')]) }}</h3>
        <div class="report-buttons mb-3">
            @php
                $currentType = request()->get('period', 'all');
            @endphp
            <a href="{{ route('supplier.reports.payments', ['period' => 'all']) }}" @class(['btn btn-success', 'active' => $currentType === 'all'])>شامل</a>
            <a href="{{ route('supplier.reports.payments', ['period' => 'daily']) }}" @class(['btn btn-success', 'active' => $currentType === 'daily'])>يومي</a>
            <a href="{{ route('supplier.reports.payments', ['period' => 'weekly']) }}"
                @class(['btn btn-success', 'active' => $currentType === 'weekly'])>أسبوعي</a>
            <a href="{{ route('supplier.reports.payments', ['period' => 'monthly']) }}" @class(['btn btn-success', 'active' => $currentType === 'monthly'])>شهري</a>
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
                        <th>المدفوع</th>
                        <th>تاريخ الدفع</th>
                        <th>اسم المتجر</th>
                        <th>المتبقي</th>
                        <th>طريقة الدفع</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ number_format($payment->payment_amount, 2) . ' ' . trans('global.SAR') }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</td>
                            <td>{{ $payment->invoice->store->name }}</td>
                            <td>{{ number_format($payment->remaining_amount, 2) . ' ' . trans('global.SAR') }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="1">
                            اجمالى المبلغ
                        </td>
                        <td colspan="1" class="text-center">
                            {{ number_format($payments->sum('payment_amount'), 2) }}
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
