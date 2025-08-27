<!-- RISK MODAL -->
<div class="modal fade" id="riskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="riskForm" method="post">
                @csrf
                @method('POST')
                <input type="hidden" name="investment_id" id="investment_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title">Investment Risk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="riskTitle" class="form-control">
                            <span class="text-danger error-text title_error"></span>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Risk Level</label>
                            <select name="risk_level" id="riskLevel" class="form-control">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="riskDescription" rows="4"></textarea>
                        <span class="text-danger error-text description_error"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="clsBtn">Close</button>
                    <button type="submit" class="btn btn-primary">Save Risk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    let $modal = $('#riskModal');
    let $form = $('#riskForm');

    // init Summernote
    $('#riskDescription').summernote({
        height: 150,
        placeholder: 'Enter risk description...'
    });

    // -------------------------------
    // Load Risk Data
    // -------------------------------
    function loadRisk() {
        let investmentId = $('#investment_id').val();
        if (!investmentId) return;

        let url = "{{ route('get.risk', ['investment_id' => ':id']) }}";
        url = url.replace(':id', investmentId);

        $.get(url, (res) => {
            if (res.success && res.data) {
                const r = res.data;
                $('#riskTitle').val(r.title || '');
                $('#riskLevel').val(r.risk_level || 'medium');
                $('#riskDescription').summernote('code', r.description || '');
            } else {
                $('#riskTitle').val('');
                $('#riskLevel').val('medium');
                $('#riskDescription').summernote('code', '');
            }
        }).fail(() => {
            $('#riskTitle').val('');
            $('#riskLevel').val('medium');
            $('#riskDescription').summernote('code', '');
        });
    }

    $modal.on('show.bs.modal', loadRisk);

    // -------------------------------
    // Submit Risk Form
    // -------------------------------
    $form.on('submit', function(e) {
        e.preventDefault();

        let formData = {
            _token: $('input[name="_token"]').val(),
            investment_id: $('#investment_id').val(),
            title: $('#riskTitle').val(),
            risk_level: $('#riskLevel').val(),
            description: $('#riskDescription').summernote('code')
        };

        let url = "{{ route('investment.risk.store') }}";

        $.post(url, formData)
            .done((res) => {
                if (res.success) {
                    toastr.success('Risk saved successfully!');
                    $modal.modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    toastr.error(res.message || 'Error saving risk');
                }
            })
            .fail((xhr) => {
                let errors = xhr.responseJSON?.errors;
                if (errors) $.each(errors, (_, msg) => toastr.error(msg[0]));
                else toastr.error('Something went wrong');
            });
    });

    // Close button reload
    $('#clsBtn').click(() => {
        setTimeout(() => window.location.reload(), 1000);
    });
});
</script>
@endpush
