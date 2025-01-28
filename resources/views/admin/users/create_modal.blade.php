@section('addNewUserModal')
    <div class="modal fade" id="addNewUserModal" tabindex="-1" aria-labelledby="addNewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addNewUserModalLabel">{{ trans('admins.add new') }}</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.store') }}" method="post" id="createNewUserForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">الأسم</label>
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
                        <div class="mb-3">
                            <label for="type" class="form-label">{{ trans('users.type') }}</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                @foreach (['supplier', 'store', 'provider'] as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ trans('users.' . $type) }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 d-none">
                            <input type="text" class="form-control @error('lng') is-invalid @enderror" id="lng"
                                name="lng" value="{{ old('lng') }}">
                            <input type="text" class="form-control @error('lat') is-invalid @enderror" id="lat"
                                name="lat" value="{{ old('lat') }}">
                        </div>
                        <div class="row col-12 mb-3">
                            <div id="map" style="width: 100%; height:500px"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-import" form="createNewUserForm">{{ trans('global.save') }}</button>
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ trans('global.close') }}</button>
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
