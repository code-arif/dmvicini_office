@extends('backend.app')
@section('title', 'Investments')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
    <style>
        .filter-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filter-card .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .btn-filter {
            background: #0d6efd;
            color: white;
            padding: 0.5rem 1.5rem;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
            padding: 0.5rem 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Deals</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Deals</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>

                <!-- FILTERS -->
                <div class="row">
                    <div class="col-12">
                        <div class="filter-card bg-light">
                            <h5 class="mb-3">
                                <i class="fa fa-filter"></i> Filters
                            </h5>
                            <form id="filterForm">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Search</label>
                                        <input type="text" id="searchInput" class="form-control"
                                            placeholder="Search by title...">
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Status</label>
                                        <select id="statusFilter" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Asset Class</label>
                                        <select id="assetClassFilter" class="form-select">
                                            <option value="">All Classes</option>
                                            @foreach ($asset_classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Investment Type</label>
                                        <select id="investmentTypeFilter" class="form-select">
                                            <option value="">All Types</option>
                                            @foreach ($investment_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">Strategy</label>
                                        <select id="strategyFilter" class="form-select">
                                            <option value="">All Strategies</option>
                                            @foreach ($strategies as $strategy)
                                                <option value="{{ $strategy->id }}">{{ $strategy->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 mb-3 d-flex align-items-end">
                                        <button type="button" id="resetFilters" class="btn btn-reset w-100 py-1"
                                            style="margin-bottom: 3px;">
                                            <i class="fa fa-redo"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- DATA TABLE -->
                <div class="row">
                    <div class="col-12">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Deals List</h3>
                                <div class="card-options ms-auto">
                                    <a href="{{ route('investment.create') }}" class="btn btn-primary btn-sm"
                                        style="margin-right: 10px">
                                        <i class="fa fa-plus"></i> Add Deal
                                    </a>
                                    <a href="{{ route('investment.create') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fa fa-trash"></i> Trash
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="bg-transparent border-bottom-0">#</th>
                                                <th class="bg-transparent border-bottom-0">Title</th>
                                                <th class="bg-transparent border-bottom-0">Asset Class</th>
                                                <th class="bg-transparent border-bottom-0">Type</th>
                                                <th class="bg-transparent border-bottom-0">Location</th>
                                                <th class="bg-transparent border-bottom-0">Status</th>
                                                <th class="bg-transparent border-bottom-0">Created</th>
                                                <th class="bg-transparent border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Dropdown status styling */
        .dropdown-item.active {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .dropdown-item:hover {
            background-color: #e9ecef;
        }

        .dropdown-menu {
            min-width: 140px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            // Initialize DataTable
            let dTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('investment.list') }}",
                    type: "GET",
                    data: function(d) {
                        d.search_text = $('#searchInput').val();
                        d.status = $('#statusFilter').val();
                        d.asset_class = $('#assetClassFilter').val();
                        d.investment_type = $('#investmentTypeFilter').val();
                        d.strategy = $('#strategyFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'asset_class',
                        name: 'assetClass.name'
                    },
                    {
                        data: 'investment_type',
                        name: 'investmentType.name'
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Filter event listeners
            $('#searchInput, #statusFilter, #assetClassFilter, #investmentTypeFilter, #strategyFilter').on(
                'change keyup',
                function() {
                    dTable.ajax.reload();
                });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                dTable.ajax.reload();
            });
        });
    </script>

    {{-- Update status with dropdown --}}
    <script>
        $(document).on('click', '.changeStatus', function(e) {
            e.preventDefault();

            let element = $(this);
            let id = element.data('id');
            let newStatus = element.data('status');
            let currentStatus = element.data('current');

            // If clicking on current status, do nothing
            if (newStatus === currentStatus) {
                return;
            }

            // Status label mapping
            const statusLabels = {
                'draft': 'Draft',
                'active': 'Active',
                'closed': 'Closed'
            };

            // Sweet Alert confirmation
            Swal.fire({
                title: 'Change Status?',
                html: `Change status from <strong class="text-primary">${statusLabels[currentStatus]}</strong> to <strong class="text-success">${statusLabels[newStatus]}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-check me-1"></i> Yes, Change',
                cancelButtonText: '<i class="fa fa-times me-1"></i> Cancel',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Updating Status...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Update status via AJAX
                    $.ajax({
                        url: "{{ route('investment.status.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            status: newStatus
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload table
                                    $('#datatable').DataTable().ajax.reload(null,
                                    false);
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed!',
                                    text: res.message || 'Failed to update status',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Something went wrong!';

                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                } else if (xhr.responseJSON.errors) {
                                    errorMsg = Object.values(xhr.responseJSON.errors).flat()
                                        .join('<br>');
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMsg,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });
    </script>

    {{-- Delete investment --}}
    <script>
        function showDeleteConfirm(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this investment? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Yes, Delete',
                cancelButtonText: '<i class="fa fa-times me-1"></i> Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function deleteItem(id) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let url = "{{ route('investment.destroy', ':id') }}";
            let csrfToken = '{{ csrf_token() }}';

            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: resp.message || 'Investment deleted successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#datatable').DataTable().ajax.reload();
                    });
                },
                error: function(error) {
                    let errorMsg = 'Failed to delete investment';

                    if (error.responseJSON && error.responseJSON.message) {
                        errorMsg = error.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            });
        }
    </script>
@endpush
