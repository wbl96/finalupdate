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
    <div class="card">
        <div class="card-body">
            <form action="{{ route('supplier.products.store') }}" method="POST" id="form_create">
                @csrf
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
                        <label for="category" class="form-label">{{ trans('products.category') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('category')]) name="category" id="category"
                            value="{{ old('category') }}" @required(true)>
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sub_category" class="form-label">{{ trans('products.sub category') }}</label>
                        <input type="text" @class([
                            'form-control',
                            'is-invalid' => $errors->has('sub_category'),
                        ]) name="sub_category" id="sub_category"
                            value="{{ old('sub_category') }}" @required(true)>
                        @error('sub_category')
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
                <div class="row row-cols-sm-1 row-cols-md-2 g-3">
                    <div class="mb-3">
                        <label for="min_order_qty" class="form-label">{{ trans('products.min order qty') }}</label>
                        <input type="number" @class([
                            'form-control',
                            'is-invalid' => $errors->has('min_order_qty'),
                        ]) name="min_order_qty" id="min_order_qty"
                            value="{{ old('min_order_qty') }}" @required(true)>
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
                            value="{{ old('max_order_qty') }}" @required(true)>
                        @error('max_order_qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="qty" class="form-label">{{ trans('products.qty') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('qty')]) name="qty" id="qty" @required(true)
                            value="{{ old('qty') }}">
                        @error('qty')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="payment_type" class="form-label">{{ trans('products.payment type') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('payment_type')]) name="payment_type" id="payment_type"
                            @required(true) value="{{ old('payment_type') }}">
                        @error('payment_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="delivery_method" class="form-label">{{ trans('products.delivery method') }}</label>
                        <input type="text" @class([
                            'form-control',
                            'is-invalid' => $errors->has('delivery_method'),
                        ]) name="delivery_method" id="delivery_method"
                            @required(true) value="{{ old('delivery_method') }}">
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
                    </div>
                </div>

                <hr>
                <h3>{{ trans('products.details.details') }}</h3>

                <div id="product-details-container">
                    @forelse (old('details', array()) as $key => $item)
                        <div class="product-detail-item border border-secondary-subtle rounded p-3 my-2">
                            <header class="border-bottom mb-3">
                                <h4 class="h4 mb-0 details-header">
                                    {{ trans('products.details.num', ['num' => $key + 1]) }}
                                </h4>
                            </header>
                            <div class="mb-3 row row-cols-sm-1 row-cols-md-2 g-3">
                                <div class="form-group">
                                    <label for="type">{{ trans('products.details.type') }}</label>
                                    <select class="form-select" name="details[{{ $key }}][type]" required>
                                        <option selected disabled>{{ trans('products.details.select type') }}</option>
                                        <option value="color" @selected($item[$key]['type'] == 'color')>
                                            {{ trans('products.details.color') }}</option>
                                        <option value="text" @selected($item[$key]['type'] == 'text')>
                                            {{ trans('products.details.text') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ trans('products.details.name') }}</label>
                                    <input type="text" class="form-control" name="details[{{ $key }}][name]"
                                        value="{{ $item[$key]['name'] }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="key">{{ trans('products.details.key') }}</label>
                                    <input type="text" class="form-control" name="details[{{ $key }}][key]"
                                        value="{{ $item[$key]['key'] }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="qty">{{ trans('products.qty') }}</label>
                                    <input type="text" class="form-control" name="details[{{ $key }}][qty]"
                                        value="{{ $item[$key]['qty'] }}" required>
                                </div>
                            </div>

                            @if (count(old('details')) > 1)
                                <button type="button"
                                    class="btn btn-outline-danger remove-details-btn w-100 h-100 d-none">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="product-detail-item border border-secondary-subtle rounded p-3 my-2">
                            <header class="border-bottom mb-3">
                                <h4 class="h4 mb-0 details-header">{{ trans('products.details.num', ['num' => 1]) }}</h4>
                            </header>
                            <div class="mb-3 row row-cols-sm-1 row-cols-md-2 g-3">
                                <div class="form-group">
                                    <label for="type">{{ trans('products.details.type') }}</label>
                                    <select class="form-select" name="details[0][type]" required>
                                        <option selected disabled>{{ trans('products.details.select type') }}</option>
                                        <option value="color">{{ trans('products.details.color') }}</option>
                                        <option value="text">{{ trans('products.details.text') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ trans('products.details.name') }}</label>
                                    <input type="text" class="form-control" name="details[0][name]" required>
                                </div>
                                <div class="form-group">
                                    <label for="key">{{ trans('products.details.key') }}</label>
                                    <input type="text" class="form-control" name="details[0][key]" required>
                                </div>
                                <div class="form-group">
                                    <label for="qty">{{ trans('products.qty') }}</label>
                                    <input type="text" class="form-control" name="details[0][qty]" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-danger remove-details-btn w-100 h-100 d-none">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    @endforelse
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
            const detailsWrappers = document.querySelectorAll('.product-detail-item');
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
            // Ensure the remove button visibility is correct on page load
            updateRemoveButtonsVisibility();

            let addNewDetailBtn = document.getElementById('add-detail-btn');
            if (addNewDetailBtn)
                addNewDetailBtn.addEventListener('click', function() {
                    let container = document.getElementById('product-details-container');
                    let clone = container.children[0].cloneNode(true);
                    let index = container.childElementCount;
                    // reset inputs values
                    clone.querySelectorAll('input').forEach(element => {
                        element.value = '';
                        element.name = element.name.replace('0', index);
                    });
                    // get select
                    let select = clone.querySelector('select');
                    // reset select value
                    select.selectedIndex = 0;
                    select.name = select.name.replace('0', index);
                    // get clone header
                    let cloneHeader = clone.querySelector('header>.details-header');
                    let headerContent = "{{ trans('products.details.num', ['num' => ':TARGET']) }}";
                    cloneHeader.textContent = headerContent.replace(':TARGET', index + 1);
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
                        event.target.closest('.product-detail-item').remove();
                        updateRemoveButtonsVisibility();
                    }
                });
            }
        });
    </script>
@endpush
