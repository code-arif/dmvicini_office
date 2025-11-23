@extends('backend.app')
@section('title', 'Import Deals')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Import Deals</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Investment</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Upload Deals - CSV/Excel File</h3>
                                <div class="ms-auto">
                                    <a href="{{ route('investments.download-template') }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-download"></i> Download Template
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Instructions -->
                                <div class="alert alert-info">
                                    <h5>Import Instructions:</h5>
                                    <ul>
                                        <li>Download the template file using the button above</li>
                                        <li>Fill in your investment data following the template format</li>
                                        <li>Supported formats: CSV, XLS, XLSX (Max: 500MB)</li>
                                        <li>Make sure column headers match exactly with the template</li>
                                        <li>Empty rows will be skipped automatically</li>
                                    </ul>
                                </div>

                                <!-- Upload Form -->
                                <form id="importForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Select File</label>
                                        <input type="file" name="file" id="fileInput" class="form-control"
                                            accept=".csv,.xlsx,.xls" required>
                                        <small class="text-muted">Allowed: CSV, XLSX, XLS (Max 500MB)</small>
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" id="importBtn">
                                            <i class="fa fa-upload"></i> Import Data
                                        </button>
                                        <a href="{{ route('investment.list') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </form>

                                <!-- Progress Bar -->
                                <div id="progressBar" class="progress mb-3" style="display: none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                        style="width: 100%">
                                        Processing...
                                    </div>
                                </div>

                                <!-- Results -->
                                <div id="resultsBox" style="display: none;">
                                    <div class="alert alert-success" id="successBox">
                                        <h5>Import Successful!</h5>
                                        <p id="successMessage"></p>
                                    </div>

                                    <div class="alert alert-warning" id="errorBox" style="display: none;">
                                        <h5>Some Errors Occurred:</h5>
                                        <div id="errorList"></div>
                                    </div>
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
            $('#importForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const fileInput = $('#fileInput')[0];

                if (!fileInput.files.length) {
                    toastr.error('Please select a file');
                    return;
                }

                // Show progress
                $('#progressBar').show();
                $('#resultsBox').hide();
                $('#importBtn').prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Importing...');

                $.ajax({
                    url: "{{ route('investments.import') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#progressBar').hide();
                        $('#resultsBox').show();
                        $('#importBtn').prop('disabled', false).html(
                            '<i class="fa fa-upload"></i> Import Data');

                        if (response.success) {
                            $('#successMessage').text(response.message);
                            toastr.success(response.message);

                            // Show errors if any
                            if (response.details.errors && response.details.errors.length > 0) {
                                $('#errorBox').show();
                                let errorHtml = '<ul>';
                                response.details.errors.forEach(function(error) {
                                    errorHtml +=
                                        `<li>Row ${error.row}: ${error.error}</li>`;
                                });
                                errorHtml += '</ul>';
                                $('#errorList').html(errorHtml);
                            }

                            // Reset form
                            $('#importForm')[0].reset();

                            // Redirect after 3 seconds if fully successful
                            if (response.details.failed === 0) {
                                setTimeout(function() {
                                    window.location.href =
                                        "{{ route('investment.list') }}";
                                }, 3000);
                            }
                        }
                    },
                    error: function(xhr) {
                        $('#progressBar').hide();
                        $('#importBtn').prop('disabled', false).html(
                            '<i class="fa fa-upload"></i> Import Data');

                        let errorMsg = 'Import failed!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        toastr.error(errorMsg);
                    }
                });
            });
        });
    </script>
@endpush
