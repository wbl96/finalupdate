@section('addNewSupplierModal')
    <div class="modal fade" id="addNewSupplierModal" tabindex="-1" aria-labelledby="addNewSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addNewSupplierModalLabel">{{ trans('users.add new supplier') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('store.suppliers.store') }}" method="post" id="createNewSupplierForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ trans('users.supplier name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ trans('global.email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">{{ trans('global.mobile') }}</label>
                            <div class="input-group" dir="ltr">
                                <span class="input-group-text" id="basic-addon1">+966</span>
                                <input type="number" class="form-control @error('mobile') is-invalid @enderror"
                                    id="mobile" name="mobile" value="{{ old('mobile') }}">
                            </div>
                            @error('mobile')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-import"
                        form="createNewSupplierForm">{{ trans('global.save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('global.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@show
