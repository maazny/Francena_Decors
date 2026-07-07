@extends('admin.layouts.app')

@section('title', 'Permission Module Groups')
@section('page-title', 'Permission Groups')
@section('page-description', 'Manage categorization folders grouping permission action keys.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.permission-groups.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus-circle me-1"></i> Add Module Group
        </a>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-dark">
            <i class="fa-solid fa-key me-1"></i> View Permissions
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white py-3">
        <h5 class="card-title h6 mb-0 fw-bold">Configured Module Groups</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Group ID</th>
                    <th>Group Name</th>
                    <th>Permissions Count</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group)
                    <tr>
                        <td><code>#{{ $group->id }}</code></td>
                        <td><strong>{{ $group->name }}</strong></td>
                        <td>
                            <span class="badge bg-success-subtle text-success">{{ $group->permissions_count }} Key(s)</span>
                        </td>
                        <td><span class="text-muted small">{{ $group->description ?: 'No description' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.permission-groups.edit', $group->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.permission-groups.destroy', $group->id) }}" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No module groups defined.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($groups->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $groups->links() }}
        </div>
    @endif
</div>
@endsection

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this module group will cascade delete all permission keys defined under it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endonce
