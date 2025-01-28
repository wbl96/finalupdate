@extends('layouts.global.app')

@section('title', trans('orders.rfq requests'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('supplier.orders.submit-quote', $cart) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3 accordion" id="accordionPanelsStayOpenExample">
                        @foreach ($cart->items as $key => $item)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapse{{ $key }}" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapse{{ $key }}">
                                        {{ $item->detailKey->detail->product->name }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse{{ $key }}"
                                    class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="hstack gap-3">
                                            <p>
                                                <span class="fw-bold">الخاصية: </span>
                                                <span>{{ $item->detailKey->key }}</span>
                                            </p>
                                            <p>
                                                <span class="fw-bold">الكمية المطلوبة: </span>
                                                <span>{{ $item->qty }}</span>
                                            </p>
                                        </div>

                                        <div class="row row-cols-sm-1 row-cols-md-2">
                                            <input type="hidden" name="cart_item_id[]" value="{{ $item->id }}">
                                            <div class="mb-3">
                                                <label for="qty" class="form-label">الكمية المتاحة</label>
                                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('qty')]) name="cart_item_qty[]"
                                                    id="qty" value="{{ old('qty') }}" @required(true)>
                                                @error('qty')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="proposed_price" class="form-label">السعر المقترح</label>
                                                <input type="text" @class([
                                                    'form-control',
                                                    'is-invalid' => $errors->has('proposed_price'),
                                                ])
                                                    name="cart_item_proposed_price[]" id="proposed_price"
                                                    value="{{ old('proposed_price') }}" @required(true)>
                                                @error('proposed_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="hstack gap-3 justify-content-center">
                        <button type="submit"
                            class="btn btn-primary p-3 text-white ">{{ trans('orders.submit quote') }}</button>
                    </div>
                </form>

                @php
                    $requests = Auth::user()
                        ->rfqRequests()
                        ->whereIn('cart_item_id', $cart->items->pluck('id')->toArray())
                        ->get();
                @endphp

                <div class="card border">
                    <div class="section-header">
                        <h3 class="h3">العروض التي قدمتها</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصنف</th>
                                    <th>الكمية المتاحة</th>
                                    <th>السعر المقترح</th>
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $key => $rfq)
                                    <tr>
                                        @php
                                            $detail = $rfq->cartItem->detailKey;
                                        @endphp
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $detail->detail->product->name_ar .' - '. $detail->key }}</td>
                                        <td>{{ $rfq->qty }}</td>
                                        <td>{{ number_format($rfq->proposed_price, 2) . ' ' . trans('global.SAR') }}</td>
                                        {{-- <td></td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
