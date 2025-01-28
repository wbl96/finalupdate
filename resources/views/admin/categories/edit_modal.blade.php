@section('editCategoryModal')
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        {{ trans('global.edit with name', ['name' => $category->name_ar]) }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.categories.update', ['category' => $category]) }}" method="POST"
                        id="updateCategory">
                        @csrf
                        @method('put')
                        <div class="mb-3 row row-cols-sm-1 row-cols-md-2">
                            <div>
                                <label for="name_ar" class="form-label">{{ trans('categories.name.ar') }}</label>
                                <input type="text" class="form-control" id="name_ar" name="name_ar"
                                    value="{{ old('name_ar', $category->name_ar) }}" @required(true)>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="name_en" class="form-label">{{ trans('categories.name.en') }}</label>
                                <input type="text" class="form-control" id="name_en" name="name_en"
                                    value="{{ old('name_en', $category->name_en) }}" @required(true)>
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div id="subcategories">
                            @forelse (old('subcategories',$category->subcategories ?? []) as $key => $subcategory)
                                <div class="mb-3 row row-cols-sm-1 row-cols-md-2 border rounded subcategory">
                                    <input type="hidden" name="subcategories[{{ $key }}][id]"
                                        value="{{ $subcategory->id }}">
                                    <div>
                                        <label for="subcategory">{{ trans('categories.name.sub ar') }}</label>
                                        <input type="text" class="form-control" id="subcategory"
                                            name="subcategories[{{ $key }}][name_ar]"
                                            value="{{ old('subcategory.' . $key . '.name_ar', $subcategory->name_ar) }}">
                                    </div>
                                    <div>
                                        <label for="subcategory">{{ trans('categories.name.sub en') }}</label>
                                        <input type="text" class="form-control" id="subcategory"
                                            name="subcategories[{{ $key }}][name_en]"
                                            value="{{ old('subcategory.' . $key . '.name_en', $subcategory->name_en) }}">
                                    </div>
                                    @unless ($loop->first)
                                        <div class="mb-3 w-100">
                                            <button type="button" class="btn btn-danger w-100"
                                                onclick="deleteSubCategory(this)"><i class="bi bi-trash"></i></button>
                                        </div>
                                    @endunless
                                </div>
                            @empty
                                <div class="mb-3 row row-cols-sm-1 row-cols-md-2 border rounded subcategory">
                                    <div>
                                        <label for="subcategory">{{ trans('categories.name.sub ar') }}</label>
                                        <input type="text" class="form-control" id="subcategory"
                                            name="subcategories[0][name_ar]" value="{{ old('subcategory.0.name_ar') }}">
                                    </div>
                                    <div>
                                        <label for="subcategory">{{ trans('categories.name.sub en') }}</label>
                                        <input type="text" class="form-control" id="subcategory"
                                            name="subcategories[0][name_en]" value="{{ old('subcategory.0.name_en') }}">
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-success w-100" id="add-new-subcategory"><i
                                class="bi bi-plus-lg"></i></button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-import" form="updateCategory">{{ trans('global.save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var addNewSubCategoryBtn = document.querySelector('#add-new-subcategory');
        addNewSubCategoryBtn.addEventListener('click', function(evt) {
            evt.preventDefault();
            // get sub categories container
            let subCategoriesContainer = document.querySelector('#subcategories');
            // get length of subcategories
            let index = subCategoriesContainer.childElementCount;
            // get clone from first element
            let clone = subCategoriesContainer.children[0].cloneNode(true);
            // Clear the input fields and update the name attributes
            let inputs = clone.querySelectorAll('input');
            inputs.forEach(input => {
                input.value = ''; // Clear the value
                // Update the name attribute to use the new index
                let name = input.getAttribute('name').replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', name);
            });
            // create a remove button
            let removeBtn = document.createElement('button');
            removeBtn.classList.add('btn', 'btn-danger', 'w-100');
            removeBtn.innerHTML = '<i class="bi bi-trash"></i>'
            // add event on remove button
            removeBtn.addEventListener('click', function(evt) {
                evt.preventDefault();
                deleteSubCategory(evt);
            });
            // create a remove button container
            let removeBtnContainer = document.createElement('div');
            removeBtnContainer.classList.add('w-100', 'mb-3');
            // append button to sub category
            removeBtnContainer.appendChild(removeBtn)
            clone.appendChild(removeBtnContainer)
            subCategoriesContainer.appendChild(clone)
        })


        function deleteSubCategory(btn) {
            let subCategory = btn.closest('.subcategory');
            // get subcategory id
            let subCategoryId = subCategory.querySelector('input[name*="[id]"]');

            console.log(subCategory, subCategoryId);

            if (subCategoryId) {
                let deletedInput = document.createElement('input');
                deletedInput.type = 'hidden';
                deletedInput.name = 'deleted_sub_categories[]';
                deletedInput.value = subCategoryId.value;
                document.querySelector('#updateCategory').appendChild(deletedInput);
            }
            // remove element from dom
            subCategory.remove();
        }
    </script>

@show
