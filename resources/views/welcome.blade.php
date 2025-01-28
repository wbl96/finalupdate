@extends('layouts.store.app')

@section('title', trans('users.store'))

@section('content')
    <div class="container-fluid">
        <form action="{{ route('store.dashboard') }}" method="GET">
            <div class="search-container d-flex align-items-center">
                <input type="search" name="search" value="{{ old('search', request()->input('search')) }}"
                    class="form-control search-input mb-0" placeholder="ابحث هنا">
                <button type="submit" class="btn btn-primary search-button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <div @class([
            'row',
            'row-cols-xs-2 row-cols-md-3 row-cols-lg-4',
            'g-3' => $products->count() > 0,
            'justify-content-center',
        ])>
            @forelse ($products as $product)
                <div>
                    <div class="card product-card">
                        <div class="card-body">
                            <div class="product-image text-center">
                                @if ($product->image && Storage::disk('public')->exists($product->image))
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product" class="img-fluid">
                                @else
                                    <img src="{{ asset('img/image-file512.webp') }}" alt="Product" class="img-fluid">
                                @endif
                                <div class="action-icons">
                                    <button class="btn icon-btn" onclick="showProductModal('{{ $product->id }}')">
                                        <i class="bi bi-cart"></i>
                                    </button>
                                    {{-- <form action="{{ route('store.cart.add-item', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn icon-btn"><i class="bi bi-cart"></i></button>
                                        <input type="hidden" class="counter-value form-control text-center" name="quantity"
                                            placeholder="" aria-label="Example text with button addon"
                                            style="max-width: 75px" value="1" id="productCardQtyFast">
                                    </form> --}}
                                </div>
                            </div>
                            <div class="product-details mt-3">
                                <h6 class="mb-0 product-name">
                                    {{ $locale == 'ar' ? $product->name_ar : $product->name_en }}</h6>
                                </p>
                                <p class="mb-0 supplier-name" style="height: 42px; overflow: hidden;">
                                    {{ Str::substr($product->description, 0, 50) }}
                                    @if (strlen($product->description) > 50)
                                        ...
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none">
                    @include('store.product_modal', $product)
                </div>
            @empty
                <div class="col-8">
                    <div class="alert alert-danger">
                        {{ trans('products.no products founded') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="my-3">
        {{ $products->onEachSide(2)->links() }}
    </div>

    <div id="showProductModalContainer"></div>

    {{-- @include('store.product_modal') --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl ">
        </div>
    </div>

@endsection

@push('script')
    <script>
        function showProductModal(id) {
            $(`.modal-body .alert`).remove();
            $('.container-details-key').removeClass('border border-danger p-1')
            $('.error-msg-for-detail').addClass('d-none')
            $('.counter-value').removeClass('is-invalid')
            $('.error-msg-for-qty').addClass('d-none')
            var modal_content = $('#modal_content_' + id)
            $('#productCardQtyModal_' + id).val(1)
            $('#productModal .modal-dialog').html(modal_content)
            $('#productModal').modal('show').addClass('show')
        }

        function increaseQty(id) {
            var qtyInput = document.querySelector('#productCardQtyModal_' + id);
            let value = parseInt(qtyInput.value);
            let newValue = value + 1;
            qtyInput.value = newValue;
        }

        function decreaseQty(id) {
            var qtyInput = document.querySelector('#productCardQtyModal_' + id);
            let value = parseInt(qtyInput.value);
            if (value <= 1) return
            let newValue = value - 1;
            qtyInput.value = newValue;
        }

        function submitForm(formID) {
            $(`.modal-body`).append('<div class="alert alert-danger mx-auto mt-3 text-center" style="width: fit-content"><i class="bi bi-exclamation-triangle-fill"></i>&nbsp;برجاء تسجيل الدخول حتي تتمكن من شراء المنتجات!</div>');
        }
    </script>
@endpush