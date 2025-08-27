<!-- Investment Highlights Modal -->
<div class="modal fade" id="investmentHighlightsModal" tabindex="-1" aria-labelledby="investmentHighlightsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="investmentHighlightsForm" method="post">
                @csrf
                <input type="hidden" name="investment_id" id="investment_id" value="{{ $investment->id ?? '' }}">
                <input type="hidden" name="investment_id" id="investment_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="investmentHighlightsModalLabel">Investment Highlights</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Overview -->
                    <div class="form-group mb-3">
                        <label for="overview" class="form-label">Overview</label>
                        <textarea class="form-control" name="overview" id="overview" rows="6" placeholder="Enter investment overview"></textarea>
                        <span class="text-danger error-text overview_error"></span>
                    </div>

                    <!-- Targeted Returns -->
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label">Targeted Returns</label>
                            <button type="button" class="btn btn-sm btn-primary" id="addTargetReturn">
                                <i class="fa fa-plus"></i> Add Return
                            </button>
                        </div>

                        <div id="targetedReturnsContainer">
                            <!-- Dynamic fields will be added here -->
                        </div>
                    </div>

                    <!-- Fees -->
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label">Fees</label>
                            <button type="button" class="btn btn-sm btn-primary" id="addFee">
                                <i class="fa fa-plus"></i> Add Fee
                            </button>
                        </div>

                        <div id="feesContainer">
                            <!-- Dynamic fields will be added here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        id="closeBtn">Close</button>
                    <button type="submit" id="highlightSubmitBtn" class="btn btn-primary">Save Highlights</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for targeted return fields (hidden) -->
<div id="targetReturnTemplate" class="d-none">
    <div class="target-return-item mb-2">
        <div class="input-group">
            <input type="text" class="form-control" name="targeted_returns_key[]"
                placeholder="Return Type (e.g., IRR)">
            <input type="text" class="form-control" name="targeted_returns_value[]" placeholder="Value (e.g., 15%)">
            <button type="button" class="btn btn-danger remove-item">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>

<!-- Template for fee fields (hidden) -->
<div id="feeTemplate" class="d-none">
    <div class="fee-item mb-2">
        <div class="input-group">
            <input type="text" class="form-control" name="fees_key[]" placeholder="Fee Type (e.g., Management Fee)">
            <input type="text" class="form-control" name="fees_value[]" placeholder="Value (e.g., $1000)">
            <button type="button" class="btn btn-danger remove-item">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {

            // -------------------------------
            // Helpers
            // -------------------------------
            const $modal = $('#investmentHighlightsModal');
            const $form = $('#investmentHighlightsForm');
            const $returns = $('#targetedReturnsContainer');
            const $fees = $('#feesContainer');

            function renderItems(container, data, keyName, valueName, templateId) {
                container.empty();
                if (!data) return;
                $.each(data, (key, value) => {
                    container.append(`
                <div class="item mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="${keyName}[]" value="${key}" placeholder="Key">
                        <input type="text" class="form-control" name="${valueName}[]" value="${value}" placeholder="Value">
                        <button type="button" class="btn btn-danger remove-item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `);
                });
            }

            function collectItems(keys, values) {
                let obj = {};
                keys.forEach((k, i) => {
                    if (k && values[i]) obj[k] = values[i];
                });
                return obj;
            }

            // -------------------------------
            // Add/remove dynamic items
            // -------------------------------
            $('#addTargetReturn').click(() => $returns.append($('#targetReturnTemplate').html()));
            $('#addFee').click(() => $fees.append($('#feeTemplate').html()));
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item, .target-return-item, .fee-item').remove();
            });

            // -------------------------------
            // Load highlights
            // -------------------------------
            function loadHighlights() {
                let investmentId = $('#investment_id').val();
                if (!investmentId) return;

                // route name use kore URL generate
                let url = "{{ route('get.highlight', ['investment_id' => ':id']) }}";
                url = url.replace(':id', investmentId);

                $.get(url, (res) => {
                    if (res.success && res.data) {
                        const h = res.data;
                        $('#overview').val(h.overview || '');
                        $('#highlight_id').val(h.id || '');

                        renderItems($returns, h.targeted_returns ? JSON.parse(h.targeted_returns) : {},
                            'targeted_returns_key', 'targeted_returns_value');
                        renderItems($fees, h.fees ? JSON.parse(h.fees) : {},
                            'fees_key', 'fees_value');
                    } else {
                        $returns.empty();
                        $fees.empty();
                    }
                }).fail(() => {
                    $returns.empty();
                    $fees.empty();
                });
            }

            $modal.on('show.bs.modal', loadHighlights);

            // -------------------------------
            // Submit form
            // -------------------------------
            $form.on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let investmentId = $('#investment_id').val();
                let highlightId = $('#highlight_id').val();

                // Route name use kore URL
                let url = "{{ route('investment.highlights.store') }}";

                let data = {
                    _token: formData.get('_token'),
                    investment_id: investmentId,
                    overview: formData.get('overview'),
                    targeted_returns: collectItems(
                        formData.getAll('targeted_returns_key[]'),
                        formData.getAll('targeted_returns_value[]')
                    ),
                    fees: collectItems(
                        formData.getAll('fees_key[]'),
                        formData.getAll('fees_value[]')
                    )
                };

                $.post(url, data)
                    .done((res) => {
                        if (res.success) {
                            toastr.success('Highlights saved successfully!');
                            $modal.modal('hide');

                            // reload after success
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.message || 'Error saving highlights');
                        }
                    })
                    .fail((xhr) => {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) $.each(errors, (_, msg) => toastr.error(msg[0]));
                        else toastr.error('Something went wrong');
                    });
            });


            //close button click
            $('#closeBtn').click(() => {
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            })
        });
    </script>
@endpush
