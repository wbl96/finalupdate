@extends('layouts.global.app')

@section('title', trans('orders.rfq requests'))

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@push('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css"
        rel="stylesheet">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="alert alert-danger w-100" id="print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <div class="alert alert-success w-100" id="print-success-msg" style="display:none">
                    {{-- <ul></ul> --}}
                </div>
            </div>
            <div class="mb-3">
                {{-- refresh table button --}}
                <button class="btn btn-sm btn-outline-dark" onclick="table.ajax.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ trans('global.refresh table') }}
                </button>
            </div>
            {{-- table --}}
            <table class="table display table-striped table-bordered" id="ordersTable" style="width:100%; border: 1px solid #dee2e6;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">{{ trans('products.image') }}</th>
                        <th class="text-center">{{ trans('products.name.name') }}</th>
                        <th class="text-center">{{ trans('products.details.details') }}</th>
                        <th class="text-center">{{ trans('products.qty') }}</th>
                        <th class="text-center">{{ trans('orders.created at') }}</th>
                        <th class="noExport"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js">
    </script>
    <script>
        let translation = JSON.parse(@js(json_encode(trans('pagination'))));
        var table = $('#ordersTable').DataTable({
            scrollX: true,
            scrollY: 'auto',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{!! route('supplier.orders.rfq-requests') !!}",
            columns: [{
                data: 'DT_RowIndex',
                searchable: false
            }, {
                data: 'image',
                name: 'image',
                orderable: false,
                searchable: false,
            }, {
                data: 'product',
                name: 'product'
            }, {
                data: 'variant',
                name: 'variant'
            }, {
                data: 'quantity',
                name: 'quantity',
                searchable: false,
            }, {
                data: 'created_at',
                name: 'created_at'
            }, {
                data: 'controls',
                name: 'controls',
                orderable: false,
                searchable: false,
            }],
            pageLength: 10,
            searching: true,
            lengthMenu: [
                [10, 25, 50, 100, 500, -1],
                [10, 25, 50, 100, 500, translation.table.all],
            ],
            dom: "<'row'<'col-sm-12 d-flex justify-content-center'B>>" +
                "<'row-padding'>" +
                "<'row'<'col-sm-12 col-md-6 my-2'f><'col-sm-12 col-md-6 justify-content-end d-flex my-2'll>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5 my-2'i><'col-sm-12 col-md-7 justify-content-end d-flex my-2'p>>",
            buttons: [{
                    extend: 'copyHtml5',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="bi bi-copy"></i>&nbsp;Copy',
                    titleAttr: 'copy',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="bi bi-file-excel"></i>&nbsp;Excel',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'csvHtml5',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="bi bi-filetype-csv"></i>&nbsp;CSV',
                    titleAttr: 'CSV',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="bi bi-file-pdf"></i>&nbsp;PDF',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-success',
                    text: '<i class="bi bi-printer"></i>&nbsp;Print',
                    titleAttr: 'Print',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    className: 'btn btn-sm btn-outline-danger',
                    text: '<i class="bi bi-x-lg"></i>&nbsp;Reset',
                    titleAttr: 'Reset',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    },
                    action: function(e, dt, node, config) {
                        table.search("").draw();
                    }
                },
            ],
            "language": translation.table,
            searchPanes: {
                viewTotal: true
            },

        });
        // Add event listener for opening and closing details
        function btnRFQ_click (e) {
            let tr = $(e).closest('tr');
            let row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            }
            else {
                // Open this row
                row.child(format(row.data())).show();
            }
        }
        $('div.dt-buttons').addClass('w-100 d-flex justify-content-center').attr('dir', 'ltr')
        $('div.dt-buttons button').addClass('mx-1').removeClass('btn-primary').removeClass('btn-secondary')
        $('div.dt-button.buttons-collection').addClass('position-relative')

        // Formatting function for row details - modify as you need
        function format(d) {
            // `d` is the original data object for the row
            return (
                `
                    <form action="{{ route('supplier.orders.submit-quote') }}" class="formQutation row align-items-center bg-dark-subtle py-2" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="cart_item_id" value="${d.id}">
                        <input type="hidden" name="detail_key_id" value="${d.detail_key_id}">
                        <input type="hidden" name="store_id" value="${d.store_id}">
                        <div class="row col-md-10">
                            <div class="col-md-4">
                                <label>الكمية المتاحة</label>
                                <input type="number" min="${d.quantity}" max="${d.quantity}" class="form-control" name="quantity" required
                                    id="quantity_${d.id}" onkeyup="priceQtyChange(${d.id})">
                            </div>
                            <div class="col-md-4">
                                <label>السعر (للقطعة)</label>
                                <input type="number" class="form-control" name="price" id="price_${d.id}" required onkeyup="priceQtyChange(${d.id})">
                            </div>
                            <div class="col-md-4">
                                <label>إجمالي السعر</label>
                                <input type="number" class="form-control" disabled id="tot_amt_${d.id}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-dark btn-sm" type="button" onclick="submitForm(this)">إرسال</button>
                        </div>
                    </form>
                `
            );
        }

        function submitForm(elem){
            form = $(elem).closest('form')
            $("#modal_freeze").modal("show")
            $("#print-success-msg").css('display', 'none');
            $("#print-error-msg").css('display', 'none');
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                dataType: 'json',
                cache: false,
                data: form.serialize(),
                success: function(data) {
                    console.log(data);
                    if ($.isEmptyObject(data.error)) {
                        $("#print-success-msg").css('display', 'block');
                        $("#print-success-msg").html(data.success);
                        form[0].reset();
                        tr = $(form).closest('tr')
                        tr.prev().remove()
                        tr.remove()
                    } else {
                        printErrorMsg(data.error);
                    }
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText, textStatus, errorThrown);
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                }
            })
        }

        function printErrorMsg(msg) {
            $("#print-error-msg").find("ul").html('');
            $("#print-error-msg").css('display', 'block');
            $.each(msg, function(key, value) {
                $("#print-error-msg").find("ul").append('<li>' + value + '</li>');
            })
        }

        function priceQtyChange(id){
            $('#tot_amt_'+id).val(parseInt($('#price_'+id).val()) * parseInt($('#quantity_'+id).val()))
        }
    </script>
@endpush
