@extends('admin.layouts.app')

@section('title', 'Newsletter Campaigns')
@section('page-title', 'Newsletter Campaigns')
@section('page-description', 'Create, schedule, dispatch, and track newsletters and marketing email broadcasts.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> New Campaign
        </a>
    </div>
</div>

<form id="bulkForm" method="POST" action="">
    @csrf
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title h6 mb-0">Campaign List</h5>
            <button type="button" id="applyBulkDelete" class="btn btn-sm btn-outline-danger">Delete Selected</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Template</th>
                        <th>Status</th>
                        <th>Scheduled For</th>
                        <th>Sent At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $camp)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $camp->id }}" class="form-check-input select-item">
                            </td>
                            <td><strong>{{ $camp->title }}</strong></td>
                            <td>{{ $camp->subject }}</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($camp->campaign_type->value) }}</span></td>
                            <td>{{ $camp->template ? $camp->template->name : 'None (Custom HTML)' }}</td>
                            <td>
                                @php
                                    $statusClass = match($camp->status->value) {
                                        'draft' => 'bg-secondary-subtle text-secondary',
                                        'scheduled' => 'bg-info-subtle text-info',
                                        'sending' => 'bg-warning-subtle text-warning',
                                        'sent' => 'bg-success-subtle text-success',
                                        'cancelled' => 'bg-danger-subtle text-danger',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} border">
                                    {{ ucfirst($camp->status->value) }}
                                </span>
                            </td>
                            <td>{{ $camp->scheduled_at ? $camp->scheduled_at->format('M d, Y H:i') : '-' }}</td>
                            <td>{{ $camp->sent_at ? $camp->sent_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.newsletter.campaigns.preview', $camp->id) }}" target="_blank" class="btn btn-outline-secondary" title="Preview HTML">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.newsletter.campaigns.logs', $camp->id) }}" class="btn btn-outline-secondary" title="View Delivery Logs">
                                        <i class="fa-solid fa-chart-line"></i>
                                    </a>
                                    @if($camp->status->value !== 'sent' && $camp->status->value !== 'sending')
                                        <a href="{{ route('admin.newsletter.campaigns.edit', $camp->id) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.newsletter.campaigns.destroy', $camp->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No campaigns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectAll = document.getElementById('selectAll');
        const selectItems = document.querySelectorAll('.select-item');
        const applyBulkDelete = document.getElementById('applyBulkDelete');
        const bulkForm = document.getElementById('bulkForm');

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                selectItems.forEach(item => item.checked = selectAll.checked);
            });
        }

        if (applyBulkDelete) {
            applyBulkDelete.addEventListener('click', () => {
                const checkedCount = document.querySelectorAll('.select-item:checked').length;

                if (checkedCount === 0) {
                    alert('Please select at least one campaign.');
                    return;
                }

                if (confirm('Are you sure you want to delete the selected campaigns?')) {
                    bulkForm.action = "{{ route('admin.newsletter.campaigns.bulk-delete') }}";
                    bulkForm.submit();
                }
            });
        }
    });
</script>
@endsection
