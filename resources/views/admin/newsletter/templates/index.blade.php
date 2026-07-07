@extends('admin.layouts.app')

@section('title', 'Newsletter Templates')
@section('page-title', 'Newsletter Templates')
@section('page-description', 'Manage design templates and layouts for campaign emails.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.newsletter.templates.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Create Template
        </a>
    </div>
</div>

<form id="bulkForm" method="POST" action="">
    @csrf
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title h6 mb-0">Template List</h5>
            <button type="button" id="applyBulkDelete" class="btn btn-sm btn-outline-danger">Delete Selected</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Name</th>
                        <th>Default Subject</th>
                        <th>Created At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tpl)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $tpl->id }}" class="form-check-input select-item">
                            </td>
                            <td><strong>{{ $tpl->name }}</strong></td>
                            <td>{{ $tpl->subject ?: '-' }}</td>
                            <td>{{ $tpl->created_at ? $tpl->created_at->format('M d, Y H:i') : '-' }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.newsletter.templates.edit', $tpl->id) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.newsletter.templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">No templates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($templates->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $templates->links() }}
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
                    alert('Please select at least one template.');
                    return;
                }

                if (confirm('Are you sure you want to delete the selected templates?')) {
                    bulkForm.action = "{{ route('admin.newsletter.templates.bulk-delete') }}";
                    bulkForm.submit();
                }
            });
        }
    });
</script>
@endsection
