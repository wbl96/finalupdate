<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="text-dark text-end">{{ $locale == 'ar' ? $product->name_ar : $product->name_en }}
                        </h2>
                        <hr>
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

                        <form action="{{ route('store.cart.add-item', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3 row row-cols-sm-2 row-cols-md-4 row-cols-lg-6 g-3">
                                @foreach ($product->detail->keys as $item)
                                    <div class="form-check border p-2 m-1">
                                        <input class="form-check-input mx-0" type="radio" name="detail_key_id"
                                            id="key_{{ $loop->index }}" value="{{ $item->id }}">
                                        <label class="form-check-label" for="key_{{ $loop->index }}">
                                            {{ $item->key }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3 row row-cols-sm-2 justify-content-center align-items-center mt-5 g-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-import"><i
                                            class="bi bi-basket3-fill mx-1"></i> اضافة إلى
                                        السلة</button>
                                </div>
                                {{-- <div class="col">
                                    <button class="btn btn-primary"
                                        id="total-price">{{ trans('products.total price', ['price' => $product->price]) . ' ' . trans('global.SAR') }}</button>
                                </div> --}}
                                <div class="counter-control input-group mb-3">
                                    <button class="btn btn-outline-secondary" type="button" id="increaseBtn"
                                        onclick="increaseQty(this)"><i class="bi bi-plus"></i></button>
                                    <input type="number" class="counter-value form-control text-center" name="qty"
                                        placeholder="" aria-label="Example text with button addon"
                                        style="max-width: 75px" value="1" id="productCardQtyModal"
                                        onkeyup="getTotalFromInput(this);">
                                    <button class="btn btn-outline-secondary" type="button" id="decreaseBtn"
                                        onclick="decreaseQty(this)"><i class="bi bi-dash"></i></button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="col-md-5 text-center">
                        <img src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                            alt="Product" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    function increaseQty() {
        var qtyInput = document.querySelector('#productCardQtyModal');
        let value = parseInt(qtyInput.value);
        let newValue = value + 1;
        qtyInput.value = newValue;
    }

    function decreaseQty() {
        var qtyInput = document.querySelector('#productCardQtyModal');
        let value = parseInt(qtyInput.value);
        if (value <= 0) return
        let newValue = value - 1;
        qtyInput.value = newValue;
    }
</script>
