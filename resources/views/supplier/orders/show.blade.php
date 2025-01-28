@extends('layouts.global.app')

@section('title', trans('orders.the orders'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

{{-- @php
    $invoice = $orderItem->invoice;
@endphp --}}

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
                    <td>
                        <strong>{{ trans('users.store name') }}:&nbsp;</strong>
                        <span>{{ $order->store->name }}</span>
                    </td>
                    <td class="text-end">
                        <strong>{{ trans('orders.created at') }}:&nbsp;</strong>
                        <span>{{ $order->created_at }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{ trans('orders.order status') }}:&nbsp;</strong>
                        <span>{{ trans('orders.' . $order->status) }}</span>

                        {{-- <button class="btn btn-import btn-action btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editStatusModal">{{ trans('global.edit') }}</button> --}}
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
                            <td>{{ $item->supplier_id == Auth::id() ? 'أنت' : $item->supplier->name }}</td>
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
                        <td class="text-start" colspan="3">{{ trans('orders.total price') }}:</td>
                        <td colspan="1">{{ $order->total_price . ' ' . trans('global.SAR') }}</td>
                    </tr>
                    {{-- 
                    <tr>
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

            @if ($order->payment_receipt && Storage::disk('public')->exists($order->payment_receipt))
                <div class="row g-3 mb-3 justify-content-center">
                    <div class="col-sm-12 col-lg-4">
                        <img src="{{ Storage::url($order->payment_receipt) }}" class="img-fluid" height="350">
                    </div>
                </div>
            @endif
        </div>

        {{-- <p class="text-primary d-inline-flex">
            {{ trans('orders.bill payment archive') }} :
            {{ $invoice->id + 10000 }} -
            {{ trans('orders.' . $invoice->status) }}
        </p>
        @if ($invoice->remaining_amount > 0)
            <button class="btn btn-import btn-add btn-sm mx-2" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                {{ trans('orders.add batch') }}
            </button>
            <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="addPaymentLabel">{{ trans('orders.add batch') }}</h5>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('supplier.orders.add-payment', $orderItem) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="remaining_amount"
                                        class="form-label">{{ trans('orders.amount remains') }}</label>
                                    <input type="text" class="form-control" id="remaining_amount" name="remaining_amount"
                                        value="{{ $invoice->remaining_amount }}" @disabled(true)>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_amount"
                                        class="form-label">{{ trans('orders.amount collected') }}</label>
                                    <input type="text" class="form-control" id="payment_amount" name="payment_amount">
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-import btn-action">حفظ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
        <div class="d-flex justify-content-center">
            <button class="btn btn-import btn-print btn-sm" data-print-id="oredr-info-print">
                <i class="bi bi-printer"></i>
                {{ trans('orders.print order') }}</button>
        </div>
    </div>

    {{-- <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="editStatusLabel">{{ trans('orders.edit order status') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('supplier.orders.update-status', $orderItem) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ trans('orders.order status') }}</label>
                            <select class="form-select" id="status" name="status">
                                <option value="pending" @selected($orderItem->status == 'pending')>{{ trans('orders.pending') }}</option>
                                <option value="new" @selected($orderItem->status == 'new')>{{ trans('orders.new') }}</option>
                                <option value="in progress" @selected($orderItem->status == 'in progress')>{{ trans('orders.in progress') }}
                                </option>
                                <option value="received" @selected($orderItem->status == 'received')>{{ trans('orders.received') }}
                                </option>
                                <option value="refunded" @selected($orderItem->status == 'refunded')>{{ trans('orders.refunded') }}
                                </option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-import btn-action">{{ trans('global.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}


@endsection
