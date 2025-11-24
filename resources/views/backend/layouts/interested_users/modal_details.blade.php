<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Investor Information</h6>
        <table class="table table-borderless table-sm">
            <tr><td><strong>Name:</strong></td><td>{{ $interest->user?->profile?->first_name }} {{ $interest->user?->profile?->last_name }}</td></tr>
            <tr><td><strong>Email:</strong></td><td><a href="mailto:{{ $interest->email }}">{{ $interest->email }}</a></td></tr>
            <tr><td><strong>Firm:</strong></td><td>{{ $interest->user?->profile?->firm_name ?? 'N/A' }}</td></tr>
            <tr><td><strong>Phone:</strong></td><td>{{ $interest->user?->profile?->phone ?? 'N/A' }}</td></tr>
            <tr><td><strong>Country:</strong></td><td>{{ $interest->user?->profile?->country ?? 'N/A' }}</td></tr>
            <tr><td><strong>Investor Type:</strong></td>
                <td>
                    {{ ucfirst(str_replace('_', ' ', $interest->user?->profile?->investor_type ?? '')) }}
                    @if($interest->user?->profile?->investor_type === 'other')
                        → {{ $interest->user?->profile?->investor_type_other }}
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Deal Information</h6>
        <table class="table table-borderless table-sm">
            <tr><td><strong>Deal Title:</strong></td><td><strong>{{ $interest->investment?->title ?? 'Deal Removed' }}</strong></td></tr>
            <tr><td><strong>Interest Recorded:</strong></td><td>{{ $interest->created_at->format('d M, Y h:i A') }}</td></tr>
            @if($interest->investment)
                <tr><td><strong>Deal ID:</strong></td><td>#{{ $interest->investment->id }}</td></tr>
            @endif
        </table>
    </div>
</div>

<div class="mt-4 text-center">
    @if($interest->user)
        <a href="{{ route('admin.users.show', $interest->user->id) }}" class="btn btn-primary">
            View Full Investor Profile
        </a>
    @endif
    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Close</button>
</div>
