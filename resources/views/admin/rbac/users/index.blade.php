@extends('admin.layouts.app')

@section('title', 'User Assignments')
@section('page-title', 'Staff Role Assignments')
@section('page-description', 'Assign access roles and view computed permissions matrix per staff profile.')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white py-3">
        <h5 class="card-title h6 mb-0 fw-bold">Staff Profiles Access Grid</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Staff Name</th>
                    <th>Email Address</th>
                    <th>Assigned Roles</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td><code>{{ $user->email }}</code></td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge bg-light text-dark border">{{ $role->label }}</span>
                            @empty
                                <span class="badge bg-secondary-subtle text-secondary">No Assigned Roles</span>
                            @endforelse
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users-roles.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary" title="Assign Role Tiers">
                                <i class="fa-solid fa-user-shield me-1"></i> Edit Roles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No staff accounts registered.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
