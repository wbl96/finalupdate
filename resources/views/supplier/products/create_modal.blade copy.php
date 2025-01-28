@section('createNewProductModal')
    <div class="modal fade" id="createNewProductModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="modalLabel">{{ trans('products.add new') }}</h5>
                </div>
                <div class="modal-body pb-0">
                    <div class="product-addition">
                        <form action="{{ route('supplier.products.store') }}" method="POST" id="form_store"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 form-row d-flex align-items-center justify-content-evenly g-3 mx-auto">
                                {{-- <label for="productImage" class="form-label w-auto">{{ trans('products.image') }}</label> --}}
                                <div class="w-auto">
                                    <label for="productImage">
                                        <img id="productImagePreview" src="{{ asset('img/image-file512.webp') }}"
                                            alt="Product Image" @style(['cursor: pointer', 'max-width: 300px']) />
                                    </label>
                                    <input type="file" class="form-control-file" id="productImage" name="image"
                                        accept="image/*" style="display: none;" />
                                </div>
                            </div>

                            <div class="row row-cols-sm-1 row-cols-md-2 gx-3">
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
                                    <label for="sub_category_id"
                                        class="form-label">{{ trans('products.sub category') }}</label>
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
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ trans('products.description') }}</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                            <div class="row row-cols-sm-1 row-cols-md-2 gx-3">
                                <div class="mb-3">
                                    <label for="min_order_qty"
                                        class="form-label">{{ trans('products.min order qty') }}</label>
                                    <input type="number" @class([
                                        'form-control',
                                        'is-invalid' => $errors->has('min_order_qty'),
                                    ]) name="min_order_qty"
                                        id="min_order_qty" value="{{ old('min_order_qty') }}" @required(true)>
                                    @error('min_order_qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="max_order_qty"
                                        class="form-label">{{ trans('products.max order qty') }}</label>
                                    <input type="number" @class([
                                        'form-control',
                                        'is-invalid' => $errors->has('max_order_qty'),
                                    ]) name="max_order_qty"
                                        id="max_order_qty" value="{{ old('max_order_qty') }}" @required(true)>
                                    @error('max_order_qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="qty" class="form-label">{{ trans('products.qty') }}</label>
                                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('qty')]) name="qty" id="qty"
                                        @required(true) value="{{ old('qty') }}">
                                    @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="mb-3">
                                    <label for="payment_type"
                                        class="form-label">{{ trans('products.payment type') }}</label>
                                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('payment_type')]) name="payment_type"
                                        id="payment_type" @required(true) value="{{ old('payment_type') }}">
                                    @error('payment_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                                {{-- <div class="mb-3">
                                    <label for="delivery_method"
                                        class="form-label">{{ trans('products.delivery method') }}</label>
                                    <input type="text" @class([
                                        'form-control',
                                        'is-invalid' => $errors->has('delivery_method'),
                                    ]) name="delivery_method"
                                        id="delivery_method" @required(true) value="{{ old('delivery_method') }}">
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
                                            id="price" name="price" value="{{ old('price') }}">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div> --}}
                            </div>

                            <button type="button" id="add-detail-btn" class="btn btn-secondary btn-sm my-2 w-100">إضافة
                                تفاصيل
                                أخرى</button>
                        </form>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-import btn-sm" form="form_store">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the remove button visibility is correct on page load
            updateRemoveButtonsVisibility();

            document.getElementById('productImage').addEventListener('change', function(event) {
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('productImagePreview');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            });

            // Adding new input fields when the "Add More" button is clicked
            document.querySelectorAll('.add-more-btn').forEach(element => {
                element.addEventListener('click', () => addMoreKeys());
            });

            let addNewDetailBtn = document.getElementById('add-detail-btn');
            if (addNewDetailBtn) {
                addNewDetailBtn.addEventListener('click', () => createNewProductDetails());
            }

            let detailsContainer = document.getElementById('product-details-container');
            if (detailsContainer) {
                detailsContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-details-btn')) {
                        event.target.closest('.product-container').remove();
                        updateRemoveButtonsVisibility();
                    }
                });
            }

            // add an event on category
            document.querySelector('#category_id').addEventListener('change', function(evt) {
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
                        let subcategoriesSelect = document.querySelector('#sub_category_id');
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
        });

        function deleteDetailsKey(btn) {
            // get closest attribute container
            const attrContainer = btn.closest('.attribute-inputs');

            // check if exists in DOM
            if (attrContainer) {
                attrContainer.remove();
                return;
            }
            alert("{{ trans('global.process failed') }}");
        }

        function updateRemoveButtonsVisibility() {
            const detailsWrappers = document.querySelectorAll('.product-container');
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

        function addMoreKeys() {
            // details counter
            const detailsCount = document.querySelectorAll('.product-container').length;
            // get attribute section
            const attrSection = this.parentElement.closest('.attribute-section');
            let attrSectionInputsCount = attrSection.querySelectorAll('.attribute-inputs')
                .length;
            // Create new form row with two inputs
            var newRow = document.createElement('div');
            newRow.className = 'form-row attribute-inputs';
            // input 1
            var input1 = document.createElement('input');
            input1.type = 'text';
            input1.classList.add('form-control');
            input1.placeholder = 'اسود';
            input1.name = `details[${detailsCount}][key][${attrSectionInputsCount}]`;
            // input 2
            var input2 = document.createElement('input');
            input2.type = 'text';
            input2.classList.add('form-control');
            input2.placeholder = 'الكمية';
            input2.name = `details[${detailsCount}][qty][${attrSectionInputsCount}]`;
            // delete icon
            var span = document.createElement('span');
            span.classList.add('text-danger');
            span.style.cursor = 'pointer';
            span.innerHTML = '<i class="bi bi-x-lg"></i>';
            span.addEventListener('click', () => deleteDetailsKey(span));
            // append elements
            newRow.appendChild(input1);
            newRow.appendChild(input2);
            newRow.appendChild(span);
            // Append the new row to the attribute section
            attrSection.insertBefore(newRow, this.parentElement);
            s
        }

        function createNewProductDetails() {
            // let container = document.getElementById('product-details-container');
            // let index = container.childElementCount;
            // // create product details container
            // let productDetail = document.createElement('div');
            // productDetail.classList.add('product-container');
            // // create attribute section
            // let productDetail = document.createElement('div');
            // productDetail.classList.add('attribute-section');
            // // create details type and name
            // let
            // container.appendChild(clone)
            // // scroll down
            // // document.scrollTop = document.scrollHeight
            // document.documentElement.scrollTop = document.documentElement.scrollHeight + 100;
            updateRemoveButtonsVisibility();
        }
    </script>
@show
