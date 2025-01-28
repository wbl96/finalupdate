@extends('layouts.global.app')

@section('title', trans('services.the services'))

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
                {{-- add new service trigger --}}
                <button class="add-service-btn btn btn-import btn-sm" data-bs-toggle="modal"
                    data-bs-target="#addNewServiceModal">
                    <i class="bi bi-plus"></i>
                    {{ trans('services.add new') }}
                </button>
                {{-- refresh table button --}}
                <button class="btn btn-sm btn-outline-dark" onclick="table.ajax.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ trans('global.refresh table') }}
                </button>
            </div>
            {{-- table --}}
            <table class="table display table-striped table-bordered" id="servicesTable" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">{{ trans('services.name.ar') }}</th>
                        <th class="text-center">{{ trans('services.name.en') }}</th>
                        <th class="text-center">{{ trans('services.status') }}</th>
                        <th class="text-center">{{ trans('global.created by') }}</th>
                        <th class="text-center">{{ trans('global.updated by') }}</th>
                        <th class="noExport"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="editServiceModalContainer"></div>
    {{-- import service modal --}}
    @include('admin.services.create_modal')
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.1.7/b-3.1.2/b-colvis-3.1.2/b-html5-3.1.2/b-print-3.1.2/cr-2.0.4/fc-5.0.2/fh-4.0.1/kt-2.12.1/r-3.0.3/sc-2.4.3/sb-1.8.0/sp-2.3.2/sl-2.1.0/sr-1.4.1/datatables.min.js">
    </script>
    <script>
        let translation = JSON.parse(@js(json_encode(trans('pagination'))));
        var table = $('#servicesTable').DataTable({
            scrollX: true,
            scrollY: 'auto',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{!! route('admin.services.getServices') !!}",
            columns: [{
                data: 'DT_RowIndex',
                searchable: false
            }, {
                data: 'name_ar',
                name: 'name_ar'
            }, {
                data: 'name_en',
                name: 'name_en'
            }, {
                data: 'status',
                name: 'status'
            }, {
                data: 'created_by',
                name: 'created_by',
            }, {
                data: 'updated_by',
                name: 'updated_by',
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

        function _delete(id, name) {
            // url
            let url = `{{ route('admin.services.destroy', [':TARGET']) }}`;
            url = url.replace(':TARGET', id);
            $('#target_delete').text(name)
            $('#modal_delete').get(0).setAttribute('action', url);
            $('#deleteModal').modal('show')
        }

        function showEdit(url) {
            $("#editServiceModalContainer").empty()
            $("#modal_freeze").modal("show")
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                dataType: 'html',
                success: function(result) {
                    $("#editServiceModalContainer").html(result);
                    $('#modal_freeze').on('hidden.bs.modal', function() {
                        $('#editServiceModal').modal('show').addClass('show')
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
