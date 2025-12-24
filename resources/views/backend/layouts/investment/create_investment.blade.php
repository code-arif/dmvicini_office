@extends('backend.app')
@section('title', 'Create Investment')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Create Deal</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('investment.list') }}">Deals</a></li>
                            <li class="breadcrumb-item active">Create</li>
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

                                    <!-- Step 1: Basic Info -->
                                    <div class="step-content active" data-step="1">
                                        <h4 class="mb-4">Basic Information</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Main Image</label>
                                                <input type="file" name="mountain_image" class="form-control"
                                                    accept="image/*">
                                                <img id="mountainImagePreview" class="mountain-image-preview">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between gap-1">
                                            <div class="col-md-4 mb-3 bg-light p-3 rounded-1">
                                                <label class="form-label">Asset Class</label>
                                                <select name="asset_class_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($asset_classes as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 bg-light p-3 rounded-1">
                                                <label class="form-label">Investment Type</label>
                                                <select name="investment_type_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($investment_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3 bg-light p-3 rounded-1">
                                                <label class="form-label">Investment Strategy</label>
                                                <select name="investments_strategy_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($strategies as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-md-3 mb-3">
                                                <label class="form-label">Tax Strategy</label>
                                                <select name="tax_strategie_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($tax_strategies as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Term</label>
                                                <input type="text" name="term" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Min Investment</label>
                                                <input type="text" name="min_investment" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="draft">Draft</option>
                                                    <option value="active">Active</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Summary</label>
                                            <textarea name="investment_details" id="investmentDetails" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <!-- Step 2: Location -->
                                    <div class="step-content" data-step="2">
                                        <h4 class="mb-4">Location Details</h4>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" name="country" id="country"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">State</label>
                                                <input type="text" name="state" id="state"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">City</label>
                                                <input type="text" name="city" id="city"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" id="address"
                                                    class="form-control">
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

                                        <input type="hidden" name="latitude" id="latitude">
                                        <input type="hidden" name="longitude" id="longitude">
                                    </div>

                                    <!-- Step 3: Highlights -->
                                    <div class="step-content" data-step="3">
                                        <h4 class="mb-4">Investment Highlights</h4>
                                        <div class="mb-3">
                                            <label class="form-label">Summary</label>
                                            <textarea name="overview" id="overview" class="form-control"></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Targeted IRR</label>
                                                <input type="text" name="targeted_irr" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tax Document</label>
                                                <input type="text" name="tax_doc" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Investor Waterfall</label>
                                                <textarea name="investor_waterfall" id="investor_waterfall" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Promoted Interest</label>
                                                <textarea name="promoted_interest" id="promoted_interest" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Asset Management Fee</label>
                                                <input type="text" name="asset_management_fee" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Organizational & Offering Fee</label>
                                                <input type="text" name="organizational_and_offering_fee"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Acquisition Fee</label>
                                                <input type="text" name="acquisition_fee" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Disposition Fee</label>
                                                <input type="text" name="disposition_fee" class="form-control">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Fund Administration Fee</label>
                                                <input type="text" name="fund_administration_fee"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4: Documents -->
                                    <div class="step-content" data-step="4">
                                        <h4 class="mb-4">Upload Documents</h4>
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
                                                    <i class="fa fa-plus"></i> Add Document
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 5: Gallery -->
                                    <div class="step-content" data-step="5">
                                        <h4 class="mb-4">Gallery Images (Maximum 2)</h4>

                                        <div class="row g-4">
                                            <!-- First Image -->
                                            <div class="col-md-6">
                                                <div class="gallery-upload-box">
                                                    <label class="form-label">Image 1</label>
                                                    <div class="image-upload-wrapper" id="image1Wrapper">
                                                        <input type="file" name="gallery_images[]" class="image-input"
                                                            id="image1Input" accept="image/*">
                                                        <div class="upload-placeholder">
                                                            <i class="fa fa-image fa-3x text-muted"></i>
                                                            <p class="mt-2">Click to upload Image 1</p>
                                                        </div>
                                                        <img id="image1Preview" class="image-preview"
                                                            style="display:none;">
                                                        <button type="button" class="remove-image-btn" id="removeImage1"
                                                            style="display:none;">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Second Image -->
                                            <div class="col-md-6">
                                                <div class="gallery-upload-box">
                                                    <label class="form-label">Image 2</label>
                                                    <div class="image-upload-wrapper" id="image2Wrapper">
                                                        <input type="file" name="gallery_images[]" class="image-input"
                                                            id="image2Input" accept="image/*">
                                                        <div class="upload-placeholder">
                                                            <i class="fa fa-image fa-3x text-muted"></i>
                                                            <p class="mt-2">Click to upload Image 2</p>
                                                        </div>
                                                        <img id="image2Preview" class="image-preview"
                                                            style="display:none;">
                                                        <button type="button" class="remove-image-btn" id="removeImage2"
                                                            style="display:none;">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <small class="text-muted">You can upload up to 2 images. Both are optional.</small>
                                    </div>


                                    <!-- Step 6: Disclaimer (Single) -->
                                    <div class="step-content" data-step="6">
                                        <h4 class="mb-4">Investment Disclaimer</h4>

                                        <div class="mb-3">
                                            <label class="form-label">Disclaimer Content</label>
                                            <textarea name="disclaimer_description" id="disclaimerDescription" class="form-control">This offering summary has been prepared solely by the sponsor and is provided for informational purposes only. It is not a complete description of the securities being offered and does not constitute part of the sponsor’s private placement memorandum or other definitive offering documents (collectively, the “Offering Materials”), nor does it constitute an offer to sell or a solicitation of an offer to buy any securities. The securities described herein are offered exclusively pursuant to the Offering Materials, which must be reviewed carefully and in their entirety prior to making any investment decision.
No person has been authorized to provide information or make representations regarding this offering other than those contained in the Offering Materials. Any such unauthorized information or representations may not be relied upon.
Pinnacle Capital Group, LLC (“Pinnacle”) may act solely as a placement agent for certain offerings or, in some cases, may provide limited, non-solicited marketing or administrative services to the sponsor. Pinnacle is not the issuer, sponsor, or manager of any investment. Pinnacle does not provide investment, tax, or legal advice, does not recommend or endorse any offering on this platform, and makes no representation regarding the merits, suitability, risks, or expected performance of any offering.
Investing in private placements involves significant risks, including, but not limited to, total loss of principal, illiquidity, long holding periods, lack of a secondary market, and limited transparency. These investments are suitable only for accredited investors who fully understand and are willing to accept these risks. All investors must be verified as accredited investors in accordance with applicable securities laws and regulations prior to investing.
Any references to “target returns,” “annualized yields,” projections, or other forward-looking statements are hypothetical, are based solely on sponsor assumptions, should not be relied upon, are not guarantees of future performance, and actual results may differ materially. Past performance is not indicative of future results.
</textarea>
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
                                            <i class="fa fa-check"></i> Create Investment
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
            basicStore: "{{ route('investment.basic.store') }}",
            highlightStore: "{{ route('investment.highlight.store', ':id') }}",
            documentStore: "{{ route('investment.document.store', ':id') }}",
            imagesStore: "{{ route('investment.images.store', ':id') }}",
            disclaimerStore: "{{ route('investment.disclaimer.store', ':id') }}",
            updateBasic: "{{ route('investment.update', ':id') }}",
        };
    </script>

    <script>
        // Global variables
        let currentStep = 1;
        let totalSteps = 6;
        let investmentId = null;
        let map, marker, geocoder, searchBox;
        let documents = [];
        let galleryImages = [null, null];

        // LocalStorage key
        const FORM_STORAGE_KEY = 'investment_form_progress';

        $(document).ready(function() {
            // Initialize Summernote
            $('#investmentDetails, #overview, #investor_waterfall, #promoted_interest, #disclaimerDescription')
                .summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onInit: function() {
                            $('.note-editable').css({
                                'background-color': '#000000',
                                'color': '#ffffff',
                                'min-height': '400px'
                            });
                            $('.note-toolbar').css({
                                'background-color': '#1a1a1a',
                                'border-top': '1px solid #333'
                            });
                        },
                        onChange: function(contents, $editable) {
                            saveFormProgress();
                        }
                    },
                    color: {
                        foreColor: '#ffffff',
                        backColor: '#000000'
                    }
                });

            initMap();

            // Load saved progress
            loadFormProgress();

            // Auto-save on input change
            $('#investmentForm input, #investmentForm select, #investmentForm textarea').on('change input',
                function() {
                    saveFormProgress();
                });

            // Mountain image preview
            $('input[name="mountain_image"]').on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = e => {
                        $('#mountainImagePreview').attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(this.files[0]);
                }
                saveFormProgress();
            });

            // Navigation buttons
            $('#nextBtn').on('click', () => {
                if (currentStep === 1 && !investmentId) {
                    saveBasicInfo();
                } else if (currentStep === 2) {
                    updateLocation();
                } else {
                    navigateStep(1);
                }
            });

            $('#prevBtn').on('click', () => navigateStep(-1));
            $('#submitBtn').on('click', submitInvestment);
            $('#addDocumentBtn').on('click', addDocument);

            // Gallery - 2 images handling
            $('#image1Input').on('change', function() {
                handleImageUpload(this, '#image1Preview', '#image1Wrapper', '#removeImage1', 0);
            });

            $('#image2Input').on('change', function() {
                handleImageUpload(this, '#image2Preview', '#image2Wrapper', '#removeImage2', 1);
            });

            $('#removeImage1').on('click', function() {
                removeImage(0);
            });

            $('#removeImage2').on('click', function() {
                removeImage(1);
            });
        });

        // ============= MAP FUNCTIONS =============

        function initMap() {
            const dhaka = {
                lat: 23.8103,
                lng: 90.4125
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: dhaka,
                zoom: 13,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            marker = new google.maps.Marker({
                map: map,
                position: dhaka,
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
        }

        function reverseGeocode(location) {
            geocoder.geocode({
                location: location
            }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    $('#latitude').val(location.lat());
                    $('#longitude').val(location.lng());

                    updateLocationFields({
                        geometry: {
                            location: location
                        },
                        formatted_address: results[0].formatted_address,
                        address_components: results[0].address_components
                    });
                } else {
                    toastr.warning('Could not get location details');
                }
            });
        }

        function updateLocationFields(place) {
            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            $('#latitude').val(lat);
            $('#longitude').val(lng);

            if (place.formatted_address) {
                $('#address').val(place.formatted_address);
            }

            if (place.address_components) {
                $('#country, #state, #city').val('');

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
                    }
                });

                toastr.success('Location updated successfully');
            }
        }

        // ============= SAVE & UPDATE FUNCTIONS =============

        function saveBasicInfo() {
            const formData = new FormData($('#investmentForm')[0]);

            // Add location data
            formData.set('country', $('#country').val() || '');
            formData.set('state', $('#state').val() || '');
            formData.set('city', $('#city').val() || '');
            formData.set('address', $('#address').val() || '');
            formData.set('latitude', $('#latitude').val() || '');
            formData.set('longitude', $('#longitude').val() || '');

            NProgress.start();
            $.ajax({
                url: window.routes.basicStore,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    NProgress.done();
                    if (res.success && res.investment_id) {
                        investmentId = res.investment_id;
                        console.log('Investment ID saved:', investmentId);
                        toastr.success(res.message || 'Basic info saved successfully');
                        $(`.step-item[data-step="1"]`).addClass('completed');
                        saveFormProgress(); // Save investment ID
                        navigateStep(1);
                    }
                },
                error: function(xhr) {
                    NProgress.done();
                    handleAjaxError(xhr, 'Failed to save basic info');
                }
            });
        }

        function updateLocation() {
            if (!investmentId) {
                toastr.error('Please complete Step 1 first');
                return;
            }

            const locationData = new FormData();
            locationData.append('_token', $('input[name="_token"]').val());
            locationData.append('_method', 'POST');
            locationData.append('country', $('#country').val() || '');
            locationData.append('state', $('#state').val() || '');
            locationData.append('city', $('#city').val() || '');
            locationData.append('address', $('#address').val() || '');
            locationData.append('latitude', $('#latitude').val() || '');
            locationData.append('longitude', $('#longitude').val() || '');

            NProgress.start();
            $.ajax({
                url: window.routes.updateBasic.replace(':id', investmentId),
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
                    handleAjaxError(xhr, 'Failed to update location');
                }
            });
        }

        // ============= NAVIGATION FUNCTIONS =============

        function navigateStep(direction) {
            saveFormProgress(); // Save before navigation
            goToStep(currentStep + direction);
        }

        function goToStep(step) {
            if (step < 1 || step > totalSteps) return;

            if (step > 1 && !investmentId) {
                toastr.error('Please complete Step 1 first');
                return;
            }

            $(`.step-content[data-step="${currentStep}"]`).removeClass('active');

            if (step > currentStep) {
                $(`.step-item[data-step="${currentStep}"]`).removeClass('active').addClass('completed');
            }

            $(`.step-content[data-step="${step}"]`).addClass('active');
            $(`.step-item[data-step="${step}"]`).addClass('active').removeClass('completed');

            currentStep = step;

            // Save current step
            saveFormProgress();

            $('#prevBtn').toggle(currentStep > 1);
            $('#nextBtn').toggle(currentStep < totalSteps);
            $('#submitBtn').toggle(currentStep === totalSteps);
        }

        // ============= DOCUMENT FUNCTIONS =============

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
            toastr.success('Document added');

            saveFormProgress(); // Save after adding document
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
            toastr.info('Document removed');

            saveFormProgress(); // Save after removing document
        }

        // ============= GALLERY FUNCTIONS =============

        function handleImageUpload(input, previewId, wrapperId, removeBtnId, index) {
            const file = input.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                toastr.error('Please select an image file');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result).show();
                $(wrapperId).addClass('has-image');
                $(removeBtnId).show();
            };
            reader.readAsDataURL(file);

            galleryImages[index] = file;
            console.log('Image added at index', index, galleryImages);
        }

        function removeImage(index) {
            const imageNum = index + 1;
            const inputId = `#image${imageNum}Input`;
            const previewId = `#image${imageNum}Preview`;
            const wrapperId = `#image${imageNum}Wrapper`;
            const removeBtnId = `#removeImage${imageNum}`;

            $(inputId).val('');
            $(previewId).hide();
            $(wrapperId).removeClass('has-image');
            $(removeBtnId).hide();

            galleryImages[index] = null;
            console.log('Image removed at index', index, galleryImages);
            toastr.info('Image removed');
        }

        // ============= LOCALSTORAGE FUNCTIONS =============

        function saveFormProgress() {
            try {
                const progressData = {
                    currentStep: currentStep,
                    investmentId: investmentId,
                    formData: {
                        // Step 1 - Basic Info
                        title: $('[name="title"]').val(),
                        asset_class_id: $('[name="asset_class_id"]').val(),
                        investment_type_id: $('[name="investment_type_id"]').val(),
                        investments_strategy_id: $('[name="investments_strategy_id"]').val(),
                        term: $('[name="term"]').val(),
                        min_investment: $('[name="min_investment"]').val(),
                        status: $('[name="status"]').val(),
                        investment_details: $('#investmentDetails').summernote('code'),

                        // Step 2 - Location
                        country: $('#country').val(),
                        state: $('#state').val(),
                        city: $('#city').val(),
                        address: $('#address').val(),
                        latitude: $('#latitude').val(),
                        longitude: $('#longitude').val(),

                        // Step 3 - Highlights
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

                        // Step 6 - Disclaimer
                        disclaimer_description: $('#disclaimerDescription').summernote('code')
                    },
                    documents: documents.map(doc => ({
                        name: doc.name,
                        fileName: doc.file.name,
                        fileSize: doc.file.size,
                        fileType: doc.file.type
                    })),
                    timestamp: new Date().toISOString()
                };

                localStorage.setItem(FORM_STORAGE_KEY, JSON.stringify(progressData));
                console.log('✓ Form progress saved');
            } catch (error) {
                console.error('Error saving form progress:', error);
            }
        }

        function loadFormProgress() {
            try {
                const savedData = localStorage.getItem(FORM_STORAGE_KEY);

                if (!savedData) {
                    console.log('No saved progress found');
                    return;
                }

                const progressData = JSON.parse(savedData);

                // Show restore prompt
                Swal.fire({
                    title: 'Previous Progress found',
                    text: 'Do you want to start over?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Continue',
                    cancelButtonText: 'No, start fresh.',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        restoreFormData(progressData);
                        toastr.success('Previous progress restored successfully');
                    } else {
                        localStorage.removeItem(FORM_STORAGE_KEY);
                        toastr.info('Starting fresh');
                    }
                });

            } catch (error) {
                console.error('Error loading form progress:', error);
                localStorage.removeItem(FORM_STORAGE_KEY);
            }
        }

        function restoreFormData(progressData) {
            try {
                // Restore basic variables
                investmentId = progressData.investmentId;
                currentStep = progressData.currentStep || 1;

                const data = progressData.formData;

                // Step 1 - Basic Info
                $('[name="title"]').val(data.title || '');
                $('[name="asset_class_id"]').val(data.asset_class_id || '');
                $('[name="investment_type_id"]').val(data.investment_type_id || '');
                $('[name="investments_strategy_id"]').val(data.investments_strategy_id || '');
                $('[name="term"]').val(data.term || '');
                $('[name="min_investment"]').val(data.min_investment || '');
                $('[name="status"]').val(data.status || 'draft');

                if (data.investment_details) {
                    $('#investmentDetails').summernote('code', data.investment_details);
                }

                // Step 2 - Location
                $('#country').val(data.country || '');
                $('#state').val(data.state || '');
                $('#city').val(data.city || '');
                $('#address').val(data.address || '');
                $('#latitude').val(data.latitude || '');
                $('#longitude').val(data.longitude || '');

                // Update map if coordinates exist
                if (data.latitude && data.longitude) {
                    const position = {
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude)
                    };
                    marker.setPosition(position);
                    map.setCenter(position);
                }

                // Step 3 - Highlights
                if (data.overview) {
                    $('#overview').summernote('code', data.overview);
                }
                $('[name="targeted_irr"]').val(data.targeted_irr || '');
                $('[name="tax_doc"]').val(data.tax_doc || '');

                if (data.investor_waterfall) {
                    $('#investor_waterfall').summernote('code', data.investor_waterfall);
                }
                if (data.promoted_interest) {
                    $('#promoted_interest').summernote('code', data.promoted_interest);
                }

                $('[name="asset_management_fee"]').val(data.asset_management_fee || '');
                $('[name="organizational_and_offering_fee"]').val(data.organizational_and_offering_fee || '');
                $('[name="acquisition_fee"]').val(data.acquisition_fee || '');
                $('[name="disposition_fee"]').val(data.disposition_fee || '');
                $('[name="fund_administration_fee"]').val(data.fund_administration_fee || '');

                // Step 6 - Disclaimer
                if (data.disclaimer_description) {
                    $('#disclaimerDescription').summernote('code', data.disclaimer_description);
                }

                // Restore documents info (files can't be restored, but show list)
                if (progressData.documents && progressData.documents.length > 0) {
                    toastr.info(
                        `${progressData.documents.length} documents were previously added. Please re-upload them in Step 4.`
                        );
                }

                // Mark completed steps
                for (let i = 1; i < currentStep; i++) {
                    $(`.step-item[data-step="${i}"]`).addClass('completed');
                }

                // Navigate to saved step
                goToStep(currentStep);

                // Scroll to the card after a short delay to ensure DOM is ready
                setTimeout(() => {
                    scrollToCard();
                }, 300);

                console.log('✓ Form data restored successfully');

            } catch (error) {
                console.error('Error restoring form data:', error);
                toastr.error('Error restoring previous progress');
            }
        }

        function scrollToCard() {
            const card = $('.card').first();
            if (card.length) {
                $('html, body').animate({
                    scrollTop: card.offset().top - 20
                }, 400);
            }
        }

        function clearFormProgress() {
            localStorage.removeItem(FORM_STORAGE_KEY);
        }

        // ============= SUBMIT FUNCTION =============

        function submitInvestment() {
            if (!investmentId) {
                toastr.error('Please complete step 1 first');
                return;
            }

            console.log('Starting submission for Investment ID:', investmentId);
            NProgress.start();

            const promises = [];

            // Step 3: Highlights
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
                _token: $('input[name="_token"]').val()
            };

            promises.push(
                $.ajax({
                    url: window.routes.highlightStore.replace(':id', investmentId),
                    type: "POST",
                    data: highlightData
                }).then(() => console.log('✓ Highlights saved'))
            );

            // Step 4: Documents
            if (documents.length > 0) {
                console.log('Uploading', documents.length, 'documents');
                documents.forEach((doc, index) => {
                    const formDocument = new FormData();
                    formDocument.append('name', doc.name);
                    formDocument.append('file', doc.file);
                    formDocument.append('_token', $('input[name="_token"]').val());

                    promises.push(
                        $.ajax({
                            url: window.routes.documentStore.replace(':id', investmentId),
                            type: "POST",
                            data: formDocument,
                            processData: false,
                            contentType: false
                        }).then(() => console.log(`✓ Document ${index + 1} saved`))
                    );
                });
            }

            // Step 5: Gallery
            const validImages = galleryImages.filter(img => img !== null);
            console.log('Valid gallery images:', validImages.length);

            if (validImages.length > 0) {
                const imageFormData = new FormData();

                validImages.forEach((img) => {
                    imageFormData.append('images[]', img);
                });

                imageFormData.append('_token', $('input[name="_token"]').val());

                promises.push(
                    $.ajax({
                        url: window.routes.imagesStore.replace(':id', investmentId),
                        type: "POST",
                        data: imageFormData,
                        processData: false,
                        contentType: false
                    }).then(() => console.log('✓ Gallery images saved'))
                );
            }

            // Step 6: Disclaimer
            const disclaimerContent = $('#disclaimerDescription').summernote('code');
            if (disclaimerContent && disclaimerContent.trim() !== '') {
                promises.push(
                    $.ajax({
                        url: window.routes.disclaimerStore.replace(':id', investmentId),
                        type: "POST",
                        data: {
                            description: disclaimerContent,
                            _token: $('input[name="_token"]').val()
                        }
                    }).then(() => console.log('✓ Disclaimer saved'))
                );
            }

            // Execute all promises
            Promise.all(promises)
                .then(() => {
                    NProgress.done();
                    console.log('✓ All data saved successfully');

                    // Clear saved progress
                    clearFormProgress();

                    Swal.fire({
                        title: 'Success!',
                        text: 'Investment created successfully',
                        icon: 'success',
                        confirmButtonText: 'View List',
                        confirmButtonColor: '#172870'
                    }).then(() => {
                        window.location.href = "{{ route('investment.list') }}";
                    });
                })
                .catch(err => {
                    NProgress.done();
                    console.error('Submission error:', err);

                    let message = 'Failed to complete investment creation';
                    if (err.responseJSON) {
                        if (err.responseJSON.message) {
                            message = err.responseJSON.message;
                        } else if (err.responseJSON.errors) {
                            message = Object.values(err.responseJSON.errors).flat().join('<br>');
                        }
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

        // ============= ERROR HANDLER =============

        function handleAjaxError(xhr, defaultMsg) {
            let message = defaultMsg;

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
            }

            toastr.error(message);
            console.error(defaultMsg, xhr);
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
            display: none;
        }
    </style>

    {{-- gallary image uploading style --}}
    <style>
        .gallery-upload-box {
            margin-bottom: 1rem;
        }

        .image-upload-wrapper {
            position: relative;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s;
        }

        .image-upload-wrapper:hover {
            border-color: #0d6efd;
        }

        .image-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .upload-placeholder {
            text-align: center;
            color: #6c757d;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .remove-image-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0.9;
            transition: opacity 0.3s;
        }

        .remove-image-btn:hover {
            opacity: 1;
        }

        /* Summernote Dark Theme Fix */
        .note-editor .note-editable {
            background-color: #000000 !important;
            color: #ffffff !important;
        }

        .note-editor .note-toolbar {
            background-color: #1a1a1a !important;
            border-bottom: 1px solid #333 !important;
        }

        .note-editor .note-toolbar .note-btn {
            background-color: #2d2d2d !important;
            color: #ffffff !important;
            border: 1px solid #444 !important;
        }

        .note-editor .note-toolbar .note-btn:hover {
            background-color: #3d3d3d !important;
        }

        .note-editor .note-statusbar {
            background-color: #1a1a1a !important;
            color: #aaa !important;
        }

        .note-editor .note-editable:empty:before {
            content: "Type here...";
            color: #888 !important;
            pointer-events: none;
        }
    </style>
@endpush
