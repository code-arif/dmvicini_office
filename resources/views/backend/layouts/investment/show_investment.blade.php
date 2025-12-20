@extends('backend.app')
@section('title', 'Investment Details - ' . $investment->title)

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid mt-5">

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('investment.list') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i> Back to List
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('investment.edit', $investment->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit me-2"></i> Edit Deal
                        </a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $investment->id }})">
                            <i class="fa fa-trash me-2"></i> Delete
                        </button>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="investment-hero">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h1 class="investment-title">{{ $investment->title }}</h1>
                                <p class="investment-subtitle">
                                    @if ($investment->assetClass)
                                        <i class="fa fa-layer-group me-2"></i> {{ $investment->assetClass->name }}
                                    @endif
                                    @if ($investment->investmentType)
                                        <i class="fa fa-chart-line ms-3 me-2"></i> {{ $investment->investmentType->name }}
                                    @endif
                                </p>
                            </div>
                            <span class="status-badge status-{{ $investment->status }}">
                                {{ ucfirst($investment->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Key Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa fa-calendar-alt"></i></div>
                        <div class="stat-value">{{ $investment->term ?? 'N/A' }}</div>
                        <div class="stat-label">Investment Term</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa fa-dollar-sign"></i></div>
                        <div class="stat-value">{{ $investment->min_investment ?? 'N/A' }}</div>
                        <div class="stat-label">Minimum Investment</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa fa-chart-line"></i></div>
                        <div class="stat-value">{{ $investment->highlight->targeted_irr ?? 'N/A' }}</div>
                        <div class="stat-label">Targeted IRR</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fa fa-building"></i></div>
                        <div class="stat-value">{{ $investment->unit_count ?? 'N/A' }}</div>
                        <div class="stat-label">Unit Count</div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#overview">
                            <i class="fa fa-info-circle me-2"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#financial">
                            <i class="fa fa-coins me-2"></i> Financial Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#location">
                            <i class="fa fa-map-marker-alt me-2"></i> Location
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#documents">
                            <i class="fa fa-file-pdf me-2"></i> Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#gallery">
                            <i class="fa fa-images me-2"></i> Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#disclaimer">
                            <i class="fa fa-exclamation-triangle me-2"></i> Disclaimer
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Overview Tab -->
                    <div id="overview" class="tab-pane fade show active">
                        <div class="section-card">
                            <h3 class="section-title">Basic Information</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Sponsor</div>
                                    <div class="info-value">{{ $investment->sponsor ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fund Name</div>
                                    <div class="info-value">{{ $investment->fund_name ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Property Type</div>
                                    <div class="info-value">{{ $investment->property_type ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Launch Date</div>
                                    <div class="info-value">
                                        {{ $investment->launch_date ? date('M d, Y', strtotime($investment->launch_date)) : 'N/A' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Close Date</div>
                                    <div class="info-value">
                                        {{ $investment->close_date ? date('M d, Y', strtotime($investment->close_date)) : 'N/A' }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Investment Strategy</div>
                                    <div class="info-value">{{ $investment->strategy->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        @if ($investment->investment_details)
                            <div class="section-card">
                                <h3 class="section-title">Investment Details</h3>
                                <div class="content-body">
                                    {!! $investment->investment_details !!}
                                </div>
                            </div>
                        @endif

                        @if ($investment->highlight && $investment->highlight->overview)
                            <div class="section-card">
                                <h3 class="section-title">Investment Overview</h3>
                                <div class="content-body">
                                    {!! $investment->highlight->overview !!}
                                </div>
                            </div>
                        @endif

                        @if ($investment->market_overview)
                            <div class="section-card">
                                <h3 class="section-title">Market Overview</h3>
                                <p>{{ $investment->market_overview }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Financial Details Tab -->
                    <div id="financial" class="tab-pane fade">
                        @if ($investment->highlight)
                            <div class="section-card">
                                <h3 class="section-title">Investment Returns</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Target Equity</div>
                                        <div class="info-value">
                                            {{ $investment->target_equity ? '$' . number_format($investment->target_equity, 2) : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Target Raise</div>
                                        <div class="info-value">
                                            {{ $investment->target_raise ? '$' . number_format($investment->target_raise, 2) : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Targeted IRR</div>
                                        <div class="info-value">{{ $investment->highlight->targeted_irr ?? 'N/A' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Tax Document</div>
                                        <div class="info-value">{{ $investment->highlight->tax_doc ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-card">
                                <h3 class="section-title">Fee Structure</h3>
                                <table class="fees-table">
                                    <tr>
                                        <td>Asset Management Fee</td>
                                        <td>{{ $investment->highlight->asset_management_fee ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Organizational & Offering Fee</td>
                                        <td>{{ $investment->highlight->organizational_and_offering_fee ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Acquisition Fee</td>
                                        <td>{{ $investment->highlight->acquisition_fee ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Disposition Fee</td>
                                        <td>{{ $investment->highlight->disposition_fee ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Fund Administration Fee</td>
                                        <td>{{ $investment->highlight->fund_administration_fee ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            @if ($investment->highlight->investor_waterfall || $investment->highlight->promoted_interest)
                                <div class="row">
                                    @if ($investment->highlight->investor_waterfall)
                                        <div class="col-md-6">
                                            <div class="section-card">
                                                <h3 class="section-title">Investor Waterfall</h3>
                                                {!! $investment->highlight->investor_waterfall !!}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($investment->highlight->promoted_interest)
                                        <div class="col-md-6">
                                            <div class="section-card">
                                                <h3 class="section-title">Promoted Interest</h3>
                                                {!! $investment->highlight->promoted_interest !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <i class="fa fa-chart-pie"></i>
                                <p>No financial details available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Location Tab -->
                    <div id="location" class="tab-pane fade">
                        <div class="section-card">
                            <h3 class="section-title">Location Information</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Country</div>
                                    <div class="info-value">{{ $investment->country ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">State</div>
                                    <div class="info-value">{{ $investment->state ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">City</div>
                                    <div class="info-value">{{ $investment->city ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Full Address</div>
                                    <div class="info-value">{{ $investment->address ?? 'N/A' }}</div>
                                </div>
                            </div>

                            @if ($investment->latitude && $investment->longitude)
                                <div class="mt-4">
                                    <div id="mapDisplay"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Documents Tab -->
                    <div id="documents" class="tab-pane fade">
                        <div class="section-card">
                            <h3 class="section-title">Investment Documents</h3>
                            @forelse($investment->documents as $doc)
                                <div class="document-item">
                                    <div class="document-info">
                                        <div class="document-icon">
                                            <i class="fa fa-file-pdf"></i>
                                        </div>
                                        <div class="document-name">{{ $doc->name }}</div>
                                    </div>
                                    <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn-download">
                                        <i class="fa fa-download me-2"></i> Download
                                    </a>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa fa-file-pdf"></i>
                                    <p>No documents uploaded</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Gallery Tab -->
                    <div id="gallery" class="tab-pane fade">
                        <div class="section-card">
                            <h3 class="section-title">Investment Gallery</h3>

                            @if ($investment->images->isNotEmpty())
                                <div class="gallery-grid">
                                    @foreach ($investment->images as $img)
                                        <div class="gallery-item">
                                            <img src="{{ asset($img->image_url) }}" alt="Investment Image"
                                                onclick="viewImage('{{ asset($img->image_url) }}')">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fa fa-images"></i>
                                    <p>No images uploaded</p>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- Disclaimer Tab -->
                    <div id="disclaimer" class="tab-pane fade">
                        <div class="section-card">
                            <h3 class="section-title">Investment Disclaimer</h3>
                            @if ($investment->disclaimers && $investment->disclaimers->description)
                                <div class="disclaimer-box">
                                    <div class="disclaimer-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    {!! $investment->disclaimers->description !!}
                                </div>
                            @else
                                <div class="disclaimer-box">
                                    <div class="disclaimer-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    This offering summary has been prepared solely by the sponsor and is provided for
                                    informational purposes only. It is not a complete description of the securities being
                                    offered and does not constitute part of the sponsor’s private placement memorandum or
                                    other definitive offering documents (collectively, the “Offering Materials”), nor does
                                    it constitute an offer to sell or a solicitation of an offer to buy any securities. The
                                    securities described herein are offered exclusively pursuant to the Offering Materials,
                                    which must be reviewed carefully and in their entirety prior to making any investment
                                    decision.
                                    No person has been authorized to provide information or make representations regarding
                                    this offering other than those contained in the Offering Materials. Any such
                                    unauthorized information or representations may not be relied upon.
                                    Pinnacle Capital Group, LLC (“Pinnacle”) may act solely as a placement agent for certain
                                    offerings or, in some cases, may provide limited, non-solicited marketing or
                                    administrative services to the sponsor. Pinnacle is not the issuer, sponsor, or manager
                                    of any investment. Pinnacle does not provide investment, tax, or legal advice, does not
                                    recommend or endorse any offering on this platform, and makes no representation
                                    regarding the merits, suitability, risks, or expected performance of any offering.
                                    Investing in private placements involves significant risks, including, but not limited
                                    to, total loss of principal, illiquidity, long holding periods, lack of a secondary
                                    market, and limited transparency. These investments are suitable only for accredited
                                    investors who fully understand and are willing to accept these risks. All investors must
                                    be verified as accredited investors in accordance with applicable securities laws and
                                    regulations prior to investing.
                                    Any references to “target returns,” “annualized yields,” projections, or other
                                    forward-looking statements are hypothetical, are based solely on sponsor assumptions,
                                    should not be relied upon, are not guarantees of future performance, and actual results
                                    may differ materially. Past performance is not indicative of future results.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the investment and all related data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteInvestment(id);
                }
            });
        }

        function deleteInvestment(id) {
            NProgress.start();
            $.ajax({
                url: `/deal/delete/${id}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('investment.list') }}";
                        });
                    }
                },
                error: function() {
                    NProgress.done();
                    toastr.error('Failed to delete investment');
                }
            });
        }

        function viewImage(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Investment Image',
                showCloseButton: true,
                showConfirmButton: false,
                width: '80%'
            });
        }
    </script>
@endpush


@push('styles')
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --light: #f1f5f9;
            --dark: #1e293b;
        }

        .investment-hero {
            position: relative;
            height: 350px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(59, 130, 246, 0.85)),
                url('{{ $investment->mountain_image ? asset($investment->mountain_image) : asset('default/hero.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            align-items: center;
            border-radius: 12px;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(51, 65, 85, 0.7));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 40px;
            width: 100%;
        }

        .investment-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .investment-subtitle {
            font-size: 1.1rem;
            opacity: 0.95;
            margin-bottom: 1.5rem;
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-draft {
            background: var(--secondary);
            color: white;
        }

        .status-active {
            background: var(--success);
            color: white;
        }

        .status-closed {
            background: var(--danger);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--secondary);
            font-weight: 500;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            padding: 15px;
            background: var(--light);
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }

        .info-label {
            font-size: 0.85rem;
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--dark);
            font-weight: 600;
        }

        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            color: var(--secondary);
            font-weight: 600;
            padding: 15px 25px;
            border: none;
            position: relative;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary) !important;
            background: transparent;
        }

        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            aspect-ratio: 4/3;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: var(--light);
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .document-item:hover {
            background: #e2e8f0;
            transform: translateX(5px);
        }

        .document-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .document-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .document-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 1.05rem;
        }

        .btn-download {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-download:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }

        #mapDisplay {
            height: 400px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .fees-table {
            width: 100%;
            margin-top: 20px;
        }

        .fees-table tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .fees-table tr:last-child {
            border-bottom: none;
        }

        .fees-table td {
            padding: 15px;
        }

        .fees-table td:first-child {
            font-weight: 600;
            color: var(--dark);
            width: 60%;
        }

        .fees-table td:last-child {
            text-align: right;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .disclaimer-box {
            background: #fef3c7;
            border-left: 4px solid var(--warning);
            border-radius: 8px;
            padding: 25px;
        }

        .disclaimer-icon {
            color: var(--warning);
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
@endpush
