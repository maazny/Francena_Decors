@extends('admin.layouts.app')

@section('title', 'System Permissions')
@section('page-title', 'Permissions Manager')
@section('page-description', 'Define individual action keys mapped to specific modules.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus-circle me-1"></i> Add Permission Key
        </a>
        <a href="{{ route('admin.permission-groups.index') }}" class="btn btn-outline-dark">
            <i class="fa-solid fa-folder me-1"></i> View Groups
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
        <h5 class="card-title h6 mb-0 fw-bold">Active Permission Keys</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Permission Key</th>
                    <th>Display Name</th>
                    <th>Group / Module</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $perm)
                    <tr>
                        <td><code>{{ $perm->name }}</code></td>
                        <td><strong>{{ $perm->label }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $perm->group ? $perm->group->name : 'None' }}</span>
                        </td>
                        <td><span class="text-muted small">{{ $perm->description ?: 'No description' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.permissions.edit', $perm->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.permissions.destroy', $perm->id) }}" class="d-inline delete-form">
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
                        <td colspan="5" class="text-center py-4 text-muted">No permissions defined.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($permissions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $permissions->links() }}
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
                text: "Deleting this permission key removes it from any assigned roles, disabling corresponding gate checks.",
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
