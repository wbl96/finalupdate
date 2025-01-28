<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditModalLabel">تعديل بيانات إداري </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">

                    <div class="row">
                        <div class="alert alert-danger w-100" id="print_error_msg_Edit" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="alert alert-success w-100" id="print_success_msg_Edit" style="display:none">
                            <ul></ul>
                        </div>
                    </div>

                    <div class="">
                        <div class="card-body p-3">
                            <form method="post" autocomplete="off"
                                action="{{ route('admin.admins.update', $admin->id) }}"
                                class="row flex-column align-items-center" id="form_edit">
                                @csrf
                                @method('PUT')
                                <div class="mb-3 ">
                                    <label for="name_edit" class="form-label">اسم الإداري</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $admin->name }}" id="name_edit" required>
                                    <div id="name_edit_error" class="text-danger"></div>
                                </div>
                                <div class="mb-3 ">
                                    <label for="email_edit" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" autocomplete="off" class="form-control" name="email"
                                        id="email_edit" value="{{ $admin->email }}" required>
                                    <div id="email_edit_error" class="text-danger"></div>
                                </div>
                                @if (auth()->id() == $admin->id)
                                    <div class="mb-3 ">
                                        <label for="password_edit" class="form-label">كلمة المرور</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password"
                                                id="password_edit">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="showPassword('password_edit')">
                                                <i class="fa fa-eye password_edit"></i>
                                            </button>
                                        </div>
                                        <div id="password_edit_error" class="text-danger"></div>
                                    </div>
                                    <div class="mb-3 ">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="password_confirmation">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="showPassword('password_confirmation')">
                                                <i class="fa fa-eye password_confirmation"></i>
                                            </button>
                                        </div>
                                        <div id="password_confirmation_error" class="text-danger"></div>
                                    </div>
                                @endif
                                <div class="mb-3 ">
                                    <label for="department_edit" class="form-label">الوظيفة</label>
                                    <select class="form-select" name="department_id" id="department_edit" required>
                                        <option disabled selected>{{ trans('admins.select department') }}</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}" @selected($dept->is($admin->department))>
                                                {{ app()->getLocale() == 'ar' ? $dept->name_ar : $dept->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="department_edit_error" class="text-danger"></div>
                                </div>
                                <div class="mb-3 ">
                                    <button class="btn btn-import" type="submit">تعديل</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">إلغاء</button>
                                </div>
                            </form>
                        </div>
                        <input type="hidden" id="url_edit" value="{{ route('admin.admins.edit', [$admin->id]) }}">
                        <input type="hidden" id="sendRestPassword" value="{{ url('admin/sendRestPassword') }}/">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    /**
     * @argument ele is id of input
     */
    function showPassword(ele) {
        // select input and its icon
        var input = $('input#' + ele)
        var icon = $('i.' + ele)

        if (input.attr('type') === 'password') {
            input.attr('type', 'text')
            icon.removeClass('fa-eye')
            icon.addClass('fa-eye-slash')
        } else {
            input.attr('type', 'password')
            icon.removeClass('fa-eye-slash')
            icon.addClass('fa-eye')
        }
    }

    $(document).ready(function() {
        const arrs = ['#name', '#email', '#department']
        removeError()
        $('#form_edit').on('submit', function(e) {
            e.preventDefault()
            removeError()
            $("#modal_freeze").modal("show")
            $("#print_success_msg_Edit").css('display', 'none');
            $("#print_error_msg_Edit").css('display', 'none');
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                dataType: 'json',
                cache: false,
                data: $(this).serialize(),
                success: function(data) {
                    setTimeout(() => {
                        $("#modal_freeze").modal("hide")
                    }, 1000);
                    if ($.isEmptyObject(data.error)) {
                        $("#print_success_msg_Edit").css('display', 'block');
                        $("#print_success_msg_Edit").html(data.success);
                        editTrTable();
                        // $('#form_edit')[0].reset();
                    } else {
                        printErrorMsg(data.error);
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
        })

        function removeError() {
            $.each(arrs, function(index, element) {
                $(element).removeClass('is-invalid')
                $(element + "_error").html('')
            })
        }

        function printErrorMsg(msg) {
            $("#print_error_msg_Edit").find("ul").html('');
            $("#print_error_msg_Edit").css('display', 'block');
            $.each(msg, function(key, value) {
                $("#print_error_msg_Edit").find("ul").append('<li>' + value + '</li>');
            })
        }

        function editTrTable() {
            var id = '{{ $admin->id }}'
            var tr = $('#tr_' + id)
            tr.empty()
            var url = $('#url_edit').val()
            var text = `
                <td>${$('#name_edit').val()}</td>
                <td class="text-center">${$('#email_edit').val()}</td>
                <td class="text-center">${$('#department_edit').val()}</td>

                <td class="text-center">
                    <button class="btn btn-sm btn-outline-dark"
                        onclick="showPermission('${id}', '${$('#name_edit').val()}')">مشاهدة
                        الصلاحيات</button>
                </td>

                <td class="text-center">
                    <button class="btn btn-sm btn-info m-1" onclick="showEdit('${url}')">
                        <i class="fa fa-eye"></i> تعديل
                    </button>
                    <button class="btn btn-sm btn-danger m-1"
                        onclick="_delete('${id}', '${$('#name_edit').val()}')">
                        <i class="fa fa-trash-alt"></i> حذف
                    </button>
                    <button class="btn btn-warning btn-sm m-1"
                            onclick="sendRestPassword('${$('#sendRestPassword').val()}${$('#email_edit').val()}')">
                        إعادة ضبط كلمة المرور
                    </button>
                </td>
            `
            tr.append(text)
        }
    })
</script>
