@extends('backend.app')
@section('title', 'Create Investment')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Investment Create</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investment</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card product-sales-main">
                            <div class="card-body">
                                <form id="createInvestmentForm" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <!-- Title -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control">
                                        </div>

                                        <!-- Thumbnail -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Thumbnail</label>
                                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
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
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="asset_class" class="form-control mt-2"
                                                    placeholder="Or create new class">
                                            </div>
                                        </div>

                                        <!-- Investment Type -->
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 rounded-2 bg-light">
                                                <label class="form-label">Investment Type</label>
                                                <select name="investment_type_id" class="form-control">
                                                    <option value="">-- Select Type --</option>
                                                    @foreach ($investment_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="investment_type" class="form-control mt-2"
                                                    placeholder="Or create new type">
                                            </div>
                                        </div>

                                        <!-- Strategy -->
                                        <div class="col-md-4 mb-3">
                                            <div class="p-3 rounded-2 bg-light">
                                                <label class="form-label">Investment Strategy</label>
                                                <select name="investments_strategy_id" class="form-control">
                                                    <option value="">-- Select Strategy --</option>
                                                    @foreach ($strategies as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="investment_strategy" class="form-control mt-2"
                                                    placeholder="Or create new strategy">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <!-- Term -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Term</label>
                                            <input type="text" name="term" class="form-control">
                                        </div>
                                        <!-- Min Investment -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Min Investment</label>
                                            <input type="text" name="min_investment" class="form-control">
                                        </div>
                                        <!-- Targeted IRR -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Targeted IRR</label>
                                            <input type="text" name="targeted_irr" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Summary -->
                                    <div class="mb-3">
                                        <label class="form-label">Summary</label>
                                        <textarea name="summary" rows="4" class="description form-control"></textarea>
                                    </div>

                                    <!-- Location (Map Integration Later) -->
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Map URL, Lat, Lng -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Map URL</label>
                                            <input type="text" name="map_url" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" name="latitude" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" name="longitude" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Banker Info -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Banker Phone</label>
                                            <input type="text" name="banker_phone" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Banker Email</label>
                                            <input type="email" name="banker_email" class="form-control">
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="draft" selected>Draft</option>
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Investment</button>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // Global variables
        let map, marker, geocoder;

        // Function to initialize the map
        function initializeMap() {
            // Default to Dhaka coordinates
            const defaultLocation = [23.8103, 90.4125];

            // Initialize map
            map = L.map('map').setView(defaultLocation, 13);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add marker
            marker = L.marker(defaultLocation, {
                draggable: true
            }).addTo(map);

            // Initialize geocoder
            geocoder = L.Control.Geocoder.nominatim();

            // Add search control
            L.Control.geocoder({
                defaultMarkGeocode: false,
                geocoder: geocoder,
                position: 'topright',
                placeholder: 'Search location...',
                errorMessage: 'Location not found.'
            }).on('markgeocode', function(e) {
                const {
                    center,
                    name
                } = e.geocode;
                updateLocation(center.lat, center.lng, name);
            }).addTo(map);

            // Handle marker drag
            marker.on('dragend', function() {
                const position = marker.getLatLng();
                reverseGeocode(position.lat, position.lng);
            });

            // Handle click on map
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            // Handle search box
            $('#search-button').click(function() {
                const query = $('#search-box').val();
                if (query) {
                    geocoder.geocode(query, function(results) {
                        if (results && results.length > 0) {
                            const {
                                center,
                                name
                            } = results[0];
                            updateLocation(center.lat, center.lng, name);
                        } else {
                            toastr.error('Location not found');
                        }
                    });
                }
            });

            // Also trigger search on Enter key
            $('#search-box').keypress(function(e) {
                if (e.which === 13) {
                    $('#search-button').click();
                }
            });
        }

        Ariful Islam, [8 / 23 / 2025 3: 08 PM]
        // Update location fields
        function updateLocation(lat, lng, address) {
            $('#venue_latitude').val(lat);
            $('#venue_longitude').val(lng);
            $('#venue_location').val(address '');

            // Move marker and center map
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], 15);
        }

        // Reverse geocode coordinates to get address
        function reverseGeocode(lat, lng) {
            geocoder.reverse({
                    lat: lat,
                    lng: lng
                },
                map.getZoom(),
                function(results) {
                    if (results && results.length > 0) {
                        updateLocation(lat, lng, results[0].name);
                    } else {
                        updateLocation(lat, lng, '');
                    }
                }
            );
        }
    </script>
@endpush
