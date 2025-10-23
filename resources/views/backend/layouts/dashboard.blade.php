@extends('backend.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        .stat-card {
            border-radius: 12px;
            padding: 25px;
            color: white;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .deal-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .roi-positive {
            color: #28a745;
            font-weight: 600;
        }

        .roi-negative {
            color: #dc3545;
            font-weight: 600;
        }

        .doc-item {
            background: #0d6efd;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .doc-item:hover {
            background: #0b5ed7;
            transform: translateX(5px);
        }

        .skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- Page Header -->
                <div class="page-header mb-4">
                    <div>
                        <h1 class="page-title">Dashboard</h1>
                        <p class="text-muted">Here's an overview of your investment portfolio.</p>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row" id="statsCards">
                    <!-- Total Invested -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="stat-card bg-primary">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fa fa-dollar-sign"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 opacity-75">Total Invested Amount</p>
                                    <h2 class="mb-0" id="totalInvested">
                                        <span class="skeleton"
                                            style="display: inline-block; width: 120px; height: 30px; border-radius: 4px;"></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Returns -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="stat-card bg-success">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 opacity-75">Total Returns</p>
                                    <h2 class="mb-0" id="totalReturns">
                                        <span class="skeleton"
                                            style="display: inline-block; width: 120px; height: 30px; border-radius: 4px;"></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Deals -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="stat-card bg-info">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fa fa-handshake"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 opacity-75">Active Deals</p>
                                    <h2 class="mb-0" id="activeDeals">
                                        <span class="skeleton"
                                            style="display: inline-block; width: 50px; height: 30px; border-radius: 4px;"></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average ROI -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
                        <div class="stat-card bg-warning">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3">
                                    <i class="fa fa-percentage"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 opacity-75">Average ROI</p>
                                    <h2 class="mb-0" id="averageROI">
                                        <span class="skeleton"
                                            style="display: inline-block; width: 80px; height: 30px; border-radius: 4px;"></span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Approved Deals & Documents -->
                <div class="row">
                    <!-- Approved Deals Table -->
                    <div class="col-xl-8 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Approved Deals
                                </h3>
                                <a href="{{ route('get.investments') }}" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>DEAL NAME</th>
                                                <th>INVESTMENT</th>
                                                <th>ROI</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="approvedDealsTable">
                                            <!-- Loading skeleton -->
                                            <tr>
                                                <td colspan="5">
                                                    <div class="skeleton"
                                                        style="height: 40px; border-radius: 4px; margin-bottom: 10px;">
                                                    </div>
                                                    <div class="skeleton"
                                                        style="height: 40px; border-radius: 4px; margin-bottom: 10px;">
                                                    </div>
                                                    <div class="skeleton" style="height: 40px; border-radius: 4px;"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KYC Documents -->
                    <div class="col-xl-4 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mb-0">
                                    <i class="fa fa-file-alt text-primary me-2"></i>
                                    Kye Documents
                                </h3>
                            </div>
                            <div class="card-body" id="kycDocuments">
                                <!-- Loading skeleton -->
                                <div class="skeleton" style="height: 60px; border-radius: 10px; margin-bottom: 10px;"></div>
                                <div class="skeleton" style="height: 60px; border-radius: 10px; margin-bottom: 10px;"></div>
                                <div class="skeleton" style="height: 60px; border-radius: 10px; margin-bottom: 10px;"></div>
                                <div class="skeleton" style="height: 60px; border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Investment Details Modal -->
    <div class="modal fade" id="investmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Investment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load dashboard stats
            loadDashboardStats();

            function loadDashboardStats() {
                $.ajax({
                    url: "{{ route('dashboard.stats') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            // Update stat cards
                            $('#totalInvested').html(data.total_invested.value);
                            $('#totalReturns').html(data.total_returns.value);
                            $('#activeDeals').html(data.active_deals.value);
                            $('#averageROI').html(data.average_roi.value);

                            // Populate approved deals table
                            populateApprovedDeals(data.approved_deals);

                            // Populate KYC documents
                            populateKycDocuments(data.kyc_documents);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load dashboard statistics');
                        console.error(xhr);
                    }
                });
            }

            function populateApprovedDeals(deals) {
                const tbody = $('#approvedDealsTable');
                tbody.empty();

                if (deals.length === 0) {
                    tbody.html('<tr><td colspan="5" class="text-center">No approved deals found</td></tr>');
                    return;
                }

                deals.forEach(deal => {
                    const avatarColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                    const randomColor = avatarColors[Math.floor(Math.random() * avatarColors.length)];
                    const initials = deal.name.substring(0, 2).toUpperCase();

                    const roiClass = deal.roi_raw >= 0 ? 'roi-positive' : 'roi-negative';

                    const row = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="deal-avatar me-2" style="background-color: ${randomColor}">
                                ${initials}
                            </div>
                            <div>
                                <div class="fw-bold">${deal.name}</div>
                                <small class="text-muted">${deal.asset_class || deal.type}</small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold">${deal.investment}</td>
                    <td class="${roiClass}">${deal.roi}</td>
                    <td><span class="badge bg-${deal.status === 'Active' ? 'success' : 'secondary'}">${deal.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary view-details" data-id="${deal.id}">
                            View Details
                        </button>
                    </td>
                </tr>
            `;
                    tbody.append(row);
                });

                // Attach click event to view details buttons
                $('.view-details').on('click', function() {
                    const investmentId = $(this).data('id');
                    viewInvestmentDetails(investmentId);
                });
            }

            function populateKycDocuments(documents) {
                const container = $('#kycDocuments');
                container.empty();

                if (documents.length === 0) {
                    container.html('<p class="text-center text-muted">No documents found</p>');
                    return;
                }

                documents.forEach((doc, index) => {
                    const docHtml = `
                <div class="doc-item" onclick="window.open('${doc.file_path}', '_blank')">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fa fa-file-pdf fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${doc.name}</div>
                            <small class="opacity-75">${doc.investment_title}</small>
                        </div>
                        <div>
                            <i class="fa fa-download"></i>
                        </div>
                    </div>
                </div>
            `;
                    container.append(docHtml);
                });
            }

            function viewInvestmentDetails(id) {
                $('#investmentModal').modal('show');
                $('#modalContent').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

                $.ajax({
                    url: `/admin/investments/${id}/details`,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            const inv = response.data;
                            const content = `
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                ${inv.thumbnail ? `<img src="${inv.thumbnail}" class="img-fluid rounded" alt="${inv.title}">` : '<div class="bg-light rounded" style="height: 200px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-image fa-3x text-muted"></i></div>'}
                            </div>
                            <div class="col-md-8">
                                <h4>${inv.title}</h4>
                                <p class="text-muted">${inv.summary || 'No summary available'}</p>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Investment:</strong> ${inv.min_investment || 'N/A'}
                                    </div>
                                    <div class="col-6">
                                        <strong>Target IRR:</strong> ${inv.targeted_irr || 'N/A'}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>Term:</strong> ${inv.term || 'N/A'}
                                    </div>
                                    <div class="col-6 mt-2">
                                        <strong>Status:</strong> <span class="badge bg-success">${inv.status}</span>
                                    </div>
                                </div>

                                ${inv.sponsor ? `<p><strong>Sponsor:</strong> ${inv.sponsor}</p>` : ''}
                                ${inv.fund_name ? `<p><strong>Fund:</strong> ${inv.fund_name}</p>` : ''}
                                ${inv.property_type ? `<p><strong>Property Type:</strong> ${inv.property_type}</p>` : ''}

                                ${inv.banker_email ? `<p><strong>Contact:</strong> ${inv.banker_email}</p>` : ''}
                            </div>
                        </div>
                    `;
                            $('#modalContent').html(content);
                        }
                    },
                    error: function(xhr) {
                        $('#modalContent').html('<p class="text-danger">Failed to load details</p>');
                        toastr.error('Failed to load investment details');
                    }
                });
            }

            // Refresh every 5 minutes
            setInterval(loadDashboardStats, 300000);
        });
    </script>
@endpush
