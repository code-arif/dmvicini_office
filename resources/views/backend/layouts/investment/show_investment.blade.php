@extends('backend.app')
@section('title', 'Investment Details')

@push('styles')
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
        }

        .hero-section {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)),
                url('{{ $investment->mountain_image ? asset($investment->mountain_image) : asset('default/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .nav-tabs .nav-link {
            color: var(--dark-text);
            font-weight: 500;
            border: none;
            padding: 15px 25px;
        }

        .nav-tabs .nav-link:hover {
            color: var(--secondary-color) !important;
            border-bottom: 3px solid var(--secondary-color);
            background: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--secondary-color) !important;
            border-bottom: 3px solid var(--secondary-color);
            background: transparent;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-title {
            font-weight: 600;
            color: var(--primary-color);
        }

        .highlight-card {
            border-left: 4px solid var(--secondary-color);
        }

        .info-table {
            width: 100%;
        }

        .info-table tr {
            border-bottom: 1px solid #eee;
        }

        .info-table td {
            padding: 15px 10px;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: var(--dark-text);
        }

        .info-table td:last-child {
            color: var(--light-text);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .stat-label {
            font-size: 1rem;
            color: var(--light-text);
            margin-top: 0.5rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
        }

        .status-draft {
            background: #6c757d;
            color: white;
        }

        .status-active {
            background: #28a745;
            color: white;
        }

        .status-closed {
            background: #dc3545;
            color: white;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Header -->
                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">Investment Details</h3>
                                <div class="d-flex gap-2">
                                    <span class="status-badge status-{{ $investment->status }}">
                                        {{ ucfirst($investment->status) }}
                                    </span>
                                    <a href="{{ route('investment.edit', $investment->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('investment.list') }}" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hero Section -->
                <section class="hero-section">
                    <div class="container">
                        <h1 class="display-4 fw-bold mb-4">{{ $investment->title }}</h1>
                        @if ($investment->investment_details)
                            <p class="lead mb-4">
                                {!! Str::limit(strip_tags($investment->investment_details), 150, '...') !!}
                            </p>
                        @endif
                    </div>
                </section>

                <!-- Key Stats -->
                {{-- <section class="container my-5">
                    <div class="row">
                        <div class="col-md-3 col-6 text-center mb-3">
                            <div class="card p-3">
                                <div class="stat-value">{{ $investment->term ?? 'N/A' }}</div>
                                <div class="stat-label">Term</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center mb-3">
                            <div class="card p-3">
                                <div class="stat-value">{{ $investment->min_investment ?? 'N/A' }}</div>
                                <div class="stat-label">Min Investment</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center mb-3">
                            <div class="card p-3">
                                <div class="stat-value">{{ $investment->highlight->targeted_irr ?? 'N/A' }}</div>
                                <div class="stat-label">Targeted IRR</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 text-center mb-3">
                            <div class="card p-3">
                                <div class="stat-value">{{ $investment->unit_count ?? 'N/A' }}</div>
                                <div class="stat-label">Unit Count</div>
                            </div>
                        </div>
                    </div>
                </section> --}}

                <!-- Navigation Tabs -->
                <section class="my-5">
                    <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                data-bs-target="#overview" type="button" role="tab">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="highlights-tab" data-bs-toggle="tab" data-bs-target="#highlights"
                                type="button" role="tab">Highlights</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location"
                                type="button" role="tab">Location</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories"
                                type="button" role="tab">Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents"
                                type="button" role="tab">Documents</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery"
                                type="button" role="tab">Gallery</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="disclaimers-tab" data-bs-toggle="tab" data-bs-target="#disclaimers"
                                type="button" role="tab">Disclaimers</button>
                        </li>
                    </ul>
                </section>

                <!-- Tab Content -->
                <div class="tab-content my-5" id="myTabContent">

                    <!-- Overview Tab -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <h2 class="section-title text-center">Investment Overview</h2>
                        <hr>

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Basic Information</h4>
                                    <table class="info-table">
                                        <tr>
                                            <td>Sponsor</td>
                                            <td class="text-end">{{ $investment->sponsor ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fund Name</td>
                                            <td class="text-end">{{ $investment->fund_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Property Type</td>
                                            <td class="text-end">{{ $investment->property_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Target Equity</td>
                                            <td class="text-end">
                                                {{ $investment->target_equity ? '$' . number_format($investment->target_equity, 2) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Target Raise</td>
                                            <td class="text-end">
                                                {{ $investment->target_raise ? '$' . number_format($investment->target_raise, 2) : 'N/A' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Important Dates</h4>
                                    <table class="info-table">
                                        <tr>
                                            <td>Launch Date</td>
                                            <td class="text-end">
                                                {{ $investment->launch_date ? date('M d, Y', strtotime($investment->launch_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Close Date</td>
                                            <td class="text-end">
                                                {{ $investment->close_date ? date('M d, Y', strtotime($investment->close_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created</td>
                                            <td class="text-end">{{ $investment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Last Updated</td>
                                            <td class="text-end">{{ $investment->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Investment Details -->
                            @if ($investment->investment_details)
                                <div class="col-12 mb-4">
                                    <div class="card highlight-card p-4">
                                        <h4 class="card-title">Investment Details</h4>
                                        <div class="mt-3">
                                            {!! $investment->investment_details !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Market Overview -->
                            @if ($investment->market_overview)
                                <div class="col-12">
                                    <div class="card highlight-card p-4">
                                        <h4 class="card-title">Market Overview</h4>
                                        <p class="mt-3">{{ $investment->market_overview }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Highlights Tab -->
                    <div class="tab-pane fade" id="highlights" role="tabpanel">
                        <h2 class="section-title text-center">Investment Highlights</h2>
                        <hr>

                        @if ($investment->highlight)
                            <div class="row">
                                <!-- Overview -->
                                @if ($investment->highlight->overview)
                                    <div class="col-12 mb-4">
                                        <div class="card highlight-card p-4">
                                            <h4 class="card-title">Overview</h4>
                                            <div class="mt-3">
                                                {!! $investment->highlight->overview !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Fees -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-4 h-100">
                                        <h4 class="card-title">Fees Structure</h4>
                                        <table class="info-table">
                                            <tr>
                                                <td>Asset Management Fee</td>
                                                <td class="text-end">
                                                    {{ $investment->highlight->asset_management_fee ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Organizational & Offering Fee</td>
                                                <td class="text-end">
                                                    {{ $investment->highlight->organizational_and_offering_fee ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Acquisition Fee</td>
                                                <td class="text-end">
                                                    {{ $investment->highlight->acquisition_fee ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Disposition Fee</td>
                                                <td class="text-end">
                                                    {{ $investment->highlight->disposition_fee ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td>Fund Administration Fee</td>
                                                <td class="text-end">
                                                    {{ $investment->highlight->fund_administration_fee ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Returns & Tax -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-4 h-100">
                                        <h4 class="card-title">Returns & Tax</h4>
                                        <table class="info-table">
                                            <tr>
                                                <td>Targeted IRR</td>
                                                <td class="text-end">{{ $investment->highlight->targeted_irr ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tax Document</td>
                                                <td class="text-end">{{ $investment->highlight->tax_doc ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Waterfall & Interest -->
                                @if ($investment->highlight->investor_waterfall || $investment->highlight->promoted_interest)
                                    <div class="col-md-6 mb-4">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">Investor Waterfall</h4>
                                            <div class="mt-3">
                                                {!! $investment->highlight->investor_waterfall ?? 'N/A' !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">Promoted Interest</h4>
                                            <div class="mt-3">
                                                {!! $investment->highlight->promoted_interest ?? 'N/A' !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning text-center p-4">
                                <strong>No highlights available for this investment.</strong>
                            </div>
                        @endif
                    </div>

                    <!-- Location Tab -->
                    <div class="tab-pane fade" id="location" role="tabpanel">
                        <h2 class="section-title text-center">Location Details</h2>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Address Information</h4>
                                    <table class="info-table">
                                        <tr>
                                            <td>Country</td>
                                            <td class="text-end">{{ $investment->country ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>State</td>
                                            <td class="text-end">{{ $investment->state ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>City</td>
                                            <td class="text-end">{{ $investment->city ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="text-end">{{ $investment->address ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Coordinates</td>
                                            <td class="text-end">
                                                @if ($investment->latitude && $investment->longitude)
                                                    {{ number_format($investment->latitude, 6) }},
                                                    {{ number_format($investment->longitude, 6) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if ($investment->latitude && $investment->longitude)
                                <div class="col-md-6 mb-4">
                                    <div class="card p-4 h-100">
                                        <h4 class="card-title">Map View</h4>
                                        <div id="mapDisplay" style="height: 300px; border-radius: 8px;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Categories Tab -->
                    <div class="tab-pane fade" id="categories" role="tabpanel">
                        <h2 class="section-title text-center">Investment Categories</h2>
                        <hr>

                        <div class="row">
                            <!-- Asset Class -->
                            <div class="col-md-4 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Asset Class</h4>
                                    @if ($investment->assetClass)
                                        <h5 class="mt-3">{{ $investment->assetClass->name }}</h5>
                                        @if ($investment->assetClass->description)
                                            <p class="text-muted mt-2">{{ $investment->assetClass->description }}</p>
                                        @endif
                                    @else
                                        <p class="text-muted mt-3">No asset class assigned</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Investment Type -->
                            <div class="col-md-4 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Investment Type</h4>
                                    @if ($investment->investmentType)
                                        <h5 class="mt-3">{{ $investment->investmentType->name }}</h5>
                                        @if ($investment->investmentType->description)
                                            <p class="text-muted mt-2">{{ $investment->investmentType->description }}</p>
                                        @endif
                                    @else
                                        <p class="text-muted mt-3">No investment type assigned</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Strategy -->
                            <div class="col-md-4 mb-4">
                                <div class="card p-4 h-100">
                                    <h4 class="card-title">Investment Strategy</h4>
                                    @if ($investment->strategy)
                                        <h5 class="mt-3">{{ $investment->strategy->name }}</h5>
                                        @if ($investment->strategy->description)
                                            <p class="text-muted mt-2">{!! $investment->strategy->description !!}</p>
                                        @endif
                                    @else
                                        <p class="text-muted mt-3">No strategy assigned</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <h2 class="section-title text-center">Investment Documents</h2>
                        <hr>

                        @forelse($investment->documents as $doc)
                            <div class="card mb-3">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            {{ $doc->name }}
                                        </h5>
                                    </div>
                                    <a href="{{ asset($doc->file_path) }}" target="_blank"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center p-4">
                                <strong>No documents uploaded yet.</strong>
                            </div>
                        @endforelse
                    </div>

                    <!-- Gallery Tab -->
                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                        <h2 class="section-title text-center">Investment Gallery</h2>
                        <hr>

                        @forelse($investment->images as $img)
                            <div class="image-gallery">
                                <div class="gallery-item">
                                    <img src="{{ asset($img->image_url) }}" alt="Investment Image">
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info text-center p-4">
                                <strong>No images uploaded yet.</strong>
                            </div>
                        @endforelse
                    </div>

                    <!-- Disclaimer Tab in Show Page -->
                    <div class="tab-pane fade" id="disclaimers" role="tabpanel">
                        <h2 class="section-title text-center">Investment Disclaimer</h2>
                        <hr>

                        @if ($investment->disclaimer && $investment->disclaimer->description)
                            <div class="card p-4">
                                <div class="alert alert-warning border-0 shadow-sm">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Important Notice
                                    </h5>
                                    <hr>
                                    <div class="disclaimer-content">
                                        {!! $investment->disclaimer->description !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info text-center p-4">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <p class="mb-0"><strong>No disclaimer available for this investment.</strong></p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <footer>
                    <div class="container text-center">
                        <p class="mb-0">&copy; {{ date('Y') }} Investment Management System. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($investment->latitude && $investment->longitude)
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfGOjmqKtEBRsfVN9szUo_tac20wcI9HM"></script>
        <script>
            function initMap() {
                const position = {
                    lat: {{ $investment->latitude }},
                    lng: {{ $investment->longitude }}
                };

                const map = new google.maps.Map(document.getElementById('mapDisplay'), {
                    center: position,
                    zoom: 15
                });

                new google.maps.Marker({
                    position: position,
                    map: map,
                    title: '{{ $investment->title }}'
                });
            }

            window.addEventListener('load', initMap);
        </script>
    @endif
@endpush


@push('styles')
    <style>
        .disclaimer-content {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #333;
        }

        .disclaimer-content p {
            margin-bottom: 1rem;
        }

        .disclaimer-content ul,
        .disclaimer-content ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .disclaimer-content strong {
            font-weight: 600;
            color: #000;
        }
    </style>
@endpush
