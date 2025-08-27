@extends('backend.app')
@section('title', 'Edit Investment')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet" />
    <!-- Leaflet CSS -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" />
    <link href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Investment Edit</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investment</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card product-sales-main">
                            <div class="card-body">
                                <form id="editInvestmentForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')

                                    <div class="row">
                                        <!-- Title -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $investment->title }}">
                                        </div>

                                        <!-- Thumbnail -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Thumbnail</label>
                                            <input type="file" name="thumbnail" id="thumbnailInput" class="form-control"
                                                accept="image/*">
                                            <div class="mt-2">
                                                @if ($investment->thumbnail)
                                                    <img id="thumbnailPreview"
                                                        src="{{ asset('/' . $investment->thumbnail) }}"
                                                        alt="Current thumbnail"
                                                        style="max-height:100px; border:1px solid #ddd; padding:5px;">
                                                @else
                                                    <img id="thumbnailPreview" src="" alt="Preview"
                                                        style="max-height:100px; display:none; border:1px solid #ddd; padding:5px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Asset Class -->
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 rounded-2 bg-light">
                                                <label class="form-label">Asset Class</label>
                                                <select name="asset_class_id" class="form-control">
                                                    <option value="">-- Select Class --</option>
                                                    @foreach ($asset_classes as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->asset_class_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Investment Type -->
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 rounded-2 bg-light">
                                                <label class="form-label">Investment Type</label>
                                                <select name="investment_type_id" class="form-control">
                                                    <option value="">-- Select Type --</option>
                                                    @foreach ($investment_types as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->investment_type_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Strategy -->
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 rounded-2 bg-light">
                                                <label class="form-label">Investment Strategy</label>
                                                <select name="investments_strategy_id" class="form-control">
                                                    <option value="">-- Select Strategy --</option>
                                                    @foreach ($strategies as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->investments_strategy_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Term -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Term</label>
                                            <input type="text" name="term" class="form-control"
                                                value="{{ $investment->term }}">
                                        </div>
                                        <!-- Min Investment -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Min Investment</label>
                                            <input type="text" name="min_investment" class="form-control"
                                                value="{{ $investment->min_investment }}">
                                        </div>
                                        <!-- Targeted IRR -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Targeted IRR</label>
                                            <input type="text" name="targeted_irr" class="form-control"
                                                value="{{ $investment->targeted_irr }}">
                                        </div>
                                        <!-- Targeted EPS -->
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Targeted EPS</label>
                                            <input type="text" name="targeted_eps" class="form-control"
                                                value="{{ $investment->targeted_eps }}">
                                        </div>
                                    </div>

                                    <!-- Summary -->
                                    <div class="mb-3">
                                        <label class="form-label">Summary</label>
                                        <textarea name="summary" rows="3" class="form-control summernote">{{ $investment->summary }}</textarea>
                                    </div>

                                    <!-- Location Fields -->
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="country" class="form-control"
                                                value="{{ $investment->country }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control"
                                                value="{{ $investment->state }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                value="{{ $investment->city }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" id="address" name="address" class="form-control"
                                                value="{{ $investment->address }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3 d-none">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" id="latitude" name="latitude" class="form-control"
                                                readonly value="{{ $investment->latitude }}">
                                        </div>
                                        <div class="col-md-6 mb-3 d-none">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" id="longitude" name="longitude" class="form-control"
                                                readonly value="{{ $investment->longitude }}">
                                        </div>
                                    </div>

                                    <!-- Map -->
                                    <div class="mb-3">
                                        <label class="form-label">Select Location</label>
                                        <div id="map" style="height: 200px; border-radius: 5px;"></div>
                                    </div>

                                    <!-- Toggle for map URL -->
                                    <div class="form-check form-switch mb-3" style="margin-left: 10px">
                                        <input class="form-check-input" type="checkbox" id="toggleMapUrl"
                                            {{ $investment->map_url ? 'checked' : '' }}>
                                        <label class="form-check-label" for="toggleMapUrl">Use Map URL instead</label>
                                    </div>

                                    <!-- Map URL (hidden by default) -->
                                    <div class="mb-3" id="mapUrlBox"
                                        style="{{ $investment->map_url ? '' : 'display: none;' }}">
                                        <label class="form-label">Map URL</label>
                                        <input type="text" name="map_url" class="form-control"
                                            placeholder="Enter Google Map URL" value="{{ $investment->map_url }}">
                                    </div>

                                    <!-- Banker Info -->
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Banker Phone</label>
                                            <input type="text" name="banker_phone" class="form-control"
                                                value="{{ $investment->banker_phone }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Banker Email</label>
                                            <input type="email" name="banker_email" class="form-control"
                                                value="{{ $investment->banker_email }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="draft"
                                                    {{ $investment->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="active"
                                                    {{ $investment->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="closed"
                                                    {{ $investment->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="{{ route('get.investments') }}" class="btn btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update Investment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Summernote -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // Toggle logic
        document.getElementById('toggleMapUrl').addEventListener('change', function() {
            const mapBox = document.getElementById('map');
            const mapUrlBox = document.getElementById('mapUrlBox');

            if (this.checked) {
                mapBox.style.display = 'none';
                mapUrlBox.style.display = 'block';
            } else {
                mapBox.style.display = 'block';
                mapUrlBox.style.display = 'none';
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Summernote init
            $('.summernote').summernote({
                height: 150
            });

            // Thumbnail preview
            $('#thumbnailInput').on('change', function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#thumbnailPreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Initialize Map with existing location
            const existingLat = {{ $investment->latitude ?? '23.8103' }};
            const existingLng = {{ $investment->longitude ?? '90.4125' }};
            const existingLocation = [existingLat, existingLng];

            const map = L.map('map').setView(existingLocation, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker = L.marker(existingLocation, {
                draggable: true
            }).addTo(map);
            let geocoder = L.Control.Geocoder.nominatim();

            // Add search control
            L.Control.geocoder({
                defaultMarkGeocode: false,
                geocoder: geocoder,
                position: 'topright'
            }).on('markgeocode', function(e) {
                const {
                    center,
                    name
                } = e.geocode;
                updateLocation(center.lat, center.lng, name);
            }).addTo(map);

            // Marker drag
            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                reverseGeocode(pos.lat, pos.lng);
            });

            // Map click
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            function updateLocation(lat, lng, address = '') {
                $('#latitude').val(lat);
                $('#longitude').val(lng);
                if (address) $('#address').val(address);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 15);
            }

            function reverseGeocode(lat, lng) {
                geocoder.reverse({
                    lat: lat,
                    lng: lng
                }, map.getZoom(), function(results) {
                    if (results && results.length > 0) {
                        updateLocation(lat, lng, results[0].name);
                    } else {
                        updateLocation(lat, lng);
                    }
                });
            }

            // Form submit AJAX
            $('#editInvestmentForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('update.investment', $investment->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toastr.success('Investment updated successfully!');

                        // redirect after success
                        setTimeout(function() {
                            window.location.href = "{{ route('get.investments') }}";
                        }, 1000);
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endpush
