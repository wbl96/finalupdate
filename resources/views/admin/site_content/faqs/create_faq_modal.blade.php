@section('addNewQuestionModal')
    <div class="modal fade" id="addNewFaqModal" tabindex="-1" aria-labelledby="addNewFaqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addNewFaqModalLabel">{{ trans('site_content.add new question') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.site-content.faq.store') }}" method="POST" id="createFaq">
                        @csrf
                        <div class="input-group">
                            <label for="question_ar" class="input-group-text">{{ trans('site_content.question') }}</label>
                            <input type="text" id="question_ar" name="question_ar" class="form-control"
                                value="{{ old('question_ar') }}" placeholder="{{ trans('site_content.enter question ar') }}"
                                required>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group">
                            <label for="answer_ar" class="input-group-text">{{ trans('site_content.answer') }}</label>
                            <input type="text" id="answer_ar" name="answer_ar" class="form-control"
                                value="{{ old('answer_ar') }}" placeholder="{{ trans('site_content.enter answer ar') }}"
                                required>
                            @error('answer_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-import" form="createFaq">{{ trans('global.save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@show
