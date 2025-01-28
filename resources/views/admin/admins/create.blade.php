<div class="modal fade" id="addNewAdminModal" tabindex="-1" aria-labelledby="addNewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewAdminModalLabel">{{ trans('admins.add new') }}</h5>
                <button type="button" @class(['btn-close', 'me-auto ms-0' => app()->getLocale() == 'ar']) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="row">
                        <div class="alert alert-danger w-100" id="print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                        <div class="alert alert-success w-100" id="print-success-msg" style="display:none">
                            <ul></ul>
                        </div>
                    </div>
                    <div class="">
                        <div class="card-body p-3">
                            <form method="post" autocomplete="off" action="{{ route('admin.admins.store') }}"
                                class="row flex-column align-items-center" id="form_create">
                                @csrf
                                <div class="mb-3 ">
                                    <label for="validationServer01"
                                        class="form-label">{{ trans('admins.name') }}</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                    <div id="name_error" class="text-danger"></div>
                                </div>
                                <div class="mb-3 ">
                                    <label for="validationServer02"
                                        class="form-label">{{ trans('global.email') }}</label>
                                    <input type="email" autocomplete="off" class="form-control" name="email"
                                        id="email" required>
                                    <div id="email_error" class="text-danger"></div>
                                </div>
                                <div class="mb-3 ">
                                    <label for="validationServer05"
                                        class="form-label">{{ trans('admins.department') }}</label>
                                    <select class="form-select" name="department_id" id="department" required>
                                        <option disabled selected>{{ trans('admins.select department') }}</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}">
                                                {{ app()->getLocale() == 'ar' ? $dept->name_ar : $dept->name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="department_error" class="text-danger"></div>
                                </div>
                                <div class="mb-3 ">
                                    <button class="btn btn-import" type="submit">حفظ</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">إلغاء</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        $(document).ready(function() {
            const arrs = ['#name', '#email', '#department']
            removeError()
            $('#form_create').on('submit', function(e) {
                e.preventDefault()
                removeError()
                $("#modal_freeze").modal("show")
                $("#print-success-msg").css('display', 'none');
                $("#print-error-msg").css('display', 'none');
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
                            $("#print-success-msg").css('display', 'block');
                            $("#print-success-msg").html(data.success);
                            appendTable(data.id);
                            $('#form_create')[0].reset();
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
                $("#print-error-msg").find("ul").html('');
                $("#print-error-msg").css('display', 'block');
                $.each(msg, function(key, value) {
                    $("#print-error-msg").find("ul").append('<li>' + value + '</li>');
                })
            }

            function appendTable(id) {
                var text = `
                    <tr>
                        <td>${$('#name').val()}</td>
                        <td class="text-center">
                            ${$('#email').val()}
                            <button class="btn btn-warning btn-sm" disabled>بانتظار تأكيد الايميل</button>
                        </td>
                        <td>${$('#department').val()}</td>
                        <td class="text-center" colspan="2">
                            حدث الصفحة للحصول على العمليات
                        </td>
                    </tr>
                `
                $('#table tbody').append(text)
            }
        })
    </script>
@endpush
