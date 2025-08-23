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
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Sub Title</th>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Image</th>
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

                        <div class="mb-2">
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
        <div class="modal-dialog modal-lg">
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

                        <div class="mb-2">
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
                            <textarea name="description" id="editDescription" rows="4" class="description form-control"></textarea>
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
                        data: 'description',
                        name: 'description',
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

            // EDIT
            $(document).on('click', '.editBtn', function() {
                let e = $(this);
                $('#editID').val(e.data('id'));
                $('#editTitle').val(e.data('title'));
                $('#editSubTitle').val(e.data('sub_title'));
                $('#editCategoryId').val(e.data('category_id'));
                $('#editManualCategory').val('');

                // Description set in rich text editor
                $('#editDescription').summernote('code', e.data('description'));

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
    </script>
@endpush
