@extends('layouts.store.app')

@section('title', trans('users.store'))

@section('content')
    <div class="container my-4">
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <form action="{{ route('store.orders.confirm') }}" method="POST" id="confirmOrder">
                            @csrf
                        </form>
                        @if ($cart && $cart->isRfqRequestsPending())
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>صورة المنتج</th>
                                        <th>المنتج</th>
                                        <th>الكمية</th>
                                        <th>الخاصية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($cart)
                                        @forelse ($cart->items as $key => $item)
                                            @php
                                                $product = $item->detailKey->detail->product;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <form action="{{ route('store.cart.remove-item', $item->id) }}"
                                                        id="removeItem_{{ $product->id }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" form="removeItem_{{ $product->id }}"
                                                            class="btn btn-link text-danger">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                                                        alt="Product" class="product-img">
                                                </td>
                                                <td>
                                                    <span>{{ $locale == 'ar' ? $product->name_ar : $product->name_en }}</span>
                                                </td>
                                                <td>
                                                    <div class="quantity-control">
                                                        <button type="button" class="btn-plus"
                                                            onclick="increaseQty('productCardQtyHidden_{{ $item->id }}', 'productCardQty_{{ $item->id }}')">+</button>
                                                        <span class="quantity-value"
                                                            id="productCardQty_{{ $item->id }}">{{ $item->qty }}</span>
                                                        <button type="button" class="btn-minus"
                                                            onclick="decreaseQty('productCardQtyHidden_{{ $item->id }}', 'productCardQty_{{ $item->id }}')">−</button>

                                                        <input type="hidden" class="counter-value form-control text-center"
                                                            name="qty[]" aria-label="Example text with button addon"
                                                            style="max-width: 75px" value="{{ $item->qty }}"
                                                            id="productCardQtyHidden_{{ $item->id }}"
                                                            form="confirmOrder">
                                                        <input type="hidden" class="counter-value form-control text-center"
                                                            name="product_id[]" aria-label="Example text with button addon"
                                                            style="max-width: 75px" value="{{ $product->id }}"
                                                            form="confirmOrder">
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $item->detailKey->key }}
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        @endif

                        @if ($cart && $cart->isRfqRequestsOpen())
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach ($cart->items as $key => $item)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapse{{ $key }}"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapse{{ $key }}">
                                                {{ $item->detailKey->detail->product->name_ar }}
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

                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>المتجر</th>
                                                            <th>الكمية المتاحة</th>
                                                            <th>السعر المقترح</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->rfqRequests as $rfq)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" name="rfq_id[]"
                                                                        value="{{ $rfq->id }}" form="confirmOrder">
                                                                    {{ $rfq->supplier->name }}
                                                                </td>
                                                                <td>{{ $rfq->qty }}</td>
                                                                <td>{{ number_format($rfq->proposed_price, 2) . ' ' . trans('global.SAR') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($cart && $cart->isRfqRequestsClose())
                <div class="col-lg-8">
                    <h2 class="save-cardtxt">تفاصيل التحويل المصرفي</h2>
                    <div class="card-radio-list cmn-radio-custom">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 mb-2 mb-md-2 pb-1 pb-md-1">
                                <div class="bank-name-div">
                                    <p>الاسم الكامل للمستفيد:</p>
                                    <h6>مؤسسة غيار السريع للتجارة</h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 mb-2 mb-md-2 pb-1 pb-md-1">
                                <div class="bank-name-div">
                                    <p>رقم حساب المستفيد:</p>
                                    <h6>68299770000000</h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 mb-2 mb-md-2 pb-1 pb-md-1">
                                <div class="bank-name-div">
                                    <p>الاسم المصرفي للمستفيد:</p>
                                    <h6>مصرف الانماء</h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 mb-2 mb-md-2 pb-1 pb-md-1">
                                <div class="bank-name-div">
                                    <p>رقم IBAN الخاص بالمستفيد:</p>
                                    <h6>SA2205000068299770000000</h6>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 mb-2 mb-md-2 pb-1 pb-md-1">
                                <div class="bank-name-div">
                                    <p>رقم توجيه المستفيد:</p>
                                    <h6>INMASARI</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body p-0">
                        @if ($cart && $cart->isRfqRequestsPending())
                            <form action="{{ route('store.orders.request-for-quotations', $cart) }}" method="post">
                                @csrf
                                <button type="submit"
                                    class="btn btn-primary p-3 text-white w-100">{{ trans('orders.request for quotations') }}</button>
                            </form>
                        @elseif ($cart && $cart->isRfqRequestsOpen())
                            <button type="submit" form="confirmOrder"
                                class="btn btn-primary p-3 text-white w-100">{{ trans('orders.confirm') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        function increaseQty(targetId1, targetId2) {
            var qtyInput1 = document.querySelector(`#${targetId1}`);
            var qtyInput2 = document.querySelector(`#${targetId2}`);

            let value = parseInt(qtyInput1.value);
            let newValue = value + 1;
            qtyInput1.value = newValue;
            qtyInput2.textContent = newValue;
        }

        function decreaseQty(targetId1, targetId2) {
            var qtyInput1 = document.querySelector(`#${targetId1}`);
            var qtyInput2 = document.querySelector(`#${targetId2}`);

            let value = parseInt(qtyInput1.value);
            if (value <= 0) return
            let newValue = value - 1;
            qtyInput1.value = newValue;
            qtyInput2.textContent = newValue;
        }
    </script>
@endpush
