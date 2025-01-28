@extends('layouts.global.app')

@section('title', trans('orders.the orders'))

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@push('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css"
        rel="stylesheet">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                {{-- refresh table button --}}
                <button class="btn btn-sm btn-outline-dark" onclick="table.ajax.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ trans('global.refresh table') }}
                </button>
            </div>
            {{-- table --}}
            <table class="table display table-striped table-bordered" id="ordersTable" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('orders.order num_') }}</th>
                        <th class="text-center">{{ trans('users.store name') }}</th>
                        <th class="text-center">{!! trans('orders.Total Amount <br> With VAT') !!}</th>
                        <th class="text-center">{{ trans('orders.receipt payment image') }}</th>
                        <th class="text-center">{{ trans('orders.order status') }}</th>
                        <th class="text-center">{{ trans('orders.created at') }}</th>
                        <th class="noExport"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen p-3" style="width: calc(100vw - 1rem);">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="div_pdf" style="width: 100%; height:100%">
                        <object type="application/pdf" style="width: 100%;height: 100%;">
                            <p>Insert your error message here, if the PDF cannot be displayed.</p>
                        </object>
                    </div>
                </div>
            </div>
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
            ajax: "{!! route('admin.orders.get-orders') !!}",
            columns: [{
                data: 'order_num',
                name: 'order_num'
            }, {
                data: 'store',
                name: 'store'
            }, {
                data: 'total_price',
                name: 'total_price'
            }, {
                data: 'payment_receipt',
                name: 'payment_receipt'
            }, {
                data: 'status',
                name: 'status'
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
        $('div.dt-buttons').addClass('w-100 d-flex justify-content-center').attr('dir', 'ltr')
        $('div.dt-buttons button').addClass('mx-1').removeClass('btn-primary').removeClass('btn-secondary')
        $('div.dt-button.buttons-collection').addClass('position-relative')

        function show_invoice(url) {
            $('#div_pdf object').attr('data', url + '?#zoom=100&scrollbar=0&toolbar=0&navpanes=0')
            $('#invoiceModal').modal('show')
        }

        function show_click(e, order_id) {
            let tr = $(e).closest('tr');
            let row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
            } else {
                // Open this row
                format(row, order_id)

            }
        }

        // Formatting function for row details - modify as you need
        function format(row, order_id) {
            // `d` is the original data object for the row
            $.ajax({
                url: "{{ url('admins/orders/order_items_ajax/') }}" + "/" + order_id,
                type: 'post',
                dataType: 'html',
                cache: false,
                data: {
                    '_token': '{{ csrf_token() }}',
                    'order_id': order_id
                },
                success: function(data) {
                    row.child(data).show();
                    // return (data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.responseText, textStatus, errorThrown);
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                }
            })

        }

        function change_order_status(order_id) {
            orderStatus = $('#order_status_select_' + order_id).val()
            $("#modal_freeze").modal("show")
            $.ajax({
                url: "{{ route('admin.orders.approve') }}",
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {
                    '_token': '{{ csrf_token() }}',
                    'order_id': order_id,
                    'orderStatus': orderStatus
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        console.log($('#order_status_select_' + order_id).closest('table').closest('tr').prev()
                            .find('td:nth-child(5)').html(orderStatus));

                    } else {
                        alert('يوجد خطأ ما, نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    }
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.responseText, textStatus, errorThrown);
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                }
            })
        }
    </script>
@endpush
