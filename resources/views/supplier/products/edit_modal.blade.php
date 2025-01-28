@section('editProductModal')
    <div class="modal fade" id="EditProductModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="modalLabel">{{ $subTitle }}</h5>
                </div>
                <div class="modal-body pb-0">
                    <form action="{{ route('supplier.products.update', [$product]) }}" method="POST" id="form_update" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 form-row d-flex align-items-center justify-content-evenly gx-3 mx-auto">
                            <label for="productImage" class="form-label w-auto">{{ trans('products.image') }}</label>
                            <div class="w-auto">
                                <label for="productImage">
                                    <img id="productImagePreview"
                                        src="{{ $product->image && Storage::disk('public')->exists($product->image) ? asset('storage/' . $product->image) : asset('img/image-file512.webp') }}"
                                        alt="Product Image" @style(['cursor: pointer', 'max-width: 300px']) />
                                </label>
                                <input type="file" class="form-control-file" id="productImage" name="image"
                                    accept="image/*" style="display: none;" />
                            </div>
                        </div>

                        <div class="row row-cols-sm-1 row-cols-md-2 gx-3">
                            <div>
                                <label for="name_ar" class="form-label">{{ trans('products.name.ar') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_ar')]) name="name_ar" id="name_ar"
                                    value="{{ old('name_ar', $product->name_ar) }}" @required(true)>
                                @error('name_ar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label for="name_en" class="form-label">{{ trans('products.name.en') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_en')]) name="name_en" id="name_en"
                                    value="{{ old('name_en', $product->name_en) }}" @required(true)>
                                @error('name_en')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div>
                                <label for="edit_category_id" class="form-label">{{ trans('products.category') }}</label>
                                <select @class(['form-select', 'is-invalid' => $errors->has('category_id')]) name="category_id" id="edit_category_id">
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
                            <div>
                                <label for="edit_sub_category_id"
                                    class="form-label">{{ trans('products.sub category') }}</label>
                                <select @class([
                                    'form-select',
                                    'is-invalid' => $errors->has('sub_category_id'),
                                ]) name="sub_category_id" id="edit_sub_category_id">
                                    @php
                                        $subcategories = $product->category->subCategories;
                                    @endphp
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
                        </div>

                        <div class="mb-3 row">
                            <label for="description" class="form-label">{{ trans('products.description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                        <div class="row row-cols-sm-1 row-cols-md-2 gx-3">
                            <div class="mb-3">
                                <label for="min_order_qty" class="form-label">{{ trans('products.min order qty') }}</label>
                                <input type="number" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('min_order_qty'),
                                ]) name="min_order_qty" id="min_order_qty"
                                    value="{{ old('min_order_qty', $product->min_order_qty) }}" @required(true)>
                                @error('min_order_qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="max_order_qty" class="form-label">{{ trans('products.max order qty') }}</label>
                                <input type="number" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('max_order_qty'),
                                ]) name="max_order_qty" id="max_order_qty"
                                    value="{{ old('max_order_qty', $product->max_order_qty) }}" @required(true)>
                                @error('max_order_qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="qty" class="form-label">{{ trans('products.qty') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('qty')]) name="qty" id="qty"
                                    @required(true) value="{{ old('qty', $product->qty) }}">
                                @error('qty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            {{-- <div class="mb-3">
                                <label for="payment_type" class="form-label">{{ trans('products.payment type') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('payment_type')]) name="payment_type" id="payment_type"
                                    @required(true) value="{{ old('payment_type', $product->payment_type) }}">
                                @error('payment_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="delivery_method"
                                    class="form-label">{{ trans('products.delivery method') }}</label>
                                <input type="text" @class([
                                    'form-control',
                                    'is-invalid' => $errors->has('delivery_method'),
                                ]) name="delivery_method"
                                    id="delivery_method" @required(true)
                                    value="{{ old('delivery_method', $product->delivery_method) }}">
                                @error('delivery_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="price" class="form-label">{{ trans('products.price') }}</label>
                                <div class="input-group" dir="ltr">
                                    <span class="input-group-text" id="basic-addon1">{{ trans('global.SAR') }}</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', $product->price) }}">
                                </div>
                                @error('price')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div> --}}
                        </div>

                        {{-- <h4>خيارات المنتج</h4>
                        <div class="product-container">
                            <div class="attribute-section">
                                <div class="mb-3 row">
                                    <label for="attribute-name">اسم الخاصية</label>
                                    <input class="form-control" type="text" id="attribute-name"
                                        placeholder="مثال: لون المنتج" />
                                    <label for="attribute-type">النوع</label>
                                    <select id="attribute-type">
                                        <option>لون</option>
                                        <option>نص</option>
                                    </select>
                                </div>

                                <div class="form-row attribute-inputs">
                                    <input class="form-control" type="text" placeholder="اسود" />
                                    <input class="form-control" type="text" placeholder="الكمية" />
                                </div>
                                <div class="form-row attribute-inputs">
                                    <input class="form-control" type="text" placeholder="اسود" />
                                    <input class="form-control" type="text" placeholder="الكمية" />
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <button type="button" id="addMore" class="add-more-btn"><i
                                        class="bi bi-plus"></i></button>
                            </div>
                        </div> --}}
                    </form>
                </div>
                <hr>
                <div class="mt-3">
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-import btn-sm" form="form_update">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('productImage').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('productImagePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        // add an event on category
        document.querySelector('#edit_category_id').addEventListener('change', function(evt) {
            evt.preventDefault();
            // get target element id
            let id = evt.target.value;
            // prepare url
            let url =
                "{{ route('supplier.categories.getSubcategories', ['category' => ':TARGET']) }}"
            // get subcategories of category
            $.ajax({
                type: "get",
                url: url.replace(':TARGET', id),
                success: function(response) {
                    // check if no subcategories founded
                    if (response.length == 0) {
                        alert("{{ trans('categories.no subcategories founded') }}");
                        return;
                    }
                    // if subcategories founded put it in select box
                    let subcategoriesSelect = document.querySelector('#edit_sub_category_id');
                    subcategoriesSelect.innerHTML = '';
                    subcategoriesSelect.appendChild(new Option(
                        "{{ trans('categories.select subcategory') }}"));
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
@show
