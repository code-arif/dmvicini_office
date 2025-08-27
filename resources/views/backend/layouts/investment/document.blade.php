<!-- Investment Media Modal -->
<div class="modal fade" id="investmentMediaModal" tabindex="-1" aria-labelledby="investmentMediaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="investmentMediaForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="investment_id" id="investment_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="investmentMediaModalLabel">Upload Investment Media and Documents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Documents -->
                    <div class="form-group mb-3">
                        <label for="investment_documents" class="form-label">Documents</label>
                        <input type="file" class="form-control" name="documents[]" id="investment_documents"
                            multiple>
                        <small class="text-muted">You can upload multiple documents (PDF, DOC, etc.)</small>
                        <div id="documentsList" class="mt-2"></div>
                    </div>

                    <!-- Images -->
                    <div class="form-group mb-3">
                        <label for="investment_images" class="form-label">Images</label>
                        <input type="file" class="form-control" name="images[]" id="investment_images" multiple
                            accept="image/*">
                        <small class="text-muted">You can upload multiple images (JPG, PNG, etc.)</small>
                        <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="mediaSubmitBtn" class="btn btn-primary">Upload Media</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Preview selected images
            $('#investment_images').on('change', function() {
                $('#imagePreview').html('');
                const files = this.files;
                if (files.length) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imagePreview').append(
                                `<img src="${e.target.result}" class="img-thumbnail" style="width:100px;height:100px;">`
                            );
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Preview selected documents
            $('#investment_documents').on('change', function() {
                $('#documentsList').html('');
                const files = this.files;
                if (files.length) {
                    Array.from(files).forEach(file => {
                        $('#documentsList').append(`<div>${file.name}</div>`);
                    });
                }
            });

            // Open modal with investment ID
            $(document).on('click', '.mediaBtn', function() {
                let investmentId = $(this).data('id');
                $('#investment_id').val(investmentId);
                var modal = new bootstrap.Modal(document.getElementById('investmentMediaModal'));
                modal.show();
            });

            // Form submit via AJAX
            $('#investmentMediaForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('investment.media.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            toastr.success('Media uploaded successfully!');
                            $('#investmentMediaModal').modal('hide');

                            // reload after success
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.message || 'Error uploading media');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong while uploading media.');
                    }
                });
            });
        });
    </script>
@endpush
