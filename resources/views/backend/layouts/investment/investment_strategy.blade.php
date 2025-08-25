@extends('backend.app')
@section('title', 'Investment Strategy')

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
                        <h1 class="page-title">Investment Strategy</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investment Strategy</a></li>
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
                                <h3 class="card-title mb-0">Investment Strategy List</h3>
                                <div class="card-options ms-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createStrategyModal">Add Strategy</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="tabel-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="bg-transparent border-bottom-0">#</th>
                                                <th class="bg-transparent border-bottom-0">Name</th>
                                                <th class="bg-transparent border-bottom-0">Description</th>
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

    <!-- Create Strategy Modal -->
    <div class="modal fade" id="createStrategyModal" tabindex="-1" aria-labelledby="createStrategyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="createStrategyForm" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createStrategyModalLabel">Create Strategy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="createName" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="createName"
                                placeholder="Enter Class Name">
                            <span class="text-danger error-text name_error"></span>
                        </div>

                        <div class="form-group mb-2">
                            <label for="createDescription" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="createDescription" rows="3"
                                placeholder="Enter description"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="createSubmitBtn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editStrategyModal" tabindex="-1" aria-labelledby="editStrategyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editStrategyForm" method="post">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" id="editID">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStrategyModalLabel">Edit Strategy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="editName">
                            <span class="text-danger error-text name_error"></span>
                        </div>

                        <div class="form-group mb-2">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="editSubmitBtn" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                order: [],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('show.investment.strategy.list') }}",
                    type: "GET",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
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

            // CREATE
            $('#createStrategyForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('investment.strategy.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#createSubmitBtn').prop('disabled', true).text('Saving...');
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#createStrategyModal').modal('hide');
                            // reset form + summernote
                            $('#createStrategyForm')[0].reset();
                            $('#createDescription').summernote('reset');
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                        $('#createSubmitBtn').prop('disabled', false).text('Save');
                    },
                    error: function() {
                        toastr.error("Something went wrong!");
                        $('#createSubmitBtn').prop('disabled', false).text('Save');
                    }
                });
            });

            // OPEN EDIT MODAL
            $(document).on('click', '.editBtn', function() {
                let e = $(this);
                $('#editID').val(e.data('id'));
                $('#editName').val(e.data('name'));
                $('#editDescription').summernote('code', e.data('description'));
                $('#editStrategyModal').modal('show');
            });

            // UPDATE
            $('#editStrategyForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#editID').val();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('investment.strategy.update', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#editSubmitBtn').prop('disabled', true).text('Updating...');
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#editStrategyModal').modal('hide');
                            // reset form + summernote
                            $('#editStrategyForm')[0].reset();
                            $('#editDescription').summernote('reset');
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                        $('#editSubmitBtn').prop('disabled', false).text('Update');
                    },
                    error: function() {
                        toastr.error("Something went wrong!");
                        $('#editSubmitBtn').prop('disabled', false).text('Update');
                    }
                });
            });

            // When modal hide -> reset form automatically
            $('#createStrategyModal, #editStrategyModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.summernote').summernote('reset');
            });
        });


        // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this strategy?',
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
            let url = "{{ route('investment.strategy.delete', ':id') }}";
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
