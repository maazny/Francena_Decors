@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter Subscribers')
@section('page-description', 'Manage subscriber emails, segment assignments, and verification status states.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.newsletter.subscribers.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Subscriber
        </a>
    </div>
</div>

<!-- Filters Panel -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-4">
    <h5 class="h6 mb-3"><i class="fa-solid fa-filter me-1"></i> Filters</h5>
    <form method="GET" action="{{ route('admin.newsletter.subscribers.index') }}" class="row g-3">
      <!-- Search Input -->
      <div class="col-md-4">
        <label class="form-label small">Keyword Search</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, phone..." value="{{ request('search') }}">
      </div>
      <!-- Group -->
      <div class="col-md-3">
        <label class="form-label small">Group / Segment</label>
        <select name="group_id" class="form-select form-select-sm">
          <option value="">All Groups</option>
          @foreach($groups as $grp)
            <option value="{{ $grp->id }}" {{ request('group_id') == $grp->id ? 'selected' : '' }}>
              {{ $grp->name }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Status -->
      <div class="col-md-3">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select form-select-sm">
          <option value="">All Statuses</option>
          @foreach(\App\Enums\SubscriptionStatus::cases() as $statusOption)
            <option value="{{ $statusOption->value }}" {{ request('status') == $statusOption->value ? 'selected' : '' }}>
              {{ ucfirst($statusOption->value) }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Actions -->
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-dark btn-sm w-100">Filter</button>
        <a href="{{ route('admin.newsletter.subscribers.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
      </div>
    </form>
  </div>
</div>

<!-- Bulk Action & Table -->
<form id="bulkForm" method="POST" action="">
    @csrf
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title h6 mb-0">Subscriber List</h5>
            <div class="d-flex align-items-center gap-2">
                <select id="bulk_action_select" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Bulk Actions</option>
                    <option value="delete">Delete Selected</option>
                    <option value="active">Mark Active</option>
                    <option value="unsubscribed">Mark Unsubscribed</option>
                    <option value="blacklisted">Mark Blacklisted</option>
                </select>
                <button type="button" id="applyBulkAction" class="btn btn-sm btn-outline-dark">Apply</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Groups</th>
                        <th>Source</th>
                        <th>Subscribed At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscribers as $sub)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $sub->id }}" class="form-check-input select-item">
                            </td>
                            <td>{{ $sub->name ?: '-' }}</td>
                            <td><strong>{{ $sub->email }}</strong></td>
                            <td>{{ $sub->phone ?: '-' }}</td>
                            <td>
                                @php
                                    $statusClass = match($sub->status->value) {
                                        'active' => 'bg-success-subtle text-success',
                                        'pending' => 'bg-warning-subtle text-warning',
                                        'unsubscribed' => 'bg-secondary-subtle text-secondary',
                                        'blacklisted' => 'bg-danger-subtle text-danger',
                                        default => 'bg-info-subtle text-info'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} border px-2.5 py-1.5">
                                    {{ ucfirst($sub->status->value) }}
                                </span>
                            </td>
                            <td>
                                @if($sub->verification_status)
                                    <span class="text-success"><i class="fa-solid fa-circle-check"></i> Yes</span>
                                @else
                                    <span class="text-muted"><i class="fa-solid fa-circle-minus"></i> Pending</span>
                                @endif
                            </td>
                            <td>
                                @foreach($sub->groups as $group)
                                    <span class="badge bg-dark text-white me-1">{{ $group->name }}</span>
                                @endforeach
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($sub->source->value) }}</span></td>
                            <td>{{ $sub->subscribed_at ? $sub->subscribed_at->format('M d, Y H:i') : ($sub->created_at ? $sub->created_at->format('M d, Y H:i') : '-') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.newsletter.subscribers.edit', $sub->id) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.newsletter.subscribers.toggle-status', $sub->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary" title="Toggle Status">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.newsletter.subscribers.destroy', $sub->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscriber?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">No subscribers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscribers->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $subscribers->links() }}
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
        const bulkActionSelect = document.getElementById('bulk_action_select');
        const applyBulkAction = document.getElementById('applyBulkAction');
        const bulkForm = document.getElementById('bulkForm');

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                selectItems.forEach(item => item.checked = selectAll.checked);
            });
        }

        if (applyBulkAction) {
            applyBulkAction.addEventListener('click', () => {
                const action = bulkActionSelect.value;
                const checkedCount = document.querySelectorAll('.select-item:checked').length;

                if (checkedCount === 0) {
                    alert('Please select at least one subscriber.');
                    return;
                }

                if (!action) {
                    alert('Please select a bulk action.');
                    return;
                }

                if (action === 'delete') {
                    if (!confirm('Are you sure you want to delete the selected subscribers?')) {
                        return;
                    }
                    bulkForm.action = "{{ route('admin.newsletter.subscribers.bulk-delete') }}";
                } else {
                    bulkForm.action = "{{ route('admin.newsletter.subscribers.bulk-status') }}?status=" + action;
                }

                bulkForm.submit();
            });
        }
    });
</script>
@endsection
