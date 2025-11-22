@extends('backend.app', ['title' => 'FAQs'])

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">FAQs</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">FAQs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>

                <!-- DATA TABLE -->
                <div class="row">
                    <div class="col-12">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">FAQs List</h3>
                                <div class="card-options ms-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createFaqModal">
                                        <i class="fa fa-plus"></i> Add FAQ
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="bg-transparent border-bottom-0">SN</th>
                                                <th class="bg-transparent border-bottom-0">Question</th>
                                                <th class="bg-transparent border-bottom-0">Answer</th>
                                                <th class="bg-transparent border-bottom-0">Status</th>
                                                <th class="bg-transparent border-bottom-0">Action</th>
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

    <!-- CREATE FAQ MODAL -->
    <div class="modal fade" id="createFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="createFaqForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" class="form-control" placeholder="Enter your question"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" id="createAnswer" class="form-control summernote" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT FAQ MODAL -->
    <div class="modal fade" id="editFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editFaqForm">
                    @csrf
                    <input type="hidden" name="id" id="editID">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" id="editQuestion" class="form-control"
                                placeholder="Enter your question" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" id="editAnswer" class="form-control summernote" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            let dTable = $('#datatable').DataTable({
                order: [],
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.faq.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'question',
                        name: 'question'
                    },
                    {
                        data: 'answer',
                        name: 'answer'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
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
            $('#createFaqForm').on('submit', function(e) {
                e.preventDefault();
                NProgress.start();
                $.ajax({
                    url: "{{ route('admin.faq.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#createFaqModal').modal('hide');
                            $('#createFaqForm')[0].reset();
                            $('#createAnswer').summernote('reset');
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(xhr) {
                        NProgress.done();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                toastr.error(errors[key][0]);
                            });
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    }
                });
            });

            // EDIT BUTTON
            $(document).on('click', '.editBtn', function() {
                let e = $(this);
                $('#editID').val(e.data('id'));
                $('#editQuestion').val(e.data('question'));
                $('#editAnswer').summernote('code', e.data('answer'));
                $('#editFaqModal').modal('show');
            });

            // UPDATE
            $('#editFaqForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#editID').val();
                NProgress.start();
                $.ajax({
                    url: "{{ route('admin.faq.update', ':id') }}".replace(':id', id),
                    method: "PUT",
                    data: $(this).serialize(),
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#editFaqModal').modal('hide');
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(xhr) {
                        NProgress.done();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(key => {
                                toastr.error(errors[key][0]);
                            });
                        } else {
                            toastr.error('Something went wrong!');
                        }
                    }
                });
            });

            // Reset Summernote when modals close
            $('#createFaqModal, #editFaqModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.summernote').summernote('reset');
            });
        });

        // Status Change
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }

        function statusChange(id) {
            NProgress.start();
            let url = "{{ route('admin.faq.status', ':id') }}";
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                success: function(resp) {
                    NProgress.done();
                    toastr.success(resp.message);
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function(error) {
                    NProgress.done();
                    toastr.error('Failed to change status');
                }
            });
        }

        // Delete
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This FAQ will be deleted permanently!',
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
            let url = "{{ route('admin.faq.destroy', ':id') }}";
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
                    toastr.error('Failed to delete FAQ');
                }
            });
        }
    </script>
@endpush
