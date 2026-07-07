@extends('admin.layouts.app')

@section('title', 'Manage Permissions')
@section('page-title', 'Manage Role Permissions')
@section('page-description', 'Bind granular permission gates to authorization role levels.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form method="POST" action="{{ route('admin.roles.permissions.update', $role->id) }}">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">Role: <span class="text-gold" style="color: var(--button-background, #b19356);">{{ $role->label }}</span></h5>
                    <span class="text-muted small">Select individual checkboxes or toggle complete module categories.</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="select-all-gates" class="btn btn-sm btn-outline-dark">Select All</button>
                    <button type="button" id="deselect-all-gates" class="btn btn-sm btn-outline-secondary">Deselect All</button>
                </div>
            </div>

            <!-- Permission Groups Layout Grid -->
            <div class="row g-4 mb-4">
                @foreach($groups as $group)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm bg-white h-100 group-card" data-group-id="{{ $group->id }}">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                                <h6 class="card-title mb-0 fw-bold small uppercase text-muted">{{ $group->name }}</h6>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input toggle-group-gates" type="checkbox" role="switch" id="toggle-group-{{ $group->id }}">
                                </div>
                            </div>
                            <div class="card-body pt-0 px-4 pb-4">
                                <div class="d-flex flex-column gap-2">
                                    @foreach($group->permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input gate-checkbox" type="checkbox" name="permission_ids[]" value="{{ $permission->id }}" id="perm-{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label small text-dark" for="perm-{{ $permission->id }}">
                                                {{ $permission->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Update Role Permissions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Group Toggle Switches
    document.querySelectorAll('.toggle-group-gates').forEach(toggle => {
        const card = toggle.closest('.group-card');
        const checkboxes = card.querySelectorAll('.gate-checkbox');

        const updateToggleState = () => {
            const checkedCount = card.querySelectorAll('.gate-checkbox:checked').length;
            toggle.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        };

        toggle.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = toggle.checked);
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateToggleState));
        updateToggleState(); // Initialize
    });

    // 2. Select All / Deselect All Global Buttons
    document.getElementById('select-all-gates').addEventListener('click', () => {
        document.querySelectorAll('.gate-checkbox').forEach(cb => cb.checked = true);
        document.querySelectorAll('.toggle-group-gates').forEach(t => t.checked = true);
    });

    document.getElementById('deselect-all-gates').addEventListener('click', () => {
        document.querySelectorAll('.gate-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.toggle-group-gates').forEach(t => t.checked = false);
    });
});
</script>
@endpush
@endonce
