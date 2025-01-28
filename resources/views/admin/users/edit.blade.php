@extends('layouts.global.app')

@section('title', $title)

@section('sidebar')
    @include('layouts.admin.sidebare')
@endsection
@section('topbar')
    @include('layouts.admin.topbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', [$user]) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="name" class="form-label">الأسم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="email" class="form-label">البريد الالكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" placeholder="name@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="mobile" class="form-label">{{ trans('global.mobile') }}</label>
                        <div class="input-group" dir="ltr">
                            <span class="input-group-text" id="basic-addon1">+966</span>
                            <input type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile"
                            name="mobile" value="{{ old('mobile', $user->mobile) }}">
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="type" class="form-label">{{ trans('users.type') }}</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                            @foreach (\App\Models\User::$types as $type)
                                <option value="{{ $type }}" {{ $user->type == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 d-none">
                        <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng"
                            name="lng" value="{{ old('lng', $user->lng) }}">
                        <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat"
                            name="lat" value="{{ old('lat', $user->lat) }}">
                    </div>
                    <div class="row col-12 mb-3">
                        <div id="map" style="width: 100%; height:500px"></div>
                        {{-- <button type="button" class="btn btn-danger mt-2 col-md-3" id="confirmPosition">Confirm Position</button> --}}
                    </div>
                    <div class="mb-3 col-md-6">
                        <button type="submit" class="btn btn-import">إرسال</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key"></script>
    <script src="https://unpkg.com/location-picker/dist/location-picker.min.js"></script>
    <script>
        // Initialize locationPicker plugin
        var lp = new locationPicker('map', {
            setCurrentPosition: true, // You can omit this, defaults to true
        }, {
            zoom: 15 // You can set any google map options here, zoom defaults to 15
        });

        // // Listen to button onclick event
        // confirmBtn.onclick = function () {
        //   // Get current location and show it in HTML
        //   var location = lp.getMarkerPosition();
        //   onClickPositionView.innerHTML = 'The chosen location is ' + location.lat + ',' + location.lng;
        // };

        // Listen to map idle event, listening to idle event more accurate than listening to ondrag event
        google.maps.event.addListener(lp.map, 'idle', function(event) {
            // Get current location and show it in HTML
            var location = lp.getMarkerPosition();
            $('#lng').val(location.lng)
            $('#lat').val(location.lat)
        });

        // async function initMap() {
        //     const cairo = { lat: 30.064742, lng: 31.249509 };
        //     const map = new google.maps.Map(document.getElementById("map"), {
        //         scaleControl: true,
        //         center: cairo,
        //         zoom: 10,
        //     });
        //     const infowindow = new google.maps.InfoWindow();

        //     infowindow.setContent("<b>القاهرة</b>");

        //     const marker = new google.maps.Marker({ map, position: cairo });

        //     marker.addListener("click", () => {
        //         infowindow.open(map, marker);
        //     });
        // }

        // window.initMap = initMap;
    </script>
@endpush
