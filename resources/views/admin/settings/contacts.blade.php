@extends('layouts.global.app')

@section('title', trans('settings.contacts info'))

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection


@section('content')
    <div class="container settings-form">
        <h2 class="text-center">{{ trans('settings.contacts info') }}</h2>
        <form action="{{ route('admin.settings.updateContactsSettings') }}" method="POST">
            @csrf
            @method('put')
            <div class="row row-cols-sm-1 row-cols-md-3 g-3 mb-3">
                <div class="col">
                    <label for="email" class="form-label">{{ trans('global.email') }}</label>
                    <input type="email" id="email" name="email" @class(['form-control', 'is-invalid' => $errors->has('android_url')])
                        placeholder="{{ trans('global.email') }}" value="{{ old('email', $contacts->email) }}">
                    @error('email')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col">
                    <label for="mobile" class="form-label">{{ trans('global.mobile') }}</label>
                    <input type="tel" id="mobile" name="mobile" @class(['form-control', 'is-invalid' => $errors->has('android_url')])
                        placeholder="{{ trans('global.mobile') }}" value="{{ old('mobile', $contacts->mobile) }}">
                    @error('mobile')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
                <div class="col">
                    <label for="whatsapp" class="form-label">{{ trans('global.whatsapp') }}</label>
                    <input type="tel" id="whatsapp" name="whatsapp" @class(['form-control', 'is-invalid' => $errors->has('android_url')])
                        placeholder="{{ trans('global.whatsapp') }}" value="{{ old('whatsapp', $contacts->whatsapp) }}">
                    @error('whatsapp')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row justify-content-center">
                <button type="submit" class="col-sm-12 col-md-2 btn btn-import">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
