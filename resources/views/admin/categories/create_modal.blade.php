@section('addNewCategoryModal')
    <div class="modal fade" id="addNewCategoryModal" tabindex="-1" aria-labelledby="addNewCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addNewCategoryModalLabel">{{ trans('categories.add new') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="createCategory">
                        @csrf
                        <div class="mb-3 row row-cols-sm-1 row-cols-md-2">
                            <div>
                                <label for="name_ar" class="form-label">{{ trans('categories.name.ar') }}</label>
                                <input type="text" class="form-control" id="name_ar" name="name_ar"
                                    value="{{ old('name_ar') }}" @required(true)>
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="name_en" class="form-label">{{ trans('categories.name.en') }}</label>
                                <input type="text" class="form-control" id="name_en" name="name_en"
                                    value="{{ old('name_en') }}" @required(true)>
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="subcategories">
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
                        </div>

                        <button type="button" class="btn btn-success w-100" id="add-new-subcategory"><i
                                class="bi bi-plus-lg"></i></button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-import" form="createCategory">{{ trans('global.save') }}</button>
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
                this.closest('.subcategory').remove()
            });
            // create a remove button container
            let removeBtnContainer = document.createElement('div');
            removeBtnContainer.classList.add('w-100', 'mb-3');
            // append button to sub category
            removeBtnContainer.appendChild(removeBtn)
            clone.appendChild(removeBtnContainer)
            subCategoriesContainer.appendChild(clone)
        })
    </script>
@show
