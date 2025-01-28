@extends('layouts.global.app')

@section('title', trans('products.the products'))
@section('subTitle', $subTitle)

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@section('content')
    <div class="mt-5 product-addition">
        <form action="{{ route('supplier.products.update', [$product]) }}" method="POST" id="form_update">
            @csrf
            @method('PUT')

            <div class="mb-3 form-row d-flex align-items-center justify-content-evenly g-3 mx-auto">
                <div class="w-auto">
                    <label for="productImage">
                        <img id="productImagePreview"
                            src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                            alt="Product Image" @style(['cursor: pointer', 'max-width: 300px']) />
                    </label>
                    @error('image')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row row-cols-sm-1 row-cols-md-2 g-3">
                <div class="mb-3">
                    <label for="name_ar" class="form-label">{{ trans('products.name.ar') }}</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_ar')]) name="name_ar" id="name_ar"
                        value="{{ old('name_ar', $product->name_ar) }}" @required(true) @disabled(true)>
                    @error('name_ar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name_en" class="form-label">{{ trans('products.name.en') }}</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_en')]) name="name_en" id="name_en"
                        value="{{ old('name_en', $product->name_en) }}" @required(true) @disabled(true)>
                    @error('name_en')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">{{ trans('products.category') }}</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('category')]) name="category" id="category"
                        value="{{ old('category', $product->category?->name) }}" @required(true)
                        @disabled(true)>
                    @error('category')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="sub_category" class="form-label">{{ trans('products.sub category') }}</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sub_category')]) name="sub_category" id="sub_category"
                        value="{{ old('sub_category', $product->subCategory?->name) }}" @required(true)
                        @disabled(true)>
                    @error('sub_category')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ trans('products.description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="3" @disabled(true)>{{ old('description') ?? $product->description }}</textarea>
            </div>

            <hr>
            <h3>{{ trans('products.details.details') }}</h3>

            <div class="mb-3 row row-cols-sm-2">
                <div>
                    <p class="lead">
                        <span class="fw-bold">{{ trans('products.details.type') }}:</span>
                        <span id="details-type">{{ $product->detail->type }}</span>
                    </p>
                </div>
                <div>
                    <p class="lead">
                        <span class="fw-bold">{{ trans('products.details.name') }}:</span>
                        <span id="details-name">{{ $product->detail->name }}</span>
                    </p>
                </div>
            </div>

            <div class="mb-3 table-responsive">
                <table class="table display table-striped table-bordered" id="detailsTable" style="width:100%">
                    <thead>
                        <tr>
                            <td>{{ trans('products.details.key') }}</td>
                            <td>{{ trans('products.min order qty') }}</td>
                            <td>{{ trans('products.qty') }}</td>
                        </tr>
                    </thead>
                    <tbody id="details-key-body">
                        @foreach ($product->detail->keys as $item)
                            <tr>
                                {{-- <td colspan="3">{{ $item->detailUser }}</td> --}}
                                <td>
                                    <input type="hidden" name="detail_key_id[]"
                                        value="{{ $item->id ?? null }}">
                                    {{ $item->key }}
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="min_order_qty[]"
                                        value="{{ $item->detailUser->min_order_qty ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="qty[]"
                                        value="{{ $item->detailUser->qty ?? '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button" id="add-detail-btn" class="btn btn-secondary btn-sm my-2">إضافة تفاصيل
                أخرى</button>

            <div class="hstack">
                <button class="btn btn-import" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        function updateRemoveButtonsVisibility() {
            const detailsWrappers = document.querySelectorAll('.product-detail-item');
            detailsWrappers.forEach(wrapper => {
                const removeButton = wrapper.querySelector('.remove-details-btn');
                const addButton = wrapper.querySelector('.add-details-btn');
                if (removeButton && addButton)
                    if (detailsWrappers.length > 1) {
                        removeButton.classList.remove('d-none');
                        addButton.classList.remove('d-none');
                    } else {
                        removeButton.classList.add('d-none');
                        addButton.classList.add('d-none');
                    }
            });
        }

        function updateHeaderNumber() {
            const detailsWrappers = document.querySelectorAll('.product-detail-item');
            detailsWrappers.forEach((wrapper, _key) => {
                const detailsHeader = wrapper.querySelector('.details-header');
                detailsHeader.textContent = detailsHeader.textContent.replace(/\d+/, (_key + 1))
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the remove button visibility is correct on page load
            updateRemoveButtonsVisibility();
            updateHeaderNumber();


            const addNewDetailBtn = document.getElementById('add-detail-btn');
            if (addNewDetailBtn)
                addNewDetailBtn.addEventListener('click', function() {
                    const container = document.getElementById('product-details-container');
                    const clone = container.children[0].cloneNode(true);
                    const index = container.childElementCount;
                    // reset inputs values
                    clone.querySelectorAll('input').forEach(element => {
                        element.value = '';
                        element.name = element.name.replace(/\d+/, index);
                    });
                    // get select
                    const select = clone.querySelector('select');
                    // reset select value
                    select.selectedIndex = 0;
                    select.name = select.name.replace(/\d+/, index);

                    clone.querySelector('.add-details-btn').addEventListener('click', (evt) => addNewKeys(evt))

                    container.appendChild(clone);
                    // scroll down
                    document.scrollTop = document.scrollHeight
                    document.documentElement.scrollTop = document.documentElement.scrollHeight + 100;
                    updateRemoveButtonsVisibility();
                    updateHeaderNumber();
                });

            const detailsContainer = document.getElementById('product-details-container');
            if (detailsContainer) {
                detailsContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-details-btn')) {
                        event.target.closest('.product-detail-item').remove();
                        updateRemoveButtonsVisibility();
                        updateHeaderNumber();
                    }
                });
            }

            const addDetailsKeysBtn = document.querySelector('.add-details-btn');
            if (addDetailsKeysBtn)
                addDetailsKeysBtn.addEventListener('click', (evt) => addNewKeys(evt))
        });

        function addNewKeys(evt) {
            evt.preventDefault();
            console.log(evt.target.parentElement.previousElementSibling.querySelector('.keys-container'))
            // // get keys container
            // const keysContainer = document.querySelector('.keys-container')
            // const clone = keysContainer.children[0].cloneNode(true);
            // const index = keysContainer.childElementCount;
            // // reset inputs values
            // clone.querySelectorAll('input').forEach(element => {
            //     element.value = '';
            //     element.name = element.name.replace(/\d+/, index);
            // });

            // keysContainer.appendChild(clone);
        }
    </script>
@endpush
