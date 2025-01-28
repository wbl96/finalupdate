@extends('layouts.global.app')

@section('title', trans('products.the products'))
@section('subTitle', trans('global.add new'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css"
        rel="stylesheet">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('supplier.products.store') }}" method="POST" id="form_create">
                @csrf
                <div class="mb-3 form-row d-flex align-items-center justify-content-evenly g-3 mx-auto">
                    <div class="w-auto">
                        <label for="productImage">
                            <img id="productImagePreview" src="{{ asset('img/image-file512.webp') }}" alt="Product Image"
                                @style(['cursor: pointer', 'max-width: 300px']) />
                        </label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="name_ar" class="form-label">{{ trans('products.select product') }}</label>
                        <select class="form-select js-example-basic-single js-states js-data-example-ajax"
                            aria-label="multiple select example" id="products_list">
                            <option selected>Open this select menu</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="product_id" id="product_id">
                    </div>
                </div>
                <div class="row row-cols-sm-1 row-cols-md-2 g-3">
                    <div class="mb-3">
                        <label for="name_ar" class="form-label">{{ trans('products.name.ar') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_ar')]) name="name_ar" id="name_ar"
                            value="{{ old('name_ar') }}" @required(true) @disabled(true)>
                        @error('name_ar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name_en" class="form-label">{{ trans('products.name.en') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name_en')]) name="name_en" id="name_en"
                            value="{{ old('name_en') }}" @required(true) @disabled(true)>
                        @error('name_en')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">{{ trans('products.category') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('category')]) name="category" id="category"
                            value="{{ old('category') }}" @required(true) @disabled(true)>
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sub_category" class="form-label">{{ trans('products.sub category') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('sub_category')]) name="sub_category" id="sub_category"
                            value="{{ old('sub_category') }}" @required(true) @disabled(true)>
                        @error('sub_category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ trans('products.description') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="3" @disabled(true)>{{ old('description') }}</textarea>
                </div>

                <hr>
                <h3>{{ trans('products.details.details') }}</h3>

                <div class="mb-3 row row-cols-sm-2">
                    <div>
                        <p class="lead">
                            <span class="fw-bold">{{ trans('products.details.type') }}:</span>
                            <span id="details-type"></span>
                        </p>
                    </div>
                    <div>
                        <p class="lead">
                            <span class="fw-bold">{{ trans('products.details.name') }}:</span>
                            <span id="details-name"></span>
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
                        <tbody id="details-key-body"></tbody>
                    </table>
                </div>

                {{-- <button type="button" id="add-detail-btn" class="btn btn-secondary btn-sm my-2">إضافة تفاصيل
                    أخرى</button> --}}

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#products_list").select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for a product',
            minimumInputLength: 1,
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
            ajax: {
                url: "{{ route('supplier.products.ajaxGetProducts') }}",
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: '{{ csrf_token() }}',
                        q: params.term, // search term
                        page: params.current_page
                    };
                },
                processResults: function(data, params) {
                    params.current_page = params.current_page || 1;

                    return {
                        results: data.data,
                        pagination: {
                            more: (params.current_page * 30) < data.total
                        }
                    };
                },
                autoWidth: true,
                cache: true,
            },
        });

        $('#products_list').on('select2:select', function(e) {
            var data = e.params.data;
            $("#modal_freeze").modal("show")

            $('#product_id').val(data.id)
            $('input[name=name_ar]').val(data.name_ar)
            $('input[name=name_en]').val(data.name_en)
            $('input[name=category]').val(data.category?.name_ar ?? '')
            $('input[name=sub_category]').val(data.subcategory?.name_ar ?? '')
            $('textarea[name=description]').val(data.description)

            $('#details-type').text(data.detail?.type)
            $('#details-name').text(data.detail?.name)

            let productImg = @json(Storage::url(':PATH'));
            productImg = productImg.replace(':PATH', data.image)
            $('#productImagePreview').attr('src', productImg)

            // get detail keys
            let keys = data.detail.keys;
            let tableContent = '';

            if (keys.length > 0) {
                keys.forEach(element => {
                    tableContent += `<tr>`;
                    tableContent += `<td>${element.key}
                    <input type="hidden" name="detail_key_id[]" value="${element.id}" />
                </td>`;
                    tableContent += `<td><input class="form-control" name="min_order_qty[]"></td>`;
                    tableContent += `<td><input class="form-control" name="qty[]"></td>`;
                    tableContent += `</tr>`;
                });
            } else {
                tableContent += `<tr><td colspan="3"><div class="alert alert-danger">لا يوجد خواص</div></tr>`;
            }

            $('#details-key-body').html(tableContent);
            setTimeout(() => {
                $("#modal_freeze").modal("hide")
            }, 800);
        });

        function formatProduct(product) {
            if (product.loading) {
                return product.name_ar;
            }

            let productImg = product.image ? @json(Storage::url(':PATH')) : @json(asset('img'));
            productImg = product.image ? productImg.replace(':PATH', product.image) : productImg + '/image-file512.webp';

            var $container = $(
                "<div class='select2-result-product clearfix'>" +
                "<span class='select2-result-product__title'><img src='" + productImg +
                "' class='img-fluid mx-2' width='40' height='40'>" + product.name_ar + "</span> - " +
                "<span class='select2-result-product__sku'>" + product.sku ?? 'sku غير معروف' + "</span> - " +
                // "<span class='select2-result-product__image'></span>" +
                "</div>"
            );

            return $container;
        }

        function formatProductSelection(product) {
            return product.name_ar || product.name_en;
        }
        // $(document).ready(function() {
        //     $.noConflict();
        // });
    </script>
@endpush
