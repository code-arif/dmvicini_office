@extends('backend.app')
@section('title', 'Investors')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
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
                ajax: "{{ route('investor.lsit') }}",
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reinitialize Bootstrap dropdowns after DataTable redraw
            table.on('draw', function() {
                document.querySelectorAll('.dropdown-toggle').forEach(el => {
                    new bootstrap.Dropdown(el);
                });
            });

            // Handle Accept/Decline clicks
            $(document).on('click', '.accept-user, .decline-user', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const action = $(this).data('action');
                const actionText = action === 'accept' ? 'accept this user' : 'decline this user';
                const confirmButton = action === 'accept' ? 'Yes, accept!' : 'Yes, decline!';

                Swal.fire({
                    title: `Are you sure you want to ${actionText}?`,
                    text: 'This action can be reverted later.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: action === 'accept' ? '#28a745' : '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmButton
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('investor.changeStatus') }}",
                            type: 'POST',
                            data: {
                                id,
                                action
                            },
                            success: function(res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    table.ajax.reload(null,
                                    false); // Reload without resetting pagination
                                } else {
                                    toastr.error('Failed to update status');
                                }
                            },
                            error: function() {
                                toastr.error('Server error! Try again later.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
