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
                        <h1 class="page-title">Investments</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investments</a></li>
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
                                        <button type="button" id="resetFilters" class="btn btn-reset w-100 py-1" style="margin-bottom: 3px;">
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
                                <h3 class="card-title mb-0">Investment List</h3>
                                <div class="card-options ms-auto">
                                    <a href="{{ route('investment.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add Investment
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
                        name: 'status'
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

    {{-- Update status --}}
    <script>
        $(document).on('click', '.changeStatus', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');

            $.ajax({
                url: "{{ route('investment.status.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        $('#datatable').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error("Failed to update status");
                    }
                },
                error: function(xhr) {
                    toastr.error("Something went wrong");
                }
            });
        });
    </script>

    {{-- Delete investment --}}
    <script>
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this investment?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function deleteItem(id) {
            NProgress.start();
            let url = "{{ route('investment.destroy', ':id') }}";
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    NProgress.done();
                    toastr.success(resp.message);
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function(error) {
                    NProgress.done();
                    toastr.error(error.responseJSON.message);
                }
            });
        }
    </script>
@endpush
