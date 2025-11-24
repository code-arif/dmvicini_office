@extends('backend.app')
@section('title', 'Interested Users')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Interested Users</h1>
                        <small class="text-muted">Total: {{ \App\Models\InterestedUser::count() }} interests recorded</small>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investments</a></li>
                            <li class="breadcrumb-item active">Interested Users</li>
                        </ol>
                    </div>
                </div>
                <!-- END PAGE HEADER -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">All Users Who Expressed Interest</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Investor Name</th>
                                                <th>Email</th>
                                                <th>Deal Name</th>
                                                <th>Expressed At</th>
                                                <th width="80">Action</th>
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

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Investor Interest Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- AJAX Content -->
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
                ajax: "{{ route('deals.interst.user') }}",
                order: [
                    [4, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'investor',
                        name: 'investor'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'deal',
                        name: 'deal'
                    },
                    {
                        data: 'expressed_at',
                        name: 'expressed_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"></div>'
                }
            });

            // View Details Modal
            $(document).on('click', '.view-details', function() {
                const id = $(this).data('id');

                $.get("{{ route('interested-users.show-modal', ':id') }}".replace(':id', id), function(
                data) {
                    $('#modalBody').html(data);
                    $('#detailsModal').modal('show');
                }).fail(function() {
                    alert('Failed to load details. Please try again.');
                });
            });
        });
    </script>
@endpush
