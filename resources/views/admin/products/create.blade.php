@extends('layouts.global.app')

@section('title', $title)

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" id="form_create" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 form-row d-flex align-items-center justify-content-evenly g-3 mx-auto">
                    {{-- <label for="productImage" class="form-label w-auto">{{ trans('products.image') }}</label> --}}
                    <div class="w-auto">
                        <label for="productImage">
                            <img id="productImagePreview" src="{{ asset('img/image-file512.webp') }}" alt="Product Image"
                                @style(['cursor: pointer', 'max-width: 300px']) />
                        </label>
                        <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('name_ar')]) id="productImage" name="image" accept="image/*"
                            style="display: none;" />
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
                            value="{{ old('name_ar') }}" @required(true)>
                        @error('name_ar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name_en" class="form-label">{{ trans('products.name.en') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_en')]) name="name_en" id="name_en"
                            value="{{ old('name_en') }}" @required(true)>
                        @error('name_en')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">{{ trans('products.category') }}</label>
                        <select @class(['form-select', 'is-invalid' => $errors->has('category_id')]) name="category_id" id="category_id">
                            <option selected disabled>{{ trans('categories.select category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                    {{ $locale == 'ar' ? $category->name_ar : $category->name_en }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sub_category_id" class="form-label">{{ trans('products.sub category') }}</label>
                        <select @class([
                            'form-select',
                            'is-invalid' => $errors->has('sub_category_id'),
                        ]) name="sub_category_id" id="sub_category_id">
                            <option selected disabled>{{ trans('categories.select subcategory') }}</option>
                        </select>
                        @error('sub_category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sku" class="form-label">{{ trans('products.sku') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sku')]) name="sku" id="sku"
                            value="{{ old('sku') }}" @required(true)>
                        @error('sku')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ trans('global.status') }}</label>
                        <select @class(['form-select', 'is-invalid' => $errors->has('status')]) name="status" id="status">
                            <option selected disabled>{{ trans('global.select status') }}</option>
                            @foreach ($status as $st)
                                <option value="{{ $st }}" @selected(old('status') == $st)>
                                    {{ trans('global.' . $st) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expected_price" class="form-label">السعر المتوقع</label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('expected_price') is-invalid @enderror" 
                                   id="expected_price" 
                                   name="expected_price" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('expected_price') }}"
                                   placeholder="أدخل السعر المتوقع">
                            <span class="input-group-text">ريال</span>
                        </div>
                        @error('expected_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ trans('products.description') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <hr>
                <h3>{{ trans('products.details.details') }}</h3>

                <div id="product-details-container">
                    <div class="product-detail-item border border-secondary-subtle rounded p-3 my-2">
                        <div class="mb-3 row g-3">
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="type">{{ trans('products.details.type') }}</label>
                                <select class="form-select" name="detail[type]" required>
                                    <option selected disabled>{{ trans('products.details.select type') }}</option>
                                    <option value="color" @selected(old('detail.type') == 'color')>
                                        {{ trans('products.details.color') }}</option>
                                    <option value="text" @selected(old('detail.type') == 'text')>
                                        {{ trans('products.details.text') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="name">{{ trans('products.details.name') }}</label>
                                <input type="text" class="form-control" name="detail[name]"
                                    value="{{ old('detail.name') }}" required>
                            </div>

                            @forelse (old('details.key', []) as $key => $item)
                                <div id="details-keys-container">
                                    <div class="col-12 details-key">
                                        <div class="row g-3 justify-content-center align-items-center">
                                            <div class="col-sm-12 col-md-10 form-group">
                                                <label for="key">{{ trans('products.details.key') }}</label>
                                                <input type="text" class="form-control" name="detail[key][]" required>
                                            </div>
                                            <div class="col-sm-12 col-md-2">
                                                <button type="button"
                                                    class="btn btn-outline-danger remove-details-btn w-100 h-100 d-none">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="details-keys-container">
                                    <div class="col-12 details-key">
                                        <div class="row g-3 justify-content-center align-items-center">
                                            <div class="col-sm-12 col-md-10 form-group">
                                                <label for="key">{{ trans('products.details.key') }}</label>
                                                <input type="text" class="form-control" name="detail[key][]" required>
                                            </div>
                                            <div class="col-sm-12 col-md-2">
                                                <button type="button"
                                                    class="btn btn-outline-danger remove-details-btn w-100 h-100 d-none">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
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
    </div>
@endsection

@push('script')
    <script>
        function updateRemoveButtonsVisibility() {
            const detailsWrappers = document.querySelectorAll('.details-key');
            detailsWrappers.forEach(wrapper => {
                const removeButton = wrapper.querySelector('.remove-details-btn');
                if (removeButton)
                    if (detailsWrappers.length > 1) {
                        removeButton.classList.remove('d-none');
                    } else {
                        removeButton.classList.add('d-none');
                    }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('productImage').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('productImagePreview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // Ensure the remove button visibility is correct on page load
            updateRemoveButtonsVisibility();

            let addNewDetailBtn = document.getElementById('add-detail-btn');
            if (addNewDetailBtn)
                addNewDetailBtn.addEventListener('click', function() {
                    let container = document.getElementById('details-keys-container');
                    let clone = container.children[0].cloneNode(true);
                    let index = container.childElementCount;
                    // reset inputs values
                    clone.querySelectorAll('input').forEach(element => {
                        element.value = '';
                        // element.name = element.name.replace('0', index);
                    });
                    // append clone
                    container.appendChild(clone)
                    // scroll down
                    document.scrollTop = document.scrollHeight
                    document.documentElement.scrollTop = document.documentElement.scrollHeight + 100;
                    updateRemoveButtonsVisibility();
                });

            let detailsContainer = document.getElementById('product-details-container');
            if (detailsContainer) {
                detailsContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-details-btn')) {
                        event.target.closest('.details-key').remove();
                        updateRemoveButtonsVisibility();
                    }
                });
            }
        });

        // add an event on category
        document.querySelector('#category_id').addEventListener('change', function(evt) {
            evt.preventDefault();
            // get target element id
            let id = evt.target.value;
            // prepare url
            let url =
                "{{ route('admin.categories.getSubcategories', ['category' => ':TARGET']) }}"
            // get subcategories of category
            $.ajax({
                type: "get",
                url: url.replace(':TARGET', id),
                success: function(response) {
                    // if subcategories founded put it in select box
                    let subcategoriesSelect = document.querySelector('#sub_category_id');
                    subcategoriesSelect.innerHTML = '';
                    // check if no subcategories founded
                    if (response.length == 0) {
                        subcategoriesSelect.appendChild(new Option(
                            "{{ trans('categories.no subcategories founded') }}")).setAttribute(
                            "disabled", true);;
                        return;
                    }
                    // loop omn subcategories to display it
                    response.forEach(element => {
                        // create an option
                        let option = new Option(element.name_ar, element.id);
                        subcategoriesSelect.appendChild(option);
                    });

                }
            });
        })
    </script>
@endpush
