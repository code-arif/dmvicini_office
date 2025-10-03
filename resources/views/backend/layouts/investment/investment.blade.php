@extends('backend.app')
@section('title', 'Investments')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <!--app-content open-->
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
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
                <!-- PAGE-HEADER END -->

                <!-- ROW-4 -->
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Investment List</h3>
                                <div class="card-options ms-auto">
                                    <a href="{{ route('create.investment') }}" class="btn btn-primary btn-sm">Add
                                        Investment</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tabel-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="bg-transparent border-bottom-0">#</th>
                                                <th class="bg-transparent border-bottom-0">Title</th>
                                                <th class="bg-transparent border-bottom-0">Address</th>
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
                    </div><!-- COL END -->
                </div>
                <!-- ROW-4 END -->

            </div>
        </div>
    </div>
    <!-- CONTAINER CLOSED -->

    {{-- add/edit/heilight modal --}}
    @include('backend.layouts.investment.highlight')
    @include('backend.layouts.investment.document')
    @include('backend.layouts.investment.risk')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Summernote once for create and edit
            $('#createDescription, #editDescription').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                ]
            });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            let dTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // scrollX: true,
                ajax: {
                    url: "{{ route('get.investments') }}",
                    type: "GET",
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
                        data: 'location',
                        name: 'location'
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
        });
    </script>

    {{-- update status --}}
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
                        $('#datatable').DataTable().ajax.reload(null,
                        false);
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

    {{-- highlight modal --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.highlightBtn', function() {
                let investmentId = $(this).data('id');
                $('#investment_id').val(investmentId);
                let modal = new bootstrap.Modal(document.getElementById('investmentHighlightsModal'));
                modal.show();
            });
        });
    </script>

    {{-- inventment document modal --}}
    <script>
        $(document).on('click', '.docBtn', function() {
            let investmentId = $(this).data('id');

            // Target the input inside the modal only
            $('#investmentMediaModal').find('input[name="investment_id"]').val(investmentId);

            let modal = new bootstrap.Modal(document.getElementById('investmentMediaModal'));
            modal.show();
        });
    </script>

    {{-- inventment risk modal --}}
    <script>
        $(document).on('click', '.riskBtn', function() {
            let investmentId = $(this).data('id');

            // Target the input inside the modal only
            $('#investment_id').val(investmentId);

            let modal = new bootstrap.Modal(document.getElementById('riskModal'));
            modal.show();
        });
    </script>

    {{-- inventment delete --}}
    <script>
        // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this item?',
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

        // Delete Button
        function deleteItem(id) {
            NProgress.start();
            let url = "{{ route('destroy.investment', ':id') }}";
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
