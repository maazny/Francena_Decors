@extends('admin.layouts.app')

@section('title', 'Access Roles')
@section('page-title', 'Role Management')
@section('page-description', 'Manage user authorization access levels and permissions templates mappings.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus-circle me-1"></i> Add Role Tier
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white py-3">
        <h5 class="card-title h6 mb-0 fw-bold">Configured Role Tiers</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Role Name</th>
                    <th>Display Label</th>
                    <th>Type</th>
                    <th>Users Count</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td><code>{{ $role->name }}</code></td>
                        <td><strong>{{ $role->label }}</strong></td>
                        <td>
                            @if($role->is_system)
                                <span class="badge bg-danger-subtle text-danger"><i class="fa-solid fa-lock me-1"></i> System Lock</span>
                            @else
                                <span class="badge bg-light text-dark border">Custom Role</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info">{{ $role->users_count }} User(s)</span>
                        </td>
                        <td><span class="text-muted small">{{ $role->description ?: 'No description provided.' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.roles.permissions.edit', $role->id) }}" class="btn btn-sm btn-outline-info" title="Manage Permissions">
                                    <i class="fa-solid fa-sliders"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Role">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                @if(!$role->is_system)
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Role">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="System roles cannot be deleted"><i class="fa-solid fa-ban"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No roles configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($roles->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $roles->links() }}
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
                text: "Deleting this role will remove it from all assigned user profiles, reverting their access permissions.",
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
