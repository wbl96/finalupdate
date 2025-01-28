@extends('layouts.global.app')

@section('title', trans('admins.the admins'))

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.2/css/searchPanes.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.5/css/select.dataTables.css">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                {{-- add new admin trigger --}}
                <button type="button" class="btn btn-sm btn-import" data-bs-toggle="modal"
                    data-bs-target="#addNewAdminModal">
                    <i class="bi bi-plus"></i>
                    {{ trans('admins.add new') }}
                </button>
                <button class="btn btn-sm btn-outline-dark" onclick="table.ajax.reload()">
                    <i class="bi bi-arrow-clockwise"></i>
                    {{ trans('global.refresh table') }}
                </button>
            </div>
            {{-- add new admin modal --}}
            @include('admin.admins.create')

            <table class="table display table-striped table-bordered" id="adminsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center">{{ trans('admins.name') }}</th>
                        <th class="text-center">{{ trans('global.email') }}</th>
                        <th class="text-center">{{ trans('admins.department') }}</th>
                        <th class="noExport text-center">{{ trans('admins.permissions') }}</th>
                        <th class="noExport"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th class="text-center">{{ trans('admins.name') }}</th>
                        <th class="text-center">{{ trans('global.email') }}</th>
                        <th class="text-center">{{ trans('admins.department') }}</th>
                        <th class="noExport text-center">{{ trans('admins.permissions') }}</th>
                        <th class="noExport"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div id="show_permission"></div>
    <div id="show_edit"></div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.4/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.2/js/dataTables.searchPanes.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.2/js/searchPanes.dataTables.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.5/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.5/js/select.dataTables.js"></script>

    <script>
        let translation = JSON.parse(@js(json_encode(trans('pagination'))));
        var table = $('#adminsTable').DataTable({
            scrollX: true,
            scrollY: 'auto',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{!! route('admin.admins.getAdmins') !!}",
            columns: [{
                data: 'DT_RowIndex',
                searchable: false
            }, {
                data: 'name'
            }, {
                data: 'email'
            }, {
                data: 'department'
            }, {
                data: 'permissions',
                orderable: false,
                searchable: false,
            }, {
                data: 'controls',
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
                "<'row'<'col-sm-12 col-md-6 my-2'f><'col-sm-12 col-md-6 justify-content-end d-flex my-2'l>>" +
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
            },
            initComplete: function() {
                this.api()
                    .columns(3)
                    .data()
                    .each(function(data) {
                        // column
                        let column = this.column(3);
                        // option
                        let options = '';
                        data.forEach(d => {
                            options += `<option value="${d}">${d}</option>`;
                        });
                        // Create input element and add event listener
                        $('<select class="form-select">' +
                                '<option value="">{!! trans('global.select') !!}</option>' +
                                options + '</select>')
                            .appendTo($(column.footer()).empty())
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    })
            }
        });
        $('div.dt-buttons').addClass('w-100 d-flex justify-content-center').attr('dir', 'ltr')
        $('div.dt-buttons button').addClass('mx-1').removeClass('btn-primary').removeClass('btn-secondary')
        $('div.dt-button.buttons-collection').addClass('position-relative')


        function showPermissions(id, name) {
            // prapare permissions url
            let url = "{{ route('admin.admins.showPermissionsModal', ['id' => ':TARGET']) }}";
            // empty permissions modal
            $("#show_permission").empty()
            var btnModalPermissionEdit_click = false;
            // show modal
            $("#modal_freeze").modal("show")
            // ajax request to get permissions
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url.replace(':TARGET', id),
                data: {
                    'token': '{{ csrf_token() }}',
                    'dataType': 'html'
                },
                type: 'GET',
                success: function(result) {
                    // build permissions html
                    $("#show_permission").html(result);
                    // $(':checkbox').prop('disabled', true);
                    // put admin name
                    $('#admin_name').text(name)
                    // get all permissions
                    var _permissions = $('#permisstions_data').data('permissions')
                    // loop on permissions
                    $.each(_permissions, function(k, v) {
                        $('input[name="' + v.name + '"]').prop('checked', true);
                    });
                    // show permissions modal
                    $('#permissionModal').modal('show')
                    // assign admin id to form
                    $('#admin_id').val(id)
                    // put admin id in form action
                    $('#modal_permission').prop('action', $('#modal_permission').prop('action').replace(
                        ':TARGET', id))
                    // add event (click) on save button
                    $('#btn_save').on('click', function(e) {
                        if (btnModalPermissionEdit_click) {
                            $('#modal_permission').submit()
                        }
                        $(this).text('حفظ التعديلات')
                        $(':checkbox').prop('disabled', false);
                        btnModalPermissionEdit_click = true
                    })
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                    return false;
                }
            });
        }

        function showEdit(url) {
            $("#show_edit").empty()
            $("#modal_freeze").modal("show")
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                dataType: 'html',
                success: function(result) {
                    if (result.status == 'error') {
                        setTimeout(() => {
                            $("#modal_freeze").modal("hide")
                        }, 1000);
                        alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                        return false;
                    }
                    $("#show_edit").html(result);
                    $('#EditModal').modal('show')
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                },
                error: function(xhr, res) {
                    console.log(xhr)
                }
            });
        }

        function _delete(id, name) {
            $('#target_delete').text(name)
            $('#modal_delete').get(0).setAttribute('action', "{{ url('admin/admins/') }}" + '/' + id);
            $('#deleteModal').modal('show')
        }

        function sendRestPassword(url) {
            $("#modal_freeze").modal("show")
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                cache: false,
                data: {
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                    if ($.isEmptyObject(data.error)) {
                        alert(data.success)
                    } else {
                        console.log(data.error);
                    }
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
