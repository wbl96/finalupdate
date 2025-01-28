@extends('layouts.global.app')

@section('title', trans('clients.the clients'))
@section('subTitle', $subTitle)

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('supplier.clients.store') }}" method="POST" id="form_create">
                @csrf
                <div class="row row-cols-sm-1 row-cols-md-2 g-3">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ trans('clients.name') }}</label>
                        <input type="text" @class([
                            'form-control',
                            'rounded-5',
                            'is-invalid' => $errors->has('name'),
                        ]) name="name" id="name"
                            value="{{ old('name') }}" @required(true)>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ trans('global.email') }}</label>
                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) name="email" id="email"
                            value="{{ old('email') }}" @required(true)>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="mobile" class="form-label">{{ trans('global.mobile') }}</label>
                        <div class="input-group" dir="ltr">
                            <span class="input-group-text" id="basic-addon1">+966</span>
                            <input type="number" class="form-control @error('mobile') is-invalid @enderror" id="mobile"
                                name="mobile" value="{{ old('mobile') }}">
                        </div>
                        @error('mobile')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">{{ trans('global.address') }}</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('address')]) name="address" id="address" @required(true)
                            value="{{ old('address') }}">
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">{{ trans('global.notes') }}</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mb-3 d-none">
                    <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng"
                        name="lng" value="{{ old('lng') }}">
                    <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat"
                        name="lat" value="{{ old('lat') }}">
                </div>
                <div class="mb-3">
                    <div id="map" style="width: 100%; height:500px"></div>
                </div>
                <div class="hstack">
                    <button class="btn btn-import" type="submit">
                        {{ trans('global.save') }}
                    </button>
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

        async function initMap() {
            const cairo = {
                lat: 30.064742,
                lng: 31.249509
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                scaleControl: true,
                center: cairo,
                zoom: 10,
            });
            const infowindow = new google.maps.InfoWindow();

            infowindow.setContent("<b>القاهرة</b>");

            const marker = new google.maps.Marker({
                map,
                position: cairo
            });

            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });
        }

        window.initMap = initMap;
    </script>
@endpush
