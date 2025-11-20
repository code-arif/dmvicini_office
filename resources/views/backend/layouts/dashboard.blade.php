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

        .user-item {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .user-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .badge-pending {
            background: #ffc107;
            color: #000;
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
                        <h1 class="page-title">Pinnacle Alt's Admin Dashboard</h1>
                    </div>
                </div>

                <!-- Approved Deals & Pending Users -->
                <div class="row">
                    <!-- Approved Deals Table -->
                    <div class="col-xl-8 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <h3 class="card-title mb-0">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    Active Deals
                                </h3>
                                <a href="{{ route('get.investments') }}" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>DEAL NAME</th>
                                                <th>STATUS</th>
                                                <th>DETAILS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="approvedDealsTable">
                                            <!-- Loading skeleton -->
                                            <tr>
                                                <td colspan="3">
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

                    <!-- Pending Users for Approval -->
                    <div class="col-xl-4 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-warning py-5">
                                <h3 class="card-title mb-0 text-dark">
                                    <i class="fa fa-user-clock me-2"></i>
                                    Pending User Approvals
                                </h3>
                            </div>
                            <div class="card-body" id="pendingUsers" style="max-height: 600px; overflow-y: auto;">
                                <!-- Loading skeleton -->
                                <div class="skeleton" style="height: 70px; border-radius: 8px; margin-bottom: 12px;"></div>
                                <div class="skeleton" style="height: 70px; border-radius: 8px; margin-bottom: 12px;"></div>
                                <div class="skeleton" style="height: 70px; border-radius: 8px; margin-bottom: 12px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Investment Details Modal -->
    <div class="modal fade" id="investmentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="padding: 0px 20px 20px 20px">
                <div class="modal-header">
                    <h5 class="modal-title">Investment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="investmentModalContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Review Modal -->
    <div class="modal fade" id="userReviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-user-check me-2"></i>
                        User Registration Review
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="userReviewModalContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="approveUserBtn">
                        <i class="fa fa-check me-2"></i>Approve User
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentUserId = null;

            // Load dashboard stats
            loadDashboardStats();

            function loadDashboardStats() {
                $.ajax({
                    url: "{{ route('dashboard.stats') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            // Populate approved deals table
                            populateApprovedDeals(data.approved_deals);

                            // Populate pending users
                            populatePendingUsers(data.pending_users);
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
                    tbody.html('<tr><td colspan="3" class="text-center">No approved deals found</td></tr>');
                    return;
                }

                deals.forEach(deal => {
                    const avatarColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                    const randomColor = avatarColors[Math.floor(Math.random() * avatarColors.length)];
                    const initials = deal.name.substring(0, 2).toUpperCase();

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

            function populatePendingUsers(users) {
                const container = $('#pendingUsers');
                container.empty();

                if (users.length === 0) {
                    container.html(
                        '<div class="text-center text-muted py-4"><i class="fa fa-check-circle fa-3x mb-3"></i><p>No pending approvals</p></div>'
                    );
                    return;
                }

                users.forEach((user, index) => {
                    const initials = user.full_name.split(' ').map(n => n[0]).join('').toUpperCase()
                        .substring(0, 2);
                    const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'];
                    const bgColor = colors[index % colors.length];

                    const userHtml = `
                        <div class="user-item" data-user-id="${user.id}">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="background: ${bgColor}">
                                    ${initials}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${user.full_name}</div>
                                    <small class="text-muted d-block">${user.email}</small>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar me-1"></i>${user.registered_date}
                                    </small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary review-user" data-user-id="${user.id}">
                                        <i class="fa fa-eye"></i> Review
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(userHtml);
                });

                // Attach click event to review buttons
                $('.review-user').on('click', function(e) {
                    e.stopPropagation();
                    const userId = $(this).data('user-id');
                    viewUserDetails(userId);
                });

                // Also make entire user-item clickable
                $('.user-item').on('click', function() {
                    const userId = $(this).data('user-id');
                    viewUserDetails(userId);
                });
            }

            function viewUserDetails(userId) {
                currentUserId = userId;
                $('#userReviewModal').modal('show');
                $('#userReviewModalContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: `/admin/users/${userId}/details`,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            const user = response.data;
                            const content = `
                                <div class="row">
                                    <!-- Basic Info -->
                                    <div class="col-12 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="fa fa-user me-2"></i>Basic Information
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Name:</strong> ${user.full_name}</p>
                                                        <p><strong>Email:</strong> ${user.email}</p>
                                                        <p><strong>Phone:</strong> ${user.phone || 'N/A'}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Title:</strong> ${user.title || 'N/A'}</p>
                                                        <p><strong>Country:</strong> ${user.country || 'N/A'}</p>
                                                        <p><strong>Registered:</strong> ${user.registered_at}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Firm Info -->
                                    <div class="col-12 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="fa fa-building me-2"></i>Firm Information
                                                </h5>
                                                <p><strong>Firm Name:</strong> ${user.firm_name || 'N/A'}</p>
                                                ${user.firm_info ? `
                                                            <p><strong>Registered:</strong> ${user.firm_info.is_registered ? 'Yes' : 'No'}</p>
                                                            ${user.firm_info.firm_crd ? `<p><strong>Firm CRD:</strong> ${user.firm_info.firm_crd}</p>` : ''}
                                                            ${user.firm_info.individual_crd ? `<p><strong>Individual CRD:</strong> ${user.firm_info.individual_crd}</p>` : ''}
                                                            ${user.firm_info.firm_aum ? `<p><strong>Firm AUM:</strong> ${user.firm_info.firm_aum}</p>` : ''}
                                                            ${user.firm_info.address ? `
                                                        <p><strong>Address:</strong><br>
                                                        ${user.firm_info.address}<br>
                                                        ${user.firm_info.city}, ${user.firm_info.state} ${user.firm_info.zip}
                                                        </p>
                                                    ` : ''}
                                                        ` : '<p class="text-muted">No firm information available</p>'}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Investor Info -->
                                    <div class="col-12 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <i class="fa fa-chart-line me-2"></i>Investor Information
                                                </h5>
                                                <p><strong>Investor Type:</strong> ${user.investor_type || 'N/A'}</p>
                                                ${user.investor_type_other ? `<p><strong>Other Type:</strong> ${user.investor_type_other}</p>` : ''}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Compliance -->
                                    ${user.compliance ? `
                                            <div class="col-12 mb-4">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">
                                                            <i class="fa fa-shield-alt me-2"></i>Compliance Acknowledgments
                                                        </h5>
                                                        <p>
                                                            <i class="fa fa-${user.compliance.terms_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                                                            Terms & Conditions ${user.compliance.terms_agreed_at ? `(${user.compliance.terms_agreed_at})` : ''}
                                                        </p>
                                                        <p>
                                                            <i class="fa fa-${user.compliance.privacy_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                                                            Privacy Policy ${user.compliance.privacy_agreed_at ? `(${user.compliance.privacy_agreed_at})` : ''}
                                                        </p>
                                                        <p>
                                                            <i class="fa fa-${user.compliance.investor_acknowledgment ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                                                            Investor Acknowledgment ${user.compliance.investor_acknowledgment_at ? `(${user.compliance.investor_acknowledgment_at})` : ''}
                                                        </p>
                                                        <p>
                                                            <i class="fa fa-${user.compliance.confidentiality_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                                                            Confidentiality Agreement ${user.compliance.confidentiality_agreed_at ? `(${user.compliance.confidentiality_agreed_at})` : ''}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            ` : ''}
                                </div>
                            `;
                            $('#userReviewModalContent').html(content);
                        }
                    },
                    error: function(xhr) {
                        $('#userReviewModalContent').html(
                            '<p class="text-danger">Failed to load user details</p>');
                        toastr.error('Failed to load user details');
                    }
                });
            }

            // Approve User
            $('#approveUserBtn').on('click', function() {
                if (!currentUserId) return;

                Swal.fire({
                    title: 'Approve User?',
                    text: "This will grant the user full access to the platform.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const approveBtn = $(this);
                        approveBtn.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin me-2"></i>Approving...');

                        $.ajax({
                            url: "{{ route('user.approve', ':id') }}".replace(':id',
                                currentUserId),
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Approved!',
                                        text: 'User has been approved successfully.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    $('#userReviewModal').modal('hide');
                                    loadDashboardStats(); // Reload to update list
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to approve user. Please try again.',
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545'
                                });
                                console.error(xhr);
                            },
                            complete: function() {
                                approveBtn.prop('disabled', false).html(
                                    '<i class="fa fa-check me-2"></i>Approve User');
                            }
                        });
                    }
                });
            });

            function viewInvestmentDetails(id) {
                $('#investmentModal').modal('show');
                $('#investmentModalContent').html(`
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
                                        ${inv.thumbnail ? `<img src="${inv.thumbnail}" class="img-fluid" alt="${inv.title}">` : '<div class="bg-light rounded" style="height: 200px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-image fa-3x text-muted"></i></div>'}
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
                            $('#investmentModalContent').html(content);
                        }
                    },
                    error: function(xhr) {
                        $('#investmentModalContent').html(
                            '<p class="text-danger">Failed to load details</p>');
                        toastr.error('Failed to load investment details');
                    }
                });
            }

            // Refresh every 5 minutes
            setInterval(loadDashboardStats, 300000);
        });
    </script>
@endpush
