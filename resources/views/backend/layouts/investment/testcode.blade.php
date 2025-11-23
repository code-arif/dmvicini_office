@extends('backend.app')
@section('title', 'Create Investment')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Create Investment</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('investment.list') }}">Investments</a></li>
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
                                                <label class="form-label">Mountain Image</label>
                                                <input type="file" name="mountain_image" class="form-control"
                                                    accept="image/*">
                                                <img id="mountainImagePreview" class="mountain-image-preview">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Asset Class</label>
                                                <select name="asset_class_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($asset_classes as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Investment Type</label>
                                                <select name="investment_type_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($investment_types as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Investment Strategy</label>
                                                <select name="investments_strategy_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($strategies as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label">Tax Strategy</label>
                                                <select name="investments_strategy_id" class="form-select">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($tax_strategies as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
                                            <label class="form-label">Investment Details</label>
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
                                        <h4 class="mb-4">Gallery Images</h4>
                                        <div class="mb-3">
                                            <label class="form-label">Select Multiple Images</label>
                                            <input type="file" id="galleryImages" class="form-control"
                                                accept="image/*" multiple>
                                        </div>
                                        <div id="imagePreviewGrid" class="image-preview-grid"></div>
                                    </div>


                                    <!-- Step 6: Disclaimer (Single) -->
                                    <div class="step-content" data-step="6">
                                        <h4 class="mb-4">Investment Disclaimer</h4>

                                        <div class="mb-3">
                                            <label class="form-label">Disclaimer Content</label>
                                            <textarea name="disclaimer_description" id="disclaimerDescription" class="form-control"></textarea>
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
            disclaimerStore: "{{ route('investment.disclaimer.store', ':id') }}"
        };
    </script>

    <script>
        let currentStep = 1;
        let totalSteps = 6;
        let investmentId = null;
        let map, marker, geocoder, searchBox;
        let documents = [];
        let images = [];
        let disclaimers = [];

        $(document).ready(function() {
            // Initialize Summernote for all rich text editors including disclaimer
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
                if (currentStep === 1 && !investmentId) {
                    saveBasicInfo();
                } else {
                    navigateStep(1);
                }
            });

            $('#prevBtn').on('click', () => navigateStep(-1));
            $('#submitBtn').on('click', submitInvestment);

            // Documents
            $('#addDocumentBtn').on('click', addDocument);

            // Gallery
            $('#galleryImages').on('change', previewGalleryImages);

            // Disclaimers
            $('#addDisclaimerBtn').on('click', addDisclaimer);
        });

        function initMap() {
            const dhaka = {
                lat: 23.8103,
                lng: 90.4125
            };

            map = new google.maps.Map(document.getElementById('map'), {
                center: dhaka,
                zoom: 13
            });

            marker = new google.maps.Marker({
                map: map,
                position: dhaka,
                draggable: true
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
            $('#address').val(place.formatted_address);

            if (place.address_components) {
                place.address_components.forEach(component => {
                    if (component.types.includes('country')) {
                        $('#country').val(component.long_name);
                    }
                    if (component.types.includes('administrative_area_level_1')) {
                        $('#state').val(component.long_name);
                    }
                    if (component.types.includes('locality')) {
                        $('#city').val(component.long_name);
                    }
                });
            }
        }

        function saveBasicInfo() {
            const formData = new FormData($('#investmentForm')[0]);

            NProgress.start();
            $.ajax({
                url: "{{ route('investment.basic.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        investmentId = res.investment_id;
                        toastr.success(res.message);
                        navigateStep(1);
                    }
                },
                error: function(xhr) {
                    NProgress.done();
                    let message = 'Failed to save basic info';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                }
            });
        }

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
                                <h6>${disc.title}</h6>
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

        // Update submitInvestment function
        function submitInvestment() {
            if (!investmentId) {
                toastr.error('Please complete step 1 first');
                return;
            }

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
                    url: window.routes.highlightStore.replace(':id', investmentId),
                    type: "POST",
                    data: highlightData
                })
            );


            // 2. Upload documents
            documents.forEach(doc => {
                const formDocument = new FormData();
                formDocument.append('name', doc.name);
                formDocument.append('file', doc.file);
                formDocument.append('_token', "{{ csrf_token() }}");

                promises.push(
                    $.ajax({
                        url: window.routes.documentStore.replace(':id', investmentId),
                        type: "POST",
                        data: formDocument,
                        processData: false,
                        contentType: false
                    })
                );
            });

            // 3. Upload images
            if (images.length > 0) {
                const imageFormData = new FormData();
                images.forEach(img => imageFormData.append('images[]', img));
                imageFormData.append('_token', "{{ csrf_token() }}");

                promises.push(
                    $.ajax({
                        url: window.routes.imagesStore.replace(':id', investmentId),
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
                        url: window.routes.disclaimerStore.replace(':id', investmentId),
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
                    toastr.success('Investment created successfully!');
                    setTimeout(() => {
                        window.location.href = "{{ route('investment.list') }}";
                    }, 1500);
                })
                .catch(err => {
                    NProgress.done();
                    console.error(err);
                    let message = 'Failed to complete investment creation';
                    if (err.responseJSON && err.responseJSON.message) {
                        message = err.responseJSON.message;
                    }
                    toastr.error(message);
                });
        }
    </script>
@endpush
