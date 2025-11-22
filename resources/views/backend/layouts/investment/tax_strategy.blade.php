@extends('backend.app')
@section('title', 'Tax Strategy')

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
    <style>
        .sortable-row {
            cursor: move;
            transition: background-color 0.2s;
        }

        .sortable-row:hover {
            background-color: #f8f9fa;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #e9ecef;
        }

        .sortable-chosen {
            background: #fff3cd;
        }

        .drag-handle {
            cursor: grab;
            color: #6c757d;
            font-size: 1.2rem;
            padding: 0 10px;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .order-badge {
            background: #1e53a4;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .alert-info-custom {
            background: #cfe2ff;
            border-left: 4px solid #1e53a4;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
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
                        <h1 class="page-title">Tax Strategy</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Tax Strategy</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Index</li>
                        </ol>
                    </div>
                </div>

                <!-- INFO ALERT -->
                <div class="alert-info-custom">
                    <i class="fa fa-info-circle me-2"></i>
                    <strong>Tip:</strong> Drag and drop rows using the <i class="fas fa-grip-vertical"></i> icon to reorder
                    tax strategies.
                </div>

                <!-- DATA TABLE -->
                <div class="row">
                    <div class="col-12">
                        <div class="card product-sales-main">
                            <div class="card-header border-bottom">
                                <h3 class="card-title mb-0">Tax Strategy List</h3>
                                <div class="card-options ms-auto">
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#createTaxStrategyModal">
                                        <i class="fa fa-plus"></i> Add Tax Strategy
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-nowrap mb-0 table-bordered" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="bg-transparent border-bottom-0" style="width: 50px;">Order</th>
                                                <th class="bg-transparent border-bottom-0" style="width: 60px;">Drag</th>
                                                <th class="bg-transparent border-bottom-0">Name</th>
                                                <th class="bg-transparent border-bottom-0">Description</th>
                                                <th class="bg-transparent border-bottom-0">Created</th>
                                                <th class="bg-transparent border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sortable-tbody">
                                            <!-- Data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createTaxStrategyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="createTaxForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Tax Strategy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control summernote" name="description" id="createDescription" rows="4"></textarea>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editTaxStrategyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editStrategyForm">
                    @csrf
                    <input type="hidden" name="id" id="editID">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tax Strategy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control summernote" name="description" id="editDescription" rows="4"></textarea>
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
    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
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

            // Load Data
            loadTaxStrategies();

            // Initialize Sortable after data loads
            function initSortable() {
                const tbody = document.getElementById('sortable-tbody');
                if (tbody) {
                    Sortable.create(tbody, {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onEnd: function(evt) {
                            updateOrder();
                        }
                    });
                }
            }

            // Load tax strategies
            function loadTaxStrategies() {
                NProgress.start();
                $.ajax({
                    url: "{{ route('tax.strategy.all') }}",
                    type: "GET",
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            renderTable(res.data);
                            initSortable();
                        }
                    },
                    error: function() {
                        NProgress.done();
                        toastr.error("Failed to load data");
                    }
                });
            }

            // Render table
            function renderTable(data) {
                let html = '';
                data.forEach((item, index) => {
                    // Strip HTML for display
                    let descText = item.description ? $('<div>').html(item.description).text() : '';
                    let truncatedDesc = descText.length > 50 ? descText.substring(0, 50) + '...' : descText;

                    html += `
                        <tr class="sortable-row" data-id="${item.id}">
                            <td class="text-center bg-light">
                                <span class="order-badge">${index + 1}</span>
                            </td>
                            <td class="text-center">
                                <i class="fas fa-grip-vertical drag-handle"></i>
                            </td>
                            <td><strong>${item.name}</strong></td>
                            <td>${truncatedDesc || '<span class="text-muted">No description</span>'}</td>
                            <td>${formatDate(item.created_at)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary editBtn"
                                    data-id="${item.id}"
                                    data-name="${item.name}"
                                    data-description="${escapeHtml(item.description || '')}">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger deleteBtn" onclick="showDeleteConfirm(${item.id})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#sortable-tbody').html(html);
            }

            // Escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Format date
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Update order after drag & drop
            function updateOrder() {
                const rows = document.querySelectorAll('#sortable-tbody tr');
                const orderData = [];

                rows.forEach((row, index) => {
                    orderData.push({
                        id: row.getAttribute('data-id'),
                        order: index + 1
                    });
                });

                NProgress.start();
                $.ajax({
                    url: "{{ route('tax.strategy.update.order') }}",
                    type: "POST",
                    data: {
                        order_data: orderData,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            toastr.success('Order updated successfully!');
                            loadTaxStrategies(); // Reload to show updated order badges
                        }
                    },
                    error: function() {
                        NProgress.done();
                        toastr.error("Failed to update order");
                    }
                });
            }

            // Create
            $('#createTaxForm').on('submit', function(e) {
                e.preventDefault();
                NProgress.start();
                $.ajax({
                    url: "{{ route('tax.strategy.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#createTaxStrategyModal').modal('hide');
                            $('#createTaxForm')[0].reset();
                            $('#createDescription').summernote('reset');
                            loadTaxStrategies();
                            toastr.success(res.message);
                        }
                    },
                    error: function() {
                        NProgress.done();
                        toastr.error("Failed to create");
                    }
                });
            });

            // Open edit modal
            $(document).on('click', '.editBtn', function() {
                $('#editID').val($(this).data('id'));
                $('#editName').val($(this).data('name'));
                $('#editDescription').summernote('code', $(this).data('description'));
                $('#editTaxStrategyModal').modal('show');
            });

            // Update
            $('#editStrategyForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#editID').val();
                NProgress.start();
                $.ajax({
                    url: "{{ route('tax.strategy.update', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        NProgress.done();
                        if (res.success) {
                            $('#editTaxStrategyModal').modal('hide');
                            loadTaxStrategies();
                            toastr.success(res.message);
                        }
                    },
                    error: function() {
                        NProgress.done();
                        toastr.error("Failed to update");
                    }
                });
            });

            // Reset Summernote when modal closes
            $('#createTaxStrategyModal, #editTaxStrategyModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.summernote').summernote('reset');
            });
        });

        // Delete
        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This tax strategy will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        function deleteItem(id) {
            NProgress.start();
            $.ajax({
                type: "DELETE",
                url: "{{ route('tax.strategy.delete', ':id') }}".replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    NProgress.done();
                    if (res.success) {
                        toastr.success(res.message);
                        loadTaxStrategies(); // Reload instead of location.reload()
                    }
                },
                error: function() {
                    NProgress.done();
                    toastr.error("Failed to delete");
                }
            });
        }
    </script>
@endpush
