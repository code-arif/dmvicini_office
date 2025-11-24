@extends('backend.app')
@section('title', 'Edit Investment')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Edit Investment</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('investment.list') }}">Investments</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Step Wizard -->
                                <div class="step-wizard">
                                    <div class="step-item active" data-step="1">
                                        <div class="step-circle">1</div>
                                        <div class="step-label">Basic Info</div>
                                    </div>
                                    <div class="step-item" data-step="2">
                                        <div class="step-circle">2</div>
                                        <div class="step-label">Location</div>
                                    </div>
                                    <div class="step-item" data-step="3">
                                        <div class="step-circle">3</div>
                                        <div class="step-label">Highlights</div>
                                    </div>
                                    <div class="step-item" data-step="4">
                                        <div class="step-circle">4</div>
                                        <div class="step-label">Documents</div>
                                    </div>
                                    <div class="step-item" data-step="5">
                                        <div class="step-circle">5</div>
                                        <div class="step-label">Gallery</div>
                                    </div>
                                    <div class="step-item" data-step="6">
                                        <div class="step-circle">6</div>
                                        <div class="step-label">Disclaimers</div>
                                    </div>
                                </div>

                                <form id="investmentForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')

                                    <!-- Step 1: Basic Info -->
                                    <div class="step-content active" data-step="1">
                                        <h4 class="mb-4">Basic Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ $investment->title }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Mountain Image</label>
                                                <input type="file" name="mountain_image" class="form-control"
                                                    accept="image/*">
                                                @if ($investment->mountain_image)
                                                    <img src="{{ asset($investment->mountain_image) }}"
                                                        id="mountainImagePreview" class="mountain-image-preview"
                                                        style="display: block;">
                                                @else
                                                    <img id="mountainImagePreview" class="mountain-image-preview">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Asset Class</label>
                                                <select name="asset_class_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($asset_classes as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->asset_class_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Investment Type</label>
                                                <select name="investment_type_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($investment_types as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->investment_type_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Investment Strategy</label>
                                                <select name="investments_strategy_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($strategies as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->investments_strategy_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Tax Strategy</label>
                                                <select name="tax_strategie_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($tax_strategies as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $investment->tax_strategies_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Term</label>
                                                <input type="text" name="term" class="form-control"
                                                    value="{{ $investment->term }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Min Investment</label>
                                                <input type="text" name="min_investment" class="form-control"
                                                    value="{{ $investment->min_investment }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Status <span
                                                        class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="draft"
                                                        {{ $investment->status == 'draft' ? 'selected' : '' }}>Draft
                                                    </option>
                                                    <option value="active"
                                                        {{ $investment->status == 'active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="closed"
                                                        {{ $investment->status == 'closed' ? 'selected' : '' }}>Closed
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Investment Details</label>
                                            <textarea name="investment_details" id="investmentDetails" class="form-control">{{ $investment->investment_details }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Step 2: Location -->
                                    <div class="step-content" data-step="2">
                                        <h4 class="mb-4">Location Details</h4>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" name="country" id="country" class="form-control"
                                                    value="{{ $investment->country }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" name="state" id="state" class="form-control"
                                                    value="{{ $investment->state }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    value="{{ $investment->city }}">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    value="{{ $investment->address }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Search Location</label>
                                            <input type="text" id="searchBox" class="form-control"
                                                placeholder="Search for a place...">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Select on Map</label>
                                            <div id="map"></div>
                                        </div>

                                        <input type="hidden" name="latitude" id="latitude"
                                            value="{{ $investment->latitude }}">
                                        <input type="hidden" name="longitude" id="longitude"
                                            value="{{ $investment->longitude }}">
                                    </div>

                                    <!-- Step 3: Highlights -->
                                    <div class="step-content" data-step="3">
                                        <h4 class="mb-4">Investment Highlights</h4>
                                        <div class="mb-3">
                                            <label class="form-label">Overview</label>
                                            <textarea name="overview" id="overview" class="form-control">{{ $investment->highlight->overview ?? '' }}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Targeted IRR</label>
                                                <input type="text" name="targeted_irr" class="form-control"
                                                    value="{{ $investment->highlight->targeted_irr ?? '' }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tax Document</label>
                                                <input type="text" name="tax_doc" class="form-control"
                                                    value="{{ $investment->highlight->tax_doc ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Investor Waterfall</label>
                                                <textarea name="investor_waterfall" id="investor_waterfall" class="form-control">{{ $investment->highlight->investor_waterfall ?? '' }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Promoted Interest</label>
                                                <textarea name="promoted_interest" id="promoted_interest" class="form-control">{{ $investment->highlight->promoted_interest ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Asset Management Fee</label>
                                                <input type="text" name="asset_management_fee" class="form-control"
                                                    value="{{ $investment->highlight->asset_management_fee ?? '' }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Organizational & Offering Fee</label>
                                                <input type="text" name="organizational_and_offering_fee"
                                                    class="form-control"
                                                    value="{{ $investment->highlight->organizational_and_offering_fee ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Acquisition Fee</label>
                                                <input type="text" name="acquisition_fee" class="form-control"
                                                    value="{{ $investment->highlight->acquisition_fee ?? '' }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Disposition Fee</label>
                                                <input type="text" name="disposition_fee" class="form-control"
                                                    value="{{ $investment->highlight->disposition_fee ?? '' }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Fund Administration Fee</label>
                                                <input type="text" name="fund_administration_fee" class="form-control"
                                                    value="{{ $investment->highlight->fund_administration_fee ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4: Documents -->
                                    <div class="step-content" data-step="4">
                                        <h4 class="mb-4">Documents</h4>

                                        <!-- Existing Documents -->
                                        <div class="mb-4">
                                            <h5>Existing Documents</h5>
                                            <div id="existingDocuments" class="list-group">
                                                @foreach ($investment->documents as $doc)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center"
                                                        data-doc-id="{{ $doc->id }}">
                                                        <span><i class="fa fa-file"></i> {{ $doc->name }}</span>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteExistingDocument({{ $doc->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- New Documents -->
                                        <div id="documentsList" class="mb-3"></div>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8 mb-3">
                                                        <label class="form-label">Document Name</label>
                                                        <input type="text" id="docName" class="form-control">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">File</label>
                                                        <input type="file" id="docFile" class="form-control"
                                                            accept=".pdf,.doc,.docx,.xlsx,.ppt,.pptx">
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="addDocumentBtn">
                                                    <i class="fa fa-plus"></i> Add New Document
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 5: Gallery -->
                                    <div class="step-content" data-step="5">
                                        <h4 class="mb-4">Gallery Images</h4>

                                        <!-- Existing Images -->
                                        <div class="mb-4">
                                            <h5>Existing Images</h5>
                                            <div id="existingImages" class="image-preview-grid">
                                                @foreach ($investment->images as $img)
                                                    <div class="image-preview-item" data-img-id="{{ $img->id }}">
                                                        <img src="{{ asset($img->image_url) }}" alt="Gallery Image">
                                                        <button type="button" class="remove-btn"
                                                            onclick="deleteExistingImage({{ $img->id }})">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- New Images -->
                                        <div class="mb-3">
                                            <label class="form-label">Add New Images</label>
                                            <input type="file" id="galleryImages" class="form-control"
                                                accept="image/*" multiple>
                                        </div>
                                        <div id="imagePreviewGrid" class="image-preview-grid"></div>
                                    </div>

                                    <!-- Step 6: Disclaimers -->
                                    {{-- <div class="step-content" data-step="6">
                                        <h4 class="mb-4">Disclaimers</h4>

                                        <!-- Existing Disclaimers -->
                                        <div class="mb-4">
                                            <h5>Existing Disclaimers</h5>
                                            <div id="existingDisclaimers" class="list-group">
                                                @foreach ($investment->disclaimers as $disc)
                                                    <div class="list-group-item" data-disc-id="{{ $disc->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h6>{{ $disc->title }}</h6>
                                                                @if ($disc->description)
                                                                    <p class="mb-0 text-muted">{{ $disc->description }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="deleteExistingDisclaimer({{ $disc->id }})">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- New Disclaimers -->
                                        <div id="disclaimersList" class="mb-3"></div>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Title <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="disclaimerTitle" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea id="disclaimerDescription" class="form-control" rows="3"></textarea>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="addDisclaimerBtn">
                                                    <i class="fa fa-plus"></i> Add New Disclaimer
                                                </button>
                                            </div>
                                        </div>
                                    </div> --}}


                                    <!-- Step 6: Disclaimer (Single) in Edit Page -->
                                    <div class="step-content" data-step="6">
                                        <h4 class="mb-4">Investment Disclaimer</h4>

                                        <div class="mb-3">
                                            <label class="form-label">Disclaimer Content</label>
                                            <textarea name="disclaimer_description" id="disclaimerDescription" class="form-control">{{ $investment->disclaimer->description ?? '' }}</textarea>
                                            <small class="text-muted">Add any legal disclaimers, risk warnings, or
                                                important notices here.</small>
                                        </div>
                                    </div>

                                    <!-- Navigation Buttons -->
                                    <div class="nav-buttons">
                                        <button type="button" class="btn btn-secondary" id="prevBtn"
                                            style="display:none;">
                                            <i class="fa fa-arrow-left"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-primary" id="nextBtn">
                                            Next <i class="fa fa-arrow-right"></i>
                                        </button>
                                        <button type="button" class="btn btn-success" id="submitBtn"
                                            style="display:none;">
                                            <i class="fa fa-check"></i> Update Investment
                                        </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfGOjmqKtEBRsfVN9szUo_tac20wcI9HM&libraries=places">
    </script>

    <script>
        window.routes = {
            highlightStore: "{{ route('investment.highlight.store', $investment->id) }}",
            documentStore: "{{ route('investment.document.store', $investment->id) }}",
            imagesStore: "{{ route('investment.images.store', $investment->id) }}",
            disclaimerStore: "{{ route('investment.disclaimer.store', $investment->id) }}",
            updateBasic: "{{ route('investment.update', $investment->id) }}",
            deleteDoc: "/admin/deal/document/delete/",
            deleteImage: "/admin/deal/image/delete/"
        };

        const investmentId = {{ $investment->id }};
    </script>


    <script>
        let currentStep = 1;
        let totalSteps = 6;
        let map, marker, geocoder, searchBox;
        let documents = [];
        let images = [];
        let disclaimers = [];

        $(document).ready(function() {
            // Initialize Summernote
            $('#investmentDetails, #overview, #investor_waterfall, #promoted_interest, #disclaimerDescription')
                .summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });

            // Initialize Google Map
            initMap();

            // Mountain image preview
            $('input[name="mountain_image"]').on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = e => {
                        $('#mountainImagePreview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Navigation
            $('#nextBtn').on('click', () => {
                if (currentStep === 1) {
                    updateBasicInfo();
                } else if (currentStep === 2) {
                    updateLocation();
                } else {
                    navigateStep(1);
                }
            });

            $('#prevBtn').on('click', () => navigateStep(-1));
            $('#submitBtn').on('click', submitInvestment);
            $('#addDocumentBtn').on('click', addDocument);
            $('#galleryImages').on('change', previewGalleryImages);
        });

        function initMap() {
            const lat = parseFloat($('#latitude').val()) || 23.8103;
            const lng = parseFloat($('#longitude').val()) || 90.4125;
            const position = {
                lat,
                lng
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: position,
                zoom: 13,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            marker = new google.maps.Marker({
                map: map,
                position: position,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            geocoder = new google.maps.Geocoder();
            const input = document.getElementById('searchBox');
            searchBox = new google.maps.places.SearchBox(input);

            map.addListener('bounds_changed', () => {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener('places_changed', () => {
                const places = searchBox.getPlaces();
                if (places.length == 0) return;

                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;

                marker.setPosition(place.geometry.location);
                map.setCenter(place.geometry.location);
                map.setZoom(15);

                updateLocationFields(place);
            });

            marker.addListener('dragend', function() {
                reverseGeocode(marker.getPosition());
            });

            map.addListener('click', function(e) {
                marker.setPosition(e.latLng);
                reverseGeocode(e.latLng);
            });

            console.log('Map initialized for edit page');
        }

        function reverseGeocode(location) {
            geocoder.geocode({
                location: location
            }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    updateLocationFields({
                        geometry: {
                            location: location
                        },
                        formatted_address: results[0].formatted_address,
                        address_components: results[0].address_components
                    });
                }
            });
        }

        function updateLocationFields(place) {
            $('#latitude').val(place.geometry.location.lat());
            $('#longitude').val(place.geometry.location.lng());
            $('#address').val(place.formatted_address || '');

            if (place.address_components) {
                $('#country').val('');
                $('#state').val('');
                $('#city').val('');

                place.address_components.forEach(component => {
                    const types = component.types;

                    if (types.includes('country')) {
                        $('#country').val(component.long_name);
                    }
                    if (types.includes('administrative_area_level_1')) {
                        $('#state').val(component.long_name);
                    }
                    if (types.includes('locality')) {
                        $('#city').val(component.long_name);
                    } else if (types.includes('administrative_area_level_2') && !$('#city').val()) {
                        $('#city').val(component.long_name);
                    } else if (types.includes('sublocality_level_1') && !$('#city').val()) {
                        $('#city').val(component.long_name);
                    }
                });

                console.log('Location fields updated:', {
                    country: $('#country').val(),
                    state: $('#state').val(),
                    city: $('#city').val(),
                    address: $('#address').val()
                });

                toastr.success('Location updated successfully');
            }
        }

        function updateBasicInfo() {
            const formData = new FormData($('#investmentForm')[0]);

            // Explicitly add location data
            formData.set('country', $('#country').val() || '');
            formData.set('state', $('#state').val() || '');
            formData.set('city', $('#city').val() || '');
            formData.set('address', $('#address').val() || '');
            formData.set('latitude', $('#latitude').val() || '');
            formData.set('longitude', $('#longitude').val() || '');

            console.log('Updating with location:', {
                country: formData.get('country'),
                state: formData.get('state'),
                city: formData.get('city'),
                address: formData.get('address'),
                latitude: formData.get('latitude'),
                longitude: formData.get('longitude')
            });

            NProgress.start();
            $.ajax({
                url: window.routes.updateBasic,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        toastr.success(res.message);
                        navigateStep(1);
                    }
                },
                error: function(xhr) {
                    NProgress.done();
                    let message = 'Failed to update basic info';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                }
            });
        }

        // New function to update location from Step 2
        function updateLocation() {
            const locationData = new FormData();
            locationData.append('_token', "{{ csrf_token() }}");
            locationData.append('_method', 'POST');
            locationData.append('country', $('#country').val() || '');
            locationData.append('state', $('#state').val() || '');
            locationData.append('city', $('#city').val() || '');
            locationData.append('address', $('#address').val() || '');
            locationData.append('latitude', $('#latitude').val() || '');
            locationData.append('longitude', $('#longitude').val() || '');

            console.log('Updating location from Step 2:', {
                country: $('#country').val(),
                state: $('#state').val(),
                city: $('#city').val(),
                address: $('#address').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val()
            });

            NProgress.start();
            $.ajax({
                url: window.routes.updateBasic,
                type: "POST",
                data: locationData,
                processData: false,
                contentType: false,
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        toastr.success('Location updated successfully');
                        navigateStep(1);
                    }
                },
                error: function(xhr) {
                    NProgress.done();
                    console.error('Location update error:', xhr);
                    toastr.error('Failed to update location');
                }
            });
        }

        // Rest of the functions remain same...
        function navigateStep(direction) {
            goToStep(currentStep + direction);
        }

        function goToStep(step) {
            if (step < 1 || step > totalSteps) return;

            $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
            $(`.step-item[data-step="${currentStep}"]`).removeClass('active').addClass('completed');

            $(`.step-content[data-step="${step}"]`).addClass('active');
            $(`.step-item[data-step="${step}"]`).addClass('active');

            currentStep = step;

            $('#prevBtn').toggle(currentStep > 1);
            $('#nextBtn').toggle(currentStep < totalSteps);
            $('#submitBtn').toggle(currentStep === totalSteps);
        }

        // ... rest of your existing functions (addDocument, deleteExistingDocument, etc.)
        // Delete existing document
        function deleteExistingDocument(docId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    NProgress.start();
                    $.ajax({
                        url: window.routes.deleteDoc + docId,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            NProgress.done();
                            if (res.success) {
                                $(`#existingDocuments [data-doc-id="${docId}"]`).remove();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message || 'Document deleted successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: res.message || 'Failed to delete document',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            NProgress.done();
                            console.error('Delete error:', xhr);

                            let errorMsg = 'Failed to delete document';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                title: 'Error!',
                                text: errorMsg,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        // Delete existing image
        function deleteExistingImage(imgId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This image will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    NProgress.start();
                    $.ajax({
                        url: window.routes.deleteImage + imgId,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            NProgress.done();
                            if (res.success) {
                                $(`#existingImages [data-img-id="${imgId}"]`).remove();

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: res.message || 'Image deleted successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: res.message || 'Failed to delete image',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            NProgress.done();
                            console.error('Delete error:', xhr);

                            let errorMsg = 'Failed to delete image';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                title: 'Error!',
                                text: errorMsg,
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        // Delete existing disclaimer
        function deleteExistingDisclaimer(discId) {
            if (!confirm('Are you sure you want to delete this disclaimer?')) return;

            NProgress.start();
            $.ajax({
                url: `/deal/disclaimer/${discId}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        $(`#existingDisclaimers [data-disc-id="${discId}"]`).remove();
                        toastr.success('Disclaimer deleted successfully');
                    }
                },
                error: function() {
                    NProgress.done();
                    toastr.error('Failed to delete disclaimer');
                }
            });
        }

        function addDocument() {
            const name = $('#docName').val().trim();
            const fileInput = $('#docFile')[0];
            const file = fileInput.files[0];

            if (!name || !file) {
                toastr.error('Please provide document name and file');
                return;
            }

            documents.push({
                name,
                file
            });
            renderDocuments();
            $('#docName').val('');
            $('#docFile').val('');
        }

        function renderDocuments() {
            let html = '<div class="list-group">';
            documents.forEach((doc, index) => {
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fa fa-file"></i> ${doc.name}</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeDocument(${index})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            html += '</div>';
            $('#documentsList').html(html);
        }

        function removeDocument(index) {
            documents.splice(index, 1);
            renderDocuments();
        }

        function previewGalleryImages() {
            const files = this.files;
            images = Array.from(files);

            let html = '';
            images.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(`#img-preview-${index}`).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                html += `
                    <div class="image-preview-item">
                        <img id="img-preview-${index}" src="" alt="Preview">
                        <button type="button" class="remove-btn" onclick="removeImage(${index})">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                `;
            });

            $('#imagePreviewGrid').html(html);
        }

        function removeImage(index) {
            images.splice(index, 1);
            const dt = new DataTransfer();
            images.forEach(file => dt.items.add(file));
            $('#galleryImages')[0].files = dt.files;
            previewGalleryImages.call($('#galleryImages')[0]);
        }

        function addDisclaimer() {
            const title = $('#disclaimerTitle').val().trim();
            const description = $('#disclaimerDescription').val().trim();

            if (!title) {
                toastr.error('Please provide disclaimer title');
                return;
            }

            disclaimers.push({
                title,
                description
            });
            renderDisclaimers();
            $('#disclaimerTitle').val('');
            $('#disclaimerDescription').val('');
        }

        function renderDisclaimers() {
            let html = '<div class="list-group">';
            disclaimers.forEach((disc, index) => {
                html += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                ${disc.description ? `<p class="mb-0 text-muted">${disc.description}</p>` : ''}
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeDisclaimer(${index})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            $('#disclaimersList').html(html);
        }

        function removeDisclaimer(index) {
            disclaimers.splice(index, 1);
            renderDisclaimers();
        }


        // Update submitInvestment function for edit page
        function submitInvestment() {
            NProgress.start();

            const promises = [];

            // 1. Save highlights
            const highlightData = {
                overview: $('#overview').summernote('code'),
                targeted_irr: $('[name="targeted_irr"]').val(),
                tax_doc: $('[name="tax_doc"]').val(),
                investor_waterfall: $('#investor_waterfall').summernote('code'),
                promoted_interest: $('#promoted_interest').summernote('code'),
                asset_management_fee: $('[name="asset_management_fee"]').val(),
                organizational_and_offering_fee: $('[name="organizational_and_offering_fee"]').val(),
                acquisition_fee: $('[name="acquisition_fee"]').val(),
                disposition_fee: $('[name="disposition_fee"]').val(),
                fund_administration_fee: $('[name="fund_administration_fee"]').val(),
                _token: "{{ csrf_token() }}"
            };

            promises.push(
                $.ajax({
                    url: window.routes.highlightStore,
                    type: "POST",
                    data: highlightData
                })
            );

            // 2. Upload new documents
            documents.forEach(doc => {
                const formData = new FormData();
                formData.append('name', doc.name);
                formData.append('file', doc.file);
                formData.append('_token', "{{ csrf_token() }}");

                promises.push(
                    $.ajax({
                        url: window.routes.documentStore,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false
                    })
                );
            });

            // 3. Upload new images
            if (images.length > 0) {
                const imageFormData = new FormData();
                images.forEach(img => imageFormData.append('images[]', img));
                imageFormData.append('_token', "{{ csrf_token() }}");

                promises.push(
                    $.ajax({
                        url: window.routes.imagesStore,
                        type: "POST",
                        data: imageFormData,
                        processData: false,
                        contentType: false
                    })
                );
            }

            // 4. Save disclaimer (single)
            const disclaimerContent = $('#disclaimerDescription').summernote('code');
            if (disclaimerContent && disclaimerContent.trim() !== '') {
                promises.push(
                    $.ajax({
                        url: window.routes.disclaimerStore,
                        type: "POST",
                        data: {
                            description: disclaimerContent,
                            _token: "{{ csrf_token() }}"
                        }
                    })
                );
            }

            // Execute all promises
            Promise.all(promises)
                .then(() => {
                    NProgress.done();

                    Swal.fire({
                        title: 'Success',
                        text: 'Investment updated successfully',
                        icon: 'success',
                        confirmButtonText: 'View List',
                        confirmButtonColor: '#172870'
                    }).then(() => {
                        window.location.href = "{{ route('investment.list') }}";
                    });
                })
                .catch(err => {
                    NProgress.done();
                    console.error(err);
                    let message = 'Failed to complete investment update';
                    if (err.responseJSON && err.responseJSON.message) {
                        message = err.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Error',
                        html: message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                });
        }
    </script>
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <style>
        .step-wizard {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-wizard::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }

        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #e0e0e0;
            color: #666;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }

        .step-item.active .step-circle {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .step-item.completed .step-circle {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #666;
        }

        .step-item.active .step-label {
            color: #0d6efd;
            font-weight: 600;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }

        #map {
            height: 400px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview-item {
            position: relative;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-preview-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .image-preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        .mountain-image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 8px;
        }
    </style>
@endpush
