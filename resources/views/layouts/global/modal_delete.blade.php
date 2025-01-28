<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"
    data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered p-3">
        <form method="POST" class="modal-content" id="modal_delete">
            @method('delete')
            @csrf
            <div class="modal-header model-danger">
                <h5 class="modal-title" id="deleteModalLabel">
                    حذف
                </h5>
                <button type="button" @class(['btn-close', 'm-0 me-auto' => app()->getLocale()]) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل تريد بالتأكيد حذف (<span id="target_delete"></span>) ؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
            </div>
        </form>
    </div>
</div>
