<div class="modal-content p-0" id="modal_content_{{ $product->id }}">
    <div class="justify-content-between modal-header" style="border-bottom: 1px solid #dee2e6;">
        <h5 class="modal-title">
            {{ $locale == 'ar' ? $product->name_ar : $product->name_en }}
        </h5>
        <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5 text-center">
                <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                    alt="Product" class="img-fluid" style="max-height: 200px">
            </div>
            <div class="col-md-7">

                <form id="form_{{ $product->id }}" action="{{ route('store.cart.add-item', $product) }}" method="POST"
                    class="h-100 d-flex flex-column justify-content-between">
                    <p>{{ $product->description }}</p>
                    {{-- <div class="d-flex align-items-center mb-3">
                        <span class="text-primary fs-4 ">{{ $product->price . ' ' . trans('global.SAR') }}</span>
                        <span class="text-danger fs-5 mx-2"> 20 ريال</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                            style="width: {{ ($product->max_order_qty / $product->qty) * 100 }}%;"
                            aria-valuenow="{{ $product->qty }}" aria-valuemin="0"
                            aria-valuemax="{{ $product->max_order_qty }}"></div>
                    </div>
                    <p class="text-muted fs-6">
                        {{ trans('products.available in stock', ['qty' => $product->qty]) }}
                    </p> --}}
                    @csrf
                    <div class="mb-3 py-1 d-flex flex-column">
                        <p><strong>{{ $product->detail?->name }}</strong></p>
                        <div class="mb-1 d-flex flex-wrap gap-1 container-details-key">
                            @foreach ($product->detail->keys ?? [] as $k_keys => $item)
                                <div class="">
                                    <input type="radio" class="btn-check ah-btn-check" value="{{ $item->id }}"
                                        name="detail_key_id" id="option{{ $k_keys }}{{ $product->id }}"
                                        autocomplete="off">
                                    <label class="btn btn-outline-success"
                                        for="option{{ $k_keys }}{{ $product->id }}">{{ $item->key }}</label>
                                </div>
                            @endforeach
                        </div>
                        <strong class="text-danger error-msg-for-detail">
                            فضلا أختر إحدى الخصائص
                        </strong>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="counter-control input-group" @style(['max-width: 200px;'])>
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="increaseBtn"
                                onclick="increaseQty({{ $product->id }})"><i class="bi bi-plus"></i></button>
                            <input type="number" class="counter-value form-control text-center" name="qty"
                                min="1" value="1" id="productCardQtyModal_{{ $product->id }}"
                                onkeyup="getTotalFromInput(this);">
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="decreaseBtn"
                                onclick="decreaseQty({{ $product->id }})"><i class="bi bi-dash"></i></button>
                        </div>
                        <strong class="text-danger error-msg-for-qty">
                            يجب تحديد الكمية المطلوبة
                        </strong>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-center border-0">
        <button type="submit" class="btn btn-import" onclick="submitForm({{ $product->id }})">
            <i class="bi bi-basket3-fill mx-1"></i>
            <span>اضافة إلى السلة</span>
        </button>
    </div>
</div>
