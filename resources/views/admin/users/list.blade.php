@extends('layouts.admin.app')

@push('css')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="usersTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('users.name') }}</th>
                                    <th>{{ trans('users.email') }}</th>
                                    <th>{{ trans('users.mobile') }}</th>
                                    <th>{{ trans('users.type') }}</th>
                                    <th>رصيد المحفظة</th>
                                    <th>المبالغ غير المسددة</th>
                                    <th>{{ trans('global.actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal content will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admins/admin/users/store',
            type: 'GET',
            error: function (xhr, error, thrown) {
                console.log('Ajax error:', {
                    xhr: xhr,
                    error: error,
                    thrown: thrown
                });
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id', orderable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'mobile', name: 'mobile'},
            {data: 'type', name: 'type'},
            {data: 'wallet_balance', name: 'wallet_balance'},
            {data: 'unpaid_amount', name: 'unpaid_amount'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
        }
    });
});

function showEdit(url) {
    $.get(url, function(data) {
        $('#editModal .modal-content').html(data);
        $('#editModal').modal('show');
    });
}

function updateUnpaidAmount(storeId) {
    if (confirm('هل أنت متأكد من تسديد المبالغ غير المدفوعة لهذا المتجر؟')) {
        $.ajax({
            url: `/admins/admin/users/stores/${storeId}/mark-as-paid`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('تم تسديد المبالغ بنجاح');
                    $('#usersTable').DataTable().ajax.reload();
                } else {
                    alert(response.error || 'حدث خطأ غير معروف');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                alert('حدث خطأ أثناء تسديد المبالغ');
            }
        });
    }
}

function _delete(id, name) {
    if (confirm('هل أنت متأكد من حذف ' + name + '؟')) {
        $.ajax({
            url: `/admins/admin/users/stores/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                $('#usersTable').DataTable().ajax.reload();
            },
            error: function() {
                alert('حدث خطأ أثناء الحذف');
            }
        });
    }
}
</script>
@endpush
