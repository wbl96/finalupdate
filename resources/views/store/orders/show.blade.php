@extends('layouts.store.app')

@section('title', trans('users.store'))

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
                </tr>
            </table>
            {{-- order items --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ trans('orders.item') }}</th>
                        <th>{{ trans('users.supplier') }}</th>
                        <th>{{ trans('orders.qty') }}</th>
                        <th>{{ trans('orders.price') }}</th>
                        <th>{{ trans('orders.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $orderItems = $order->items;
                    @endphp
                    @foreach ($orderItems as $item)
                        @php
                            $product = $item->detailKey->detail->product;
                        @endphp
                        <tr>
                            <td>{{ $product->name_ar }}</td>
                            <td>{{ $item->supplier->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) . ' ' . trans('global.SAR') }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) . ' ' . trans('global.SAR') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- order payments info --}}
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="text-start">{{ trans('orders.total price') }}:</td>
                        <td>{{ $order->total_price . ' ' . trans('global.SAR') }}</td>
                    </tr>
                    {{-- <tr>
                        <td class="text-start">{{ trans('orders.amount paid') }}:</td>
                        <td>
                            {{ $invoice->paid_amount . ' ' . trans('global.SAR') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start">{{ trans('orders.amount remains') }}:</td>
                        <td> {{ $invoice->remaining_amount . ' ' . trans('global.SAR') }}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>

        <div class="row g-3 mb-3">
            @if ($order->payment_receipt && Storage::disk('public')->exists($order->payment_receipt))
                <div class="col-sm-12 col-lg-4">
                    <img src="{{ Storage::url($order->payment_receipt) }}" class="img-fluid" height="350">
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-center">
            <button class="btn btn-import btn-print btn-sm" data-print-id="oredr-info-print">
                <i class="bi bi-printer"></i>
                {{ trans('orders.print order') }}</button>
        </div>
    </div>
@endsection