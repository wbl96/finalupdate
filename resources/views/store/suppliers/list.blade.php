@extends('layouts.store.app')

@section('title', trans('users.the suppliers'))

@push('css')
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.css"
        rel="stylesheet">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                {{-- add new user trigger --}}
                <button type="button" class="btn btn-import btn-sm" data-bs-toggle="modal" data-bs-target="#addNewSupplierModal">
                    <i class="bi bi-plus"></i>
                    {{ trans('users.add new supplier') }}
                </button>
                <button class="btn btn-sm btn-outline-dark" onclick="table.ajax.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ trans('global.refresh table') }}
                </button>
            </div>

            <table class="table display table-striped table-bordered" id="usersTable" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('users.supplier name') }}</th>
                        <th>{{ trans('orders.purchases num') }}</th>
                        <th>{{ trans('global.email') }}</th>
                        <th>{{ trans('global.mobile') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('store.suppliers.create_modal')
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js">
    </script>
    <script>
        let translation = JSON.parse(@js(json_encode(trans('pagination'))));

        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('store.suppliers.get', ['type' => $targetType ?? 'all']) }}",
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ['10', '25', '50', '100', 'All']
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'num_purchases',
                    name: 'num_purchases',
                    className: 'text-center'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
            ],
            dom: "<'row'<'col-sm-12 d-flex justify-content-center'B>>" +
                "<'row-padding'>" +
                "<'row'<'col-sm-12 col-md-6 my-2'f><'col-sm-12 col-md-6 justify-content-end d-flex my-2'll>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5 my-2'i><'col-sm-12 col-md-7 justify-content-end d-flex my-2'p>>",
            buttons: [{
                    extend: 'excel',
                    className: 'btn btn-sm btn-outline-success',
                    text: 'Excel',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-sm btn-outline-success',
                    text: 'CSV',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-outline-danger',
                    text: 'PDF',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-dark',
                    text: 'Print',
                    exportOptions: {
                        columns: "thead th:not(.noExport)"
                    }
                },
                {
                    className: 'btn btn-sm btn-outline-info',
                    text: 'Reset',
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
            }
        });
        $('div.dt-buttons').addClass('w-100 d-flex justify-content-center').attr('dir', 'ltr')
        $('div.dt-buttons button').addClass('mx-1').removeClass('btn-primary').removeClass('btn-secondary')
        $('div.dt-button.buttons-collection').addClass('position-relative')

        function showEdit(url) {
            $("#editUserModalContainer").empty()
            $("#modal_freeze").modal("show")
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                dataType: 'html',
                success: function(result) {
                    $("#editUserModalContainer").html(result);
                    $('#modal_freeze').on('hidden.bs.modal', function() {
                        $('#editUserModal').modal('show').addClass('show')
                    });
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                },
                error: function(xhr, res) {
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                    alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    return false;
                }
            });
        }
    </script>
@endpush
