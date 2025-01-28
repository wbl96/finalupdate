@section('createNewClientModal')
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="modalLabel">{{ trans('clients.add new') }}</h5>
                </div>
                <div class="modal-body">
                    <form class="row g-3" action="{{ route('supplier.clients.store') }}" method="POST" id="form_create">
                        @csrf
                        {{-- name --}}
                        <div class="col-md-6">
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
                        {{-- mobile --}}
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">{{ trans('global.mobile') }}</label>
                            <div class="input-group" dir="ltr">
                                <span class="input-group-text rounded-start-5" id="basic-addon1">+966</span>
                                <input type="number" @class([
                                    'form-control',
                                    'rounded-end-5',
                                    'is-invalid' => $errors->has('mobile'),
                                ]) id="mobile" name="mobile"
                                    value="{{ old('mobile') }}" @required(true)>
                            </div>
                            @error('mobile')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        {{-- email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ trans('global.email') }}</label>
                            <input type="email" @class([
                                'form-control',
                                'rounded-5',
                                'is-invalid' => $errors->has('email'),
                            ]) name="email" id="email"
                                value="{{ old('email') }}" @required(true)>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        {{-- map --}}
                        <div class="col-12">
                            <div class="mb-3 d-none">
                                <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng"
                                    name="lng" value="{{ old('lng') }}">
                                <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat"
                                    name="lat" value="{{ old('lat') }}">
                            </div>
                            <div class="mb-3">
                                <div id="map" style="width: 100%; height:300px"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-import" type="submit" form="form_create">
                        {{ trans('global.add new') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

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
@show
