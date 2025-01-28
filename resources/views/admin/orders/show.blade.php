@extends('layouts.global.app')

@section('title', trans('orders.the orders'))

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@php
    $invoice = $order->invoice;
@endphp

@section('content')
    <div class="container mt-4">
        {{-- order info --}}
        <div class="order-info" id="oredr-info-print">
            {{-- order info --}}
            <table class="table table-borderless align-middle">
                <tr>
                    <td colspan="3">
                        <strong>{{ trans('orders.the orders') . ' - ' . trans('orders.order num', ['num' => $order->id + 10000]) }}</strong>
                    </td>
                </tr>
                <tr>
                    {{-- <td>
                        <strong>{{ trans('users.supplier name') }}:&nbsp;</strong>
                        <span>{{ $order->supplier->name }}</span>
                    </td> --}}
                    <td class="text-end">
                        <strong>{{ trans('orders.created at') }}:&nbsp;</strong>
                        <span>{{ $order->created_at }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ trans('orders.order status') }}:&nbsp;</strong>
                        <span>{{ trans('orders.' . $order->status) }}</span>
                    </td>
                    <td class="text-end">
                        <strong>{{ trans('orders.payment status') }}:&nbsp;</strong>
                        <span>{{ trans('orders.' . $order->payment_status) }}</span>
                    </td>
                </tr>
            </table>
            {{-- order items --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('orders.item') }}</th>
                        <th>{{ trans('users.the supplier') }}</th>
                        <th>{{ trans('orders.qty') }}</th>
                        <th>{{ trans('orders.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $orderItems = $order->items;
                    @endphp
                    @foreach ($orderItems as $item)
                        <tr>
                            <td>{{ $locale == 'ar' ? $item->product->name_ar : $item->product->name_en }}</td>
                            <td>{{ $item->supplier->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2).' '.trans('global.SAR') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- order payments info --}}
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="text-start">{{ trans('orders.total price') }}:</td>
                        <td>{{ number_format($order->total_price, 2).' '.trans('global.SAR') }}</td>
                    </tr>
                    {{-- <tr>
                        <td class="text-start">{{ trans('orders.amount paid') }}:</td>
                        <td>
                            {{ $invoice->paid_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start">{{ trans('orders.amount remains') }}:</td>
                        <td> {{ $invoice->remaining_amount }}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        @if ($order->payment_receipt && Storage::disk('public')->exists($order->payment_receipt))
            <div class="position-relative" @style(['width: 450px; height: 450px; margin:auto auto 3rem auto;'])>
                <h5 class="h5">ايصال الدفع</h5>
                <img src="{{ Storage::url($order->payment_receipt) }}" class="img-fluid h-100">
            </div>
        @endif

        {{-- <p class="text-primary d-inline-flex">
            {{ trans('orders.bill payment archive') }} :
            {{ $invoice->id + 10000 }} -
            {{ trans('orders.' . $invoice->status) }}
        </p>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>{{ trans('orders.amount paid') }}</th>
                    <th>{{ trans('orders.amount remains') }}</th>
                    <th>{{ trans('orders.paid date') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $payments = $invoice->payments;
                @endphp
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_amount }}</td>
                        <td>{{ $payment->remaining_amount }}</td>
                        <td>{{ $payment->payment_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        <div class="position-relative hstack gap-3 justify-content-center">
            <button class="btn btn-import btn-print btn-sm" data-print-id="oredr-info-print">
                <i class="bi bi-printer"></i>
                {{ trans('orders.print order') }}</button>

            <form action="{{ route('admin.orders.approve', $order) }}" method="post">
                @csrf
                <button class="btn btn-import btn-sm"><i class="bi bi-check-lg"></i>
                    {{ trans('orders.approve') }}
                </button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">إجمالي الطلب</span>
                        <span class="info-box-number">{{ number_format($orderDetails['total_price'], 2) }} ريال</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">المبلغ المستخدم من المحفظة</span>
                        <span class="info-box-number">{{ number_format($orderDetails['wallet_amount'], 2) }} ريال</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-university"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">المبلغ المطلوب تحويله</span>
                        <span class="info-box-number">{{ number_format($orderDetails['bank_amount'], 2) }} ريال</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">رصيد المحفظة الحالي</span>
                        <span class="info-box-number">{{ number_format($orderDetails['store_wallet_balance'], 2) }} ريال</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
