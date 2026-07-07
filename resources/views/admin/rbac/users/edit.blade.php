@extends('admin.layouts.app')

@section('title', 'Assign User Roles')
@section('page-title', 'Configure Staff Roles')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.users-roles.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-2 fw-bold">Configure Access Levels</h5>
                <p class="text-muted small mb-4">Select the role tiers to assign to: <strong>{{ $user->name }}</strong> (<code>{{ $user->email }}</code>).</p>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 mb-3 small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    @foreach($roles as $role)
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 h-100">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="role_ids[]" value="{{ $role->id }}" id="role-{{ $role->id }}" {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold small text-dark d-block" for="role-{{ $role->id }}">
                                        {{ $role->label }}
                                    </label>
                                    <span class="text-muted extra-small d-block mt-1">{{ $role->description ?: 'No description' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.users-roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Update Assignments
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
