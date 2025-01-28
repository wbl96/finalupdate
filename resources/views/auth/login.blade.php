@extends('layouts.auth.auth')

@section('content')
    <div class="login-form text-center mt-5" style="height: 450px;">
        <div class="login-logo mb-4">
            <img src="{{ asset('img/logo_n.svg') }}" alt="Logo" class="img-fluid" />
        </div>

        <div class="Heading mb-4">
            <h3 class="mb-2">{{ trans('auth.login') }}</h3>
            <p class="mb-4">{{ trans('auth.choose account type note') }}</p>
        </div>

        <form method="GET" id="accountForm">
            @csrf
            <div class="mb-4">
                <select class="form-select py-2" aria-label="Select account type" id="accountType">
                    <option value="null" selected disabled>{{ trans('auth.choose account type') }}</option>
                    <option value="{{ route('admin.showlogin') }}">{{ trans('admins.the admins') }}</option>
                    <option value="{{ route('supplier.showlogin') }}">{{ trans('users.the suppliers') }}</option>
                    <option value="{{ route('store.showlogin') }}">{{ trans('users.the stores') }}</option>
                </select>
            </div>
            <button class="btn btn-import w-100 py-2" type="submit">
                <i class="bi bi-arrow-right"></i>
                {{ trans('global.next') }}
            </button>
        </form>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#accountForm').addEventListener('submit', function(evt) {
                evt.preventDefault();
                // get account type Uri
                let accountTypeUri = document.querySelector('#accountType').value;
                // check account type value
                if (accountTypeUri != "null") {
                    location.href = accountTypeUri;
                    return;
                }
                // error message
                alert("{{ trans('auth.choose account type note') }}");
            });
        });
    </script>
@endpush
