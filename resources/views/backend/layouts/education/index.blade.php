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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Education</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>

                <!-- TABLE ROW -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Education List</h3>
                                <div class="card-options ms-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createEducationModal">
                                        Add Education
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
                                                <th>Category</th>
                                                <th>Created</th>
                                                <th>Vabluable</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="createEducationForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Education</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control">
                                <span class="text-danger error-text title_error"></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control">
                                <span class="text-danger error-text sub_title_error"></span>
                            </div>
                        </div>

                        <div class="mb-2 p-2 rounded-2 bg-light">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-muted">Or type new category below</span>
                            <input type="text" name="category_name" class="form-control mt-1" placeholder="New Category">
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control description"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <span class="text-danger error-text image_error"></span>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="editTitle" class="form-control">
                                <span class="text-danger error-text title_error"></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" id="editSubTitle" class="form-control">
                                <span class="text-danger error-text sub_title_error"></span>
                            </div>
                        </div>

                        <div class="mb-2 p-3 rounded-2 bg-light">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="editCategoryId" class="form-control">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-muted">Or type new category below</span>
                            <input type="text" name="category_name" id="editManualCategory" class="form-control mt-1"
                                placeholder="New Category">
                        </div>


                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" rows="4" style="display: none"></textarea>
                            <span class="text-danger error-text description_error"></span>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" id="editImage" class="form-control" accept="image/*">
                            <span class="text-danger error-text image_error"></span>
                            <div id="currentImage" class="mt-2"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // Initialize Summernote once
            $('#editDescription').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                ]
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'sub_title',
                        name: 'sub_title'
                    },
                    {
                        data: 'category',
                        name: 'category',
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
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                rawColumns: ['description', 'image', 'action']
            });

            // CREATE
            $('#createEducationForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('education.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            $('#createEducationModal').modal('hide');
                            $('#createEducationForm')[0].reset();
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
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
                $('#editManualCategory').val('');

                let desc = e.data('description');

                // Destroy old Summernote content before setting
                $('#editDescription').summernote('reset');
                $('#editDescription').summernote('code', desc); // set old description

                if (e.data('image')) {
                    $('#currentImage').html('<img src="{{ asset('') }}/' + e.data('image') +
                        '" width="80">');
                } else {
                    $('#currentImage').html('');
                }

                $('#editEducationModal').modal('show');
            });


            // UPDATE
            $('#editEducationForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#editID').val();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('education.update', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            $('#editEducationModal').modal('hide');
                            dTable.ajax.reload();
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    }
                });
            });
        });

        // delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this education?',
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


        // toggle pinned confirm alert
        function togglePin(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to pinned the education?',
                text: 'If you pinned this, It will show on the education screen!',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Pin It!',
            }).then((result) => {
                if (result.isConfirmed) {
                    pinnedItem(id);
                }
            });
        }

        // Delete Button
        function pinnedItem(id) {
            // alert('file deleted');
            NProgress.start();
            let url = "{{ route('pinned.education', ':id') }}";
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "POST",
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
