@extends('backend.app', ['title' => 'Create FAQ'])

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .note-editor.note-frame {
            border: 1px solid #dee2e6;
        }

        .note-editable {
            min-height: 300px;
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
                        <h1 class="page-title">Create FAQ</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.faq.index') }}">FAQs</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </div>
                </div>

                <!-- CREATE FORM -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Create New FAQ</h3>
                                <div class="card-options ms-auto">
                                    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back to List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="createFaqForm" action="{{ route('admin.faq.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label">Question <span class="text-danger">*</span></label>
                                        <input type="text" name="question" class="form-control"
                                            placeholder="Enter your question" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Answer <span class="text-danger">*</span></label>
                                        <textarea name="answer" id="answer" class="form-control summernote"></textarea>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save FAQ
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Image Upload Function
            function uploadImage(file) {
                let data = new FormData();
                data.append("file", file);
                data.append("_token", "{{ csrf_token() }}");

                $.ajax({
                    url: "{{ route('admin.faq.upload-image') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "POST",
                    success: function(response) {
                        if (response.success) {
                            $('#answer').summernote('insertImage', response.url);
                        } else {
                            toastr.error('Image upload failed');
                        }
                    },
                    error: function() {
                        // Fallback to base64 if upload fails
                        let reader = new FileReader();
                        reader.onloadend = function() {
                            $('#answer').summernote('insertImage', reader.result);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Form Submit
            $('#createFaqForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let answer = $('#answer').summernote('code');
                formData.set('answer', answer);


                // Check if answer is empty
                if ($('#answer').summernote('isEmpty')) {
                    toastr.error('Please enter an answer');
                    return false;
                }


                NProgress.start();

                $.ajax({
                    url: $(this).attr('action'),
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(function() {
                                window.location.href = "{{ route('admin.faq.index') }}";
                            }, 1000);
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
        });
    </script>
@endpush
