@section('addNewServiceModal')
    <div class="modal fade" id="addNewServiceModal" tabindex="-1" aria-labelledby="addNewServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addNewServiceModalLabel">{{ trans('services.add new') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.services.store') }}" method="POST" id="createService">
                        @csrf
                        <div class="mb-3">
                            <label for="name_ar" class="form-label">{{ trans('services.name.ar') }}</label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar"
                                value="{{ old('name_ar') }}" @required(true)>
                            @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name_en" class="form-label">{{ trans('services.name.en') }}</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en') }}" @required(true)>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ trans('services.status') }}</label>
                            <select class="form-select" name="status" @required(true)>
                                <option disabled selected>{{ trans('services.select status') }}</option>
                                <option value="certified" @selected(old('statuc') == 'certified')>{{ trans('services.certified') }}
                                </option>
                                <option value="not certified" @selected(old('statuc') == 'not certified')>
                                    {{ trans('services.not certified') }}</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-import" form="createService">{{ trans('global.save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@show
