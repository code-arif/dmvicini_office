@extends('backend.app', ['title' => 'Education'])

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />

    <style>
        .badge.red-accent {
            background: #ffe6e6;
            color: #b30000;
            border: 1px solid #ffcccc;
        }
    </style>
@endpush

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Education</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Article</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>

                <!-- TABLE ROW -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Article List</h3>
                                <div class="card-options ms-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createEducationModal">
                                        <i class="fa fa-plus"></i> Add Article
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>SN.</th>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Sub Title</th>
                                                <th>Asset Class</th>
                                                <th>Created</th>
                                                <th>Status – Active</th>
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

    <!-- CREATE EDUCATION MODAL -->
    <div class="modal fade" id="createEducationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="createEducationForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required>
                                <span class="text-danger error-text title_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control">
                                <span class="text-danger error-text sub_title_error"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asset Class <span class="text-danger">*</span></label>
                            <select name="asset_class_id" class="form-control" required>
                                <option value="">-- Select Asset Class --</option>
                                @foreach ($asset_classes as $asset_class)
                                    <option value="{{ $asset_class->id }}">{{ $asset_class->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text asset_class_id_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="createDescription" rows="4" class="form-control"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" id="createImageInput" class="form-control"
                                accept="image/*">
                            <span class="text-danger error-text image_error"></span>
                            <!-- Create Preview -->
                            <div id="createImagePreview" class="mt-3" style="display: none;">
                                <img src="" alt="Preview" class="border"
                                    style="max-width: 200px; max-height: 200px;">
                            </div>
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

    <!-- EDIT EDUCATION MODAL -->
    <div class="modal fade" id="editEducationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editEducationForm" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" id="editID">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="editTitle" class="form-control" required>
                                <span class="text-danger error-text title_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" id="editSubTitle" class="form-control">
                                <span class="text-danger error-text sub_title_error"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Asset Class <span class="text-danger">*</span></label>
                            <select name="asset_class_id" id="editAssetClassId" class="form-control" required>
                                <option value="">-- Select Asset Class --</option>
                                @foreach ($asset_classes as $asset_class)
                                    <option value="{{ $asset_class->id }}">{{ $asset_class->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text asset_class_id_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" rows="4" class="form-control"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" id="editImageInput" class="form-control"
                                accept="image/*">
                            <span class="text-danger error-text image_error"></span>
                            <!-- Edit Preview -->
                            <div id="editImagePreview" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="" alt="Preview" class="border"
                                        style="max-width: 200px; max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger" id="removeEditImage">
                                        <i class="fa fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
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

            // Initialize Summernote for both modals with same height
            $('#createDescription, #editDescription').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                ]
            });

            // ===== IMAGE PREVIEW FUNCTIONALITY =====

            // Create Modal - Image Preview
            $('#createImageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#createImagePreview').show();
                        $('#createImagePreview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#createImagePreview').hide();
                }
            });

            // Edit Modal - Image Preview (New Upload)
            $('#editImageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#editImagePreview').show();
                        $('#editImagePreview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Remove Edit Image Preview
            $('#removeEditImage').on('click', function() {
                $('#editImageInput').val('');
                $('#editImagePreview').hide();
            });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            let dTable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('show.education.list') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'sub_title',
                        name: 'sub_title'
                    },
                    {
                        data: 'asset_class',
                        name: 'asset_class',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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
                    },
                ]
            });

            // CREATE
            $('#createEducationForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                NProgress.start();
                $.ajax({
                    url: "{{ route('education.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#createEducationModal').modal('hide');
                            $('#createEducationForm')[0].reset();
                            $('#createDescription').summernote('reset');
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

            // When edit button clicked
            $(document).on('click', '.editBtn', function() {
                let e = $(this);
                $('#editID').val(e.data('id'));
                $('#editTitle').val(e.data('title'));
                $('#editSubTitle').val(e.data('sub_title'));
                $('#editCategoryId').val(e.data('category_id'));

                let desc = e.data('description') || '';
                $('#editDescription').summernote('code', desc);

                // Show existing image if available
                if (e.data('image')) {
                    $('#editImagePreview').show();
                    $('#editImagePreview img').attr('src', '{{ asset('') }}/' + e.data('image'));
                } else {
                    $('#editImagePreview').hide();
                }

                // Clear file input
                $('#editImageInput').val('');

                $('#editEducationModal').modal('show');
            });

            // UPDATE
            $('#editEducationForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#editID').val();
                let formData = new FormData(this);

                NProgress.start();
                $.ajax({
                    url: "{{ route('education.update', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#editEducationModal').modal('hide');
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

            // Reset Summernote when modals are closed
            $('#createEducationModal, #editEducationModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.summernote').summernote('reset');

                // Hide image previews
                $('#createImagePreview').hide();
                $('#editImagePreview').hide();
            });
        });

        // Delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This education will be deleted permanently!',
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

        // Delete Item
        function deleteItem(id) {
            NProgress.start();
            let url = "{{ route('education.delete', ':id') }}";
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
            let url = "{{ route('education.status', ':id') }}";
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
    </script>
@endpush
