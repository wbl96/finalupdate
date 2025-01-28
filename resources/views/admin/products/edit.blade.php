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
            <form action="{{ route('admin.products.update', $product) }}" method="POST" id="form_create"
                enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="mb-3 form-row d-flex align-items-center justify-content-evenly g-3 mx-auto">
                    <div class="w-auto">
                        <label for="productImage">
                            <img id="productImagePreview"
                                src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                                alt="Product Image" @style(['cursor: pointer', 'max-width: 300px']) />
                        </label>
                        <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('image')]) id="productImage" name="image" accept="image/*"
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
                            value="{{ old('name_ar', $product->name_ar) }}" @required(true)>
                        @error('name_ar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name_en" class="form-label">{{ trans('products.name.en') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_en')]) name="name_en" id="name_en"
                            value="{{ old('name_en', $product->name_en) }}" @required(true)>
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
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
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
                        @php
                            $subcategories = $product->category->subCategories;
                        @endphp
                        <label for="sub_category_id" class="form-label">{{ trans('products.sub category') }}</label>
                        <select @class([
                            'form-select',
                            'is-invalid' => $errors->has('sub_category_id'),
                        ]) name="sub_category_id" id="sub_category_id">
                            <option selected disabled>{{ trans('categories.select subcategory') }}</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" @selected(old('sub_category_id', $product->sub_category_id) == $subcategory->id)>
                                    {{ $locale == 'ar' ? $subcategory->name_ar : $subcategory->name_en }}</option>
                            @endforeach
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
                            value="{{ old('sku', $product->sku) }}" @required(true)>
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
                                <option value="{{ $st }}" @selected(old('status', $product->status) == $st)>
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
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ trans('products.description') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>

                <hr>
                <h3>{{ trans('products.details.details') }}</h3>

                <div id="product-details-container">
                    <div class="product-detail-item border border-secondary-subtle rounded p-3 my-2">
                        <div class="mb-3 row g-3">
                            <input type="hidden" name="detail[id]" value="{{ $product->detail->id }}">
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="type">{{ trans('products.details.type') }}</label>
                                <select class="form-select" name="detail[type]" required>
                                    <option selected disabled>{{ trans('products.details.select type') }}</option>
                                    <option value="color" @selected(old('detail.type', $product->detail->type) == 'color')>
                                        {{ trans('products.details.color') }}</option>
                                    <option value="text" @selected(old('detail.type', $product->detail->type) == 'text')>
                                        {{ trans('products.details.text') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label for="name">{{ trans('products.details.name') }}</label>
                                <input type="text" class="form-control" name="detail[name]"
                                    value="{{ old('detail.name', $product->detail->name) }}" required>
                            </div>

                            <div id="details-keys-container">
                                <div id="message"></div>
                                @forelse (old('details.key', $product->detail->keys ?? []) as $key => $item)
                                    <div class="col-12 details-key">
                                        <div class="row g-3 justify-content-center align-items-center">
                                            <input type="hidden" name="detail[keys_id][]" class="details-key-id"
                                                value="{{ $item->id ?? null }}">
                                            <div class="col-sm-12 col-md-10 form-group">
                                                <label for="key">{{ trans('products.details.key') }}</label>
                                                <input type="text" class="form-control" name="detail[key][]"
                                                    value="{{ $item->key }}" required>
                                            </div>
                                            <div class="col-sm-12 col-md-2">
                                                <button type="button"
                                                    class="btn btn-outline-danger remove-details-btn w-100 h-100 d-none">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
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
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-detail-btn" class="btn btn-secondary btn-sm my-2 w-auto">إضافة تفاصيل
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
                    let clone = container.querySelector('.details-key').cloneNode(true);
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
                        // confirm delete
                        let confirmed = confirm('هل انت متأكد من الحذف؟');
                        if (!confirmed) return;

                        let container = event.target.closest('.details-key')

                        // get target element id
                        let id = container.querySelector('input[type=hidden]').value;
                        // prepare url
                        let url =
                            "{{ route('admin.products.deleteDetailKey', ['id' => ':TARGET']) }}";

                        // send request to delete key
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: url.replace(':TARGET', id),
                            type: "post",
                            success: function(response) {
                                console.log(response, response.status === 'success');

                                if (response.status === 'success') {
                                    let alert =
                                        `<div class="alert alert-success w-100" role="alert">${response.message}</div>`;

                                    setTimeout(() => {
                                        $('#message').html('');
                                    }, 3000);
                                } else {
                                    alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                                    alert(response.message);
                                }
                            },
                            error: function(xhr, res) {
                                alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                                return false;
                            }
                        });

                        // remove container
                        container.remove();
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
                },
                error: function(xhr, res) {
                    alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    return false;
                }
            });
        })
    </script>
@endpush
