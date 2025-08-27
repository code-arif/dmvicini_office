@extends('backend.app')
@section('title', 'Investments')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />

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
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('{{ $heroImageUrl }}');
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

        .fee-table {
            width: 100%;
        }

        .fee-table tr {
            border-bottom: 1px solid #eee;
        }

        .fee-table td {
            padding: 15px 10px;
        }

        .fee-table tr:last-child {
            border-bottom: none;
            font-weight: 600;
            background-color: var(--light-bg);
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .stat-label {
            font-size: 1rem;
            color: var(--light-text);
        }
    </style>
@endpush

@section('content')
    <!--app-content open-->
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- ROW-4 -->
                <div class="row">
                    <div class="col-12 col-sm-12 mt-4">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Investment Details</h3>
                                <div class="card-options ms-auto">
                                    <a href="{{ route('get.investments') }}" class="btn btn-primary btn-sm"> Back To List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- details start --}}
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <!-- Hero Section -->
                        <section class="hero-section">
                            <div class="container">
                                <h1 class="display-4 fw-bold mb-4">{{ $investment->title ?? 'Investment Title' }}</h1>
                                <p class="lead mb-4">
                                    {!! Str::limit(strip_tags($investment->summary), 80, '...') !!}
                                </p>
                            </div>
                        </section>

                        <!-- Navigation Tabs -->
                        <section class="container my-5">
                            <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="highlights-tab" data-bs-toggle="tab"
                                        data-bs-target="#highlights" type="button" role="tab">Highlights</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary"
                                        type="button" role="tab">Summary</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="asset-class-tab" data-bs-toggle="tab"
                                        data-bs-target="#asset_class" type="button" role="tab">Asset Class</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="investment_type-tab" data-bs-toggle="tab"
                                        data-bs-target="#investment_type" type="button" role="tab">Investment
                                        Type</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="strategy-tab" data-bs-toggle="tab"
                                        data-bs-target="#strategy" type="button" role="tab">Strategy</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                        data-bs-target="#documents" type="button" role="tab">Documents</button>
                                </li>
                            </ul>
                        </section>

                        <!-- Content Sections -->
                        <div class="tab-content container my-5" id="myTabContent">
                            <!-- Highlights Section -->
                            <div class="tab-pane fade show active" id="highlights" role="tabpanel">
                                <h2 class="section-title text-center">Highlights</h2>
                                <hr>

                                <div class="row">
                                    {{-- overview --}}
                                    <div class="col-md-6">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">Overview</h4>
                                            <p class="mb-4">{{ $investment->highlight->overview ?? '' }}</p>
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-md-6">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">Address</h4>
                                            <table class="fee-table">
                                                <tr>
                                                    <td>Country</td>
                                                    <td class="text-end">{{ $investment->country ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>City</td>
                                                    <td class="text-end"> {{ $investment->city ?? '' }} </td>
                                                </tr>
                                                <tr>
                                                    <td>State</td>
                                                    <td class="text-end"> {{ $investment->state }} </td>
                                                </tr>
                                                <tr>
                                                    <td>Address</td>
                                                    <td class="text-end">{{ $investment->address }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Fees -->
                                    <div class="col-md-6 mt-4">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">All Investment Fees</h4>
                                            @if ($investment->highlight && $investment->highlight->fees)
                                                <table class="fee-table table">
                                                    @foreach (json_decode($investment->highlight->fees, true) as $name => $amount)
                                                        <tr>
                                                            <td>{{ $name }}</td>
                                                            <td class="text-end">{{ $amount }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- targeted_returns --}}
                                    <div class="col-md-6 mt-4">
                                        <div class="card p-4 h-100">
                                            <h4 class="card-title">Targeted Returns</h4>
                                            @if ($investment->highlight && $investment->highlight->targeted_returns)
                                                <table class="fee-table table">
                                                    @foreach (json_decode($investment->highlight->targeted_returns, true) as $name => $amount)
                                                        <tr>
                                                            <td>{{ $name }}</td>
                                                            <td class="text-end">{{ $amount }}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <!-- Stats -->
                                    <div class="col-md-3 col-6 text-center">
                                        <div class="stat-value">{{ $investment->term ?? '-' }}</div>
                                        <div class="stat-label">Term</div>
                                    </div>
                                    <div class="col-md-3 col-6 text-center">
                                        <div class="stat-value">${{ $investment->min_investment ?? '-' }}</div>
                                        <div class="stat-label">Minimum Investment</div>
                                    </div>
                                    <div class="col-md-3 col-6 text-center">
                                        <div class="stat-value">{{ $investment->targeted_irr ?? '-' }}%</div>
                                        <div class="stat-label">Targeted Returns</div>
                                    </div>
                                    <div class="col-md-3 col-6 text-center">
                                        <div class="stat-value">{{ $investment->targeted_eps ?? '-' }}</div>
                                        <div class="stat-label">Targeted EPS</div>
                                    </div>
                                </div>
                            </div>

                            <!-- investment summary -->
                            <div class="tab-pane fade" id="summary" role="tabpanel">
                                <h2 class="section-title text-center">Investment Summary</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($investment->summary)
                                            <div class="card highlight-card p-4">
                                                <h4 class="card-title">Summary</h4>
                                                <p class="lead mb-4">{!! $investment->summary !!}</p>
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center p-4">
                                                <strong>No summary found.</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- asset class --}}
                            <div class="tab-pane fade" id="asset_class" role="tabpanel">
                                <h2 class="section-title text-center">Asset Class</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($investment->assetClass)
                                            <div class="card highlight-card p-4">
                                                <h4 class="card-title">{{ $investment->assetClass->name ?? '' }}</h4>
                                                <p class="mb-4">{{ $investment->assetClass->description ?? '' }}</p>
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center p-4">
                                                <strong>No class found.</strong>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            {{-- investment type --}}
                            <div class="tab-pane fade" id="investment_type" role="tabpanel">
                                <h2 class="section-title text-center">Investment Type</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($investment->investmentType)
                                            <div class="card highlight-card p-4">
                                                <h4 class="card-title">{{ $investment->investmentType->name ?? '' }}</h4>
                                                <p class="mb-4">{{ $investment->investmentType->description ?? '' }}</p>
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center p-4">
                                                <strong>No type found.</strong>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            {{-- investment strategy --}}
                            <div class="tab-pane fade" id="strategy" role="tabpanel">
                                <h2 class="section-title text-center">Investment Strategy</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($investment->strategy)
                                            <div class="card highlight-card p-4">
                                                <h4 class="card-title">{{ $investment->strategy->name }}</h4>
                                                <p class="mb-4">{!! $investment->strategy->description !!}</p>
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center p-4">
                                                <strong>No strategy found.</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- investment documents --}}
                            <div class="tab-pane fade" id="documents" role="tabpanel">
                                <div>
                                    <h2 class="section-title text-center">Investment Documents</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card highlight-card p-4 d-flex flex-wrap gap-2">
                                                @forelse($investment->documents as $doc)
                                                    <a href="{{ asset($doc->file_path) }}" target="_blank"
                                                        class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-file-pdf me-2"></i>
                                                        {{ $doc->name ?? 'Document' }}
                                                    </a>
                                                @empty
                                                    <div class="alert alert-warning text-center p-4">
                                                        <strong>No documents uploaded.</strong>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="section-title text-center">Investment Images</h2>
                                    <div class="row g-3 mt-3">
                                        @forelse($investment->images as $img)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card shadow-sm border-0 rounded-3">
                                                    <img src="{{ asset($img->image_url) }}"
                                                        class="card-img-top rounded-top-3" alt="Investment Image">

                                                    <div class="card-body text-center">
                                                        <a href="{{ asset($img->image_url) }}" target="_blank"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                           <div class="alert alert-warning text-center p-4">
                                                        <strong>No image uploaded.</strong>
                                                    </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <footer>
                            <div class="container text-center">
                                <div class="mt-3">
                                    <p>
                                        <i> Lorem ipsum dolor sit amet consectetur adipisicing elit. Non atque neque,
                                            quisquam voluptate doloribus unde iure voluptatem sint rem, est aspernatur
                                            nostrum beatae, quis laudantium assumenda fugiat eveniet repudiandae nulla
                                            accusamus perferendis saepe nemo quibusdam. Porro quasi, corrupti impedit
                                            facilis, nesciunt dolor non numquam sed doloremque veniam nostrum vitae maxime
                                            voluptatibus eaque repudiandae. Alias numquam voluptatibus, nesciunt quasi sunt
                                            laborum sint iusto est quas maiores quidem minima asperiores consequatur,
                                            dolores neque sequi itaque commodi similique? Nostrum sit placeat corrupti quae
                                            ratione hic, perferendis illo itaque repellat corporis, accusantium eos magni
                                            blanditiis pariatur architecto accusamus. Omnis nulla id dolore accusantium non.
                                        </i>
                                    </p>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
