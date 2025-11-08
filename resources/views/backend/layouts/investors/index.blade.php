@extends('backend.app')
@section('title', 'Investors')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
    <style>
        /* Clean Modal Scroll Fix */
        #viewInvestorModal .modal-dialog {
            max-width: 900px;
            margin: 30px auto;
        }

        #viewInvestorModal .modal-content {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        /* Fixed Header - Always Visible */
        #viewInvestorModal .modal-header {
            flex-shrink: 0;
            border-bottom: 2px solid #dee2e6;
            padding: 15px 20px;
            background: #fff;
            z-index: 10;
        }

        /* Scrollable Body - Main Fix */
        #viewInvestorModal .modal-body {
            flex: 1 1 auto;
            overflow-y: auto !important;
            overflow-x: hidden;
            padding: 20px;
            max-height: calc(90vh - 130px);
        }

        /* Fixed Footer - Always Visible */
        #viewInvestorModal .modal-footer {
            flex-shrink: 0;
            border-top: 2px solid #dee2e6;
            padding: 15px 20px;
            background: #fff;
            z-index: 10;
        }

        /* Beautiful Scrollbar */
        #viewInvestorModal .modal-body::-webkit-scrollbar {
            width: 12px;
        }

        #viewInvestorModal .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #viewInvestorModal .modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            border: 2px solid #f1f1f1;
        }

        #viewInvestorModal .modal-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2 0%, #667eea 100%);
        }

        /* Firefox */
        #viewInvestorModal .modal-body {
            scrollbar-width: thin;
            scrollbar-color: #667eea #f1f1f1;
        }

        /* Info Row Styling */
        .modal-body .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .modal-body .info-row:last-child {
            border-bottom: none;
        }

        .modal-body .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .modal-body .info-value {
            color: #6c757d;
            font-size: 14px;
        }

        /* Section Header */
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            margin: 20px -20px 15px -20px;
            font-weight: 600;
            font-size: 15px;
            border-left: 5px solid #ffd700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section-header:first-of-type {
            margin-top: 0;
        }

        /* Compliance Check */
        .compliance-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .compliance-check i {
            font-size: 20px;
        }

        /* Avatar Section */
        .investor-avatar-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 25px 20px;
            margin: -20px -20px 20px -20px;
            border-bottom: 3px solid #667eea;
            text-align: center;
        }

        .investor-avatar-section img {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Badge & Alert Improvements */
        .badge {
            padding: 6px 12px;
            font-weight: 600;
            font-size: 12px;
        }

        .alert {
            border-radius: 8px;
            border-left: 4px solid;
        }

        code {
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            color: #e83e8c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #viewInvestorModal .modal-dialog {
                margin: 10px;
                max-width: calc(100% - 20px);
            }

            #viewInvestorModal .modal-content {
                max-height: 95vh;
            }

            #viewInvestorModal .modal-body {
                max-height: calc(95vh - 130px);
                padding: 15px;
            }

            .section-header {
                margin-left: -15px;
                margin-right: -15px;
                padding: 10px 15px;
            }

            .investor-avatar-section {
                margin-left: -15px;
                margin-right: -15px;
                padding: 20px 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <h1 class="page-title">All Investor List</h1>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investors</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Investor List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Info</th>
                                                <th>Investor Details</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Investor Modal -->
    <div class="modal fade" id="viewInvestorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Investor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="investorDetailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="modalFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="approveFromModal" style="display: none;">
                        <i class="fas fa-check me-1"></i> Approve Investor
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('investor.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'investor_info',
                        name: 'investor_info',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            let currentInvestorId = null;

            // View Investor Details
            $(document).on('click', '.view-investor', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                currentInvestorId = id;

                $('#viewInvestorModal').modal('show');
                loadInvestorDetails(id);
            });

            function loadInvestorDetails(id) {
                $.ajax({
                    url: "{{ route('investor.show', '') }}/" + id,
                    type: 'GET',
                    success: function(res) {
                        if (res.success) {
                            displayInvestorDetails(res.data);
                        } else {
                            toastr.error('Failed to load investor details');
                        }
                    },
                    error: function() {
                        toastr.error('Server error! Try again later.');
                        $('#viewInvestorModal').modal('hide');
                    }
                });
            }

            function displayInvestorDetails(data) {
                let html = '';

                // User Info Section with Avatar
                html += `
        <div class="investor-avatar-section text-center">
            <img src="${data.user.avatar}" alt="avatar" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover; border: 3px solid #007bff;">
            <h5>${data.profile ? data.profile.first_name + ' ' + data.profile.last_name : 'N/A'}</h5>
            <p class="text-muted mb-2">${data.user.email}</p>
            <div>
                <span class="badge ${getAccessBadgeClass(data.user.access_level, data.user.is_active)} me-2">
                    ${data.user.is_active && data.user.access_level === 'full' ? 'Approved' : ucFirst(data.user.access_level)}
                </span>
                <span class="badge ${data.user.is_active ? 'bg-success' : 'bg-danger'}">
                    ${data.user.is_active ? 'Active' : 'Inactive'}
                </span>
            </div>
        </div>
    `;

                // Profile Information
                if (data.profile) {
                    html += '<div class="section-header">Profile Information</div>';
                    html += `
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">${data.profile.first_name} ${data.profile.last_name}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Title</div>
                        <div class="info-value">${data.profile.title || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Firm Name</div>
                        <div class="info-value">${data.profile.firm_name || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">${data.profile.phone || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Country</div>
                        <div class="info-value">${data.profile.country || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Investor Type</div>
                        <div class="info-value">${data.profile.investor_type || 'N/A'}</div>
                    </div>
                </div>
                ${data.profile.investor_type_other ? `
                            <div class="col-12">
                                <div class="info-row">
                                    <div class="info-label">Investor Type (Other Details)</div>
                                    <div class="info-value">${data.profile.investor_type_other}</div>
                                </div>
                            </div>
                            ` : ''}
            </div>
        `;
                }

                // Firm Information
                if (data.firm) {
                    html += '<div class="section-header">Firm Information</div>';
                    html += `
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Registered with SEC</div>
                        <div class="info-value">
                            <span class="badge ${data.firm.is_registered ? 'bg-success' : 'bg-warning'}">
                                ${data.firm.is_registered ? 'Yes' : 'No'}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Firm CRD Number</div>
                        <div class="info-value">${data.firm.firm_crd || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Individual CRD Number</div>
                        <div class="info-value">${data.firm.individual_crd || 'N/A'}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Assets Under Management (AUM)</div>
                        <div class="info-value fw-bold text-success">${data.firm.firm_aum}</div>
                    </div>
                </div>
            </div>
        `;

                    // Full Address Display
                    let fullAddress = '';
                    if (data.firm.address || data.firm.city || data.firm.state || data.firm.zip) {
                        fullAddress = [
                            data.firm.address,
                            data.firm.city,
                            data.firm.state,
                            data.firm.zip
                        ].filter(Boolean).join(', ');
                    }

                    if (fullAddress) {
                        html += `
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="info-row">
                            <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Firm Address</div>
                            <div class="info-value">${fullAddress}</div>
                        </div>
                    </div>
                </div>
            `;
                    }

                    // Explanation if not registered
                    if (data.firm.explanation_if_not_registered) {
                        html += `
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="info-row">
                            <div class="info-label">Explanation (Not SEC Registered)</div>
                            <div class="info-value alert alert-info mb-0">${data.firm.explanation_if_not_registered}</div>
                        </div>
                    </div>
                </div>
            `;
                    }
                } else {
                    html += '<div class="section-header">Firm Information</div>';
                    html +=
                        '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No firm information available</div>';
                }

                // Access Request Information
                if (data.access_request) {
                    html += '<div class="section-header">Access Request Details</div>';
                    html += `
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Request Status</div>
                        <div class="info-value">
                            <span class="badge ${getRequestStatusBadge(data.access_request.status)}">
                                ${data.access_request.status}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">Verification Type</div>
                        <div class="info-value">${data.access_request.verification_type || 'N/A'}</div>
                    </div>
                </div>
                ${data.access_request.verified_at ? `
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">Verified Date</div>
                                    <div class="info-value"><i class="far fa-calendar-check me-2"></i>${data.access_request.verified_at}</div>
                                </div>
                            </div>
                            ` : ''}
                ${data.access_request.verifier_document ? `
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">Verifier Document</div>
                                    <div class="info-value">${data.access_request.verifier_document}</div>
                                </div>
                            </div>
                            ` : ''}
                ${data.access_request.verifier_reference ? `
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">Verifier Reference</div>
                                    <div class="info-value">${data.access_request.verifier_reference}</div>
                                </div>
                            </div>
                            ` : ''}
                ${data.access_request.admin_notes ? `
                            <div class="col-12">
                                <div class="info-row">
                                    <div class="info-label">Admin Notes</div>
                                    <div class="info-value alert alert-secondary mb-0">${data.access_request.admin_notes}</div>
                                </div>
                            </div>
                            ` : ''}
            </div>
        `;
                }

                // Compliance Information
                if (data.compliance) {
                    html += '<div class="section-header">Compliance & Acknowledgments</div>';
                    html += `
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="compliance-check">
                            <i class="fas fa-${data.compliance.terms_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                            <span>Terms & Conditions</span>
                        </div>
                        ${data.compliance.terms_agreed_at ? `<small class="text-muted ms-4">Agreed: ${data.compliance.terms_agreed_at}</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="compliance-check">
                            <i class="fas fa-${data.compliance.privacy_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                            <span>Privacy Policy</span>
                        </div>
                        ${data.compliance.privacy_agreed_at ? `<small class="text-muted ms-4">Agreed: ${data.compliance.privacy_agreed_at}</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="compliance-check">
                            <i class="fas fa-${data.compliance.investor_acknowledgment ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                            <span>Investor Acknowledgment</span>
                        </div>
                        ${data.compliance.investor_acknowledgment_at ? `<small class="text-muted ms-4">Agreed: ${data.compliance.investor_acknowledgment_at}</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="compliance-check">
                            <i class="fas fa-${data.compliance.confidentiality_agreed ? 'check-circle text-success' : 'times-circle text-danger'}"></i>
                            <span>Confidentiality Agreement</span>
                        </div>
                        ${data.compliance.confidentiality_agreed_at ? `<small class="text-muted ms-4">Agreed: ${data.compliance.confidentiality_agreed_at}</small>` : ''}
                    </div>
                </div>
                ${data.compliance.marketing_opt_in !== null ? `
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="compliance-check">
                                        <i class="fas fa-${data.compliance.marketing_opt_in ? 'check-circle text-info' : 'times-circle text-secondary'}"></i>
                                        <span>Marketing Communications (Optional)</span>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                ${data.compliance.ip_address ? `
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label">IP Address</div>
                                    <div class="info-value"><code>${data.compliance.ip_address}</code></div>
                                </div>
                            </div>
                            ` : ''}
                ${data.compliance.user_agent ? `
                            <div class="col-12">
                                <div class="info-row">
                                    <div class="info-label">User Agent</div>
                                    <div class="info-value"><small class="text-muted">${data.compliance.user_agent}</small></div>
                                </div>
                            </div>
                            ` : ''}
            </div>
        `;
                } else {
                    html += '<div class="section-header">Compliance & Acknowledgments</div>';
                    html +=
                        '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No compliance data available</div>';
                }

                // Account Information
                html += `
        <div class="section-header">Account Information</div>
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <div class="info-label">User ID</div>
                    <div class="info-value"><code>#${data.user.id}</code></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <div class="info-label">Registration Date</div>
                    <div class="info-value"><i class="far fa-clock me-2"></i>${data.user.created_at}</div>
                </div>
            </div>
        </div>
    `;

                $('#investorDetailsContent').html(html);

                // Show approve button if not already approved
                if (!data.user.is_active || data.user.access_level !== 'full') {
                    $('#approveFromModal').show().data('id', data.user.id);
                } else {
                    $('#approveFromModal').hide();
                }
            }

            function getAccessBadgeClass(accessLevel, isActive) {
                if (isActive && accessLevel === 'full') return 'bg-success';
                if (accessLevel === 'provisional') return 'bg-warning text-dark';
                if (accessLevel === 'review') return 'bg-info';
                if (accessLevel === 'limited') return 'bg-secondary';
                return 'bg-danger';
            }

            function getRequestStatusBadge(status) {
                const badges = {
                    'pending': 'bg-warning text-dark',
                    'provisional': 'bg-info',
                    'approved': 'bg-success',
                    'rejected': 'bg-danger',
                    'limited': 'bg-secondary',
                    'expired': 'bg-dark'
                };
                return badges[status.toLowerCase()] || 'bg-secondary';
            }

            function ucFirst(str) {
                if (!str) return '';
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            function getAccessBadgeClass(accessLevel, isActive) {
                if (isActive && accessLevel === 'full') return 'bg-success';
                if (accessLevel === 'provisional') return 'bg-warning';
                if (accessLevel === 'review') return 'bg-info';
                if (accessLevel === 'limited') return 'bg-secondary';
                return 'bg-danger';
            }

            function ucFirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            // Approve from table
            $(document).on('click', '.approve-investor', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                approveInvestor(id);
            });

            // Approve from modal
            $(document).on('click', '#approveFromModal', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                approveInvestor(id);
            });

            function approveInvestor(id) {
                Swal.fire({
                    title: 'Approve Investor?',
                    text: 'This will grant full access to the investor.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, approve!',
                    preConfirm: (adminNotes) => {
                        return {
                            id: id,
                        };
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('investor.approve') }}",
                            type: 'POST',
                            data: result.value,
                            success: function(res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    table.ajax.reload(null, false);
                                    $('#viewInvestorModal').modal('hide');
                                } else {
                                    toastr.error('Failed to approve investor');
                                }
                            },
                            error: function() {
                                toastr.error('Server error! Try again later.');
                            }
                        });
                    }
                });
            }

            // Delete Investor
            $(document).on('click', '.delete-investor', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This investor will be permanently deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('investor.destroy', '') }}/" + id,
                            type: 'DELETE',
                            success: function(res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    table.ajax.reload(null, false);
                                } else {
                                    toastr.error(res.message);
                                }
                            },
                            error: function(xhr) {
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    toastr.error(xhr.responseJSON.message);
                                } else {
                                    toastr.error('Server error! Try again later.');
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
