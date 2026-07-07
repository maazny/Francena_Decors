@extends('admin.layouts.app')

@section('title', 'Newsletter Groups')
@section('page-title', 'Newsletter Groups')
@section('page-description', 'Manage list segments, target cohorts, and demographic subscription groups.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.newsletter.groups.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Create Group
        </a>
    </div>
</div>

<form id="bulkForm" method="POST" action="">
    @csrf
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title h6 mb-0">Group List</h5>
            <button type="button" id="applyBulkDelete" class="btn btn-sm btn-outline-danger">Delete Selected</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Order</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-center">Subscribers</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groups as $group)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $group->id }}" class="form-check-input select-item">
                            </td>
                            <td>{{ $group->display_order }}</td>
                            <td><strong>{{ $group->name }}</strong></td>
                            <td><code>{{ $group->slug }}</code></td>
                            <td>{{ Str::limit($group->description ?: '-', 50) }}</td>
                            <td>
                                @if($group->is_dynamic)
                                    <span class="badge bg-primary">Dynamic</span>
                                @else
                                    <span class="badge bg-secondary">Static</span>
                                @endif
                            </td>
                            <td>
                                @if($group->status)
                                    <span class="badge bg-success-subtle text-success border">Active</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark text-white rounded-pill px-3">{{ $group->subscribers()->count() }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.newsletter.groups.edit', $group->id) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.newsletter.groups.toggle-status', $group->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary" title="Toggle Status">
                                            <i class="fa-solid fa-rotate"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.newsletter.groups.destroy', $group->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this group? It will untag all associated subscribers.')">
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
                            <td colspan="9" class="text-center py-4 text-muted">No groups found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($groups->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $groups->links() }}
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
                    alert('Please select at least one group.');
                    return;
                }

                if (confirm('Are you sure you want to delete the selected groups? All subscribers associated with these groups will be detached.')) {
                    bulkForm.action = "{{ route('admin.newsletter.groups.bulk-delete') }}";
                    bulkForm.submit();
                }
            });
        }
    });
</script>
@endsection
