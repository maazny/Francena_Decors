@extends('admin.layouts.app')

@section('title', 'RBAC Control Center')
@section('page-title', 'Roles & Permissions Dashboard')
@section('page-description', 'Configure secure user authorization roles, action permissions, and security matrix overrides.')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Total Users</span>
                <i class="fa-solid fa-users text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\User::count() }}</h3>
            <p class="text-muted small mb-0 mt-1">Staff accounts registered</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Roles configured</span>
                <i class="fa-solid fa-shield text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\Role::count() }}</h3>
            <p class="text-muted small mb-0 mt-1">System & custom access levels</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Permission Groups</span>
                <i class="fa-solid fa-folder-open text-info fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\PermissionGroup::count() }}</h3>
            <p class="text-muted small mb-0 mt-1">Modules mapping folders</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Super Admins</span>
                <i class="fa-solid fa-crown text-danger fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->count() ?: 1 }}</h3>
            <p class="text-muted small mb-0 mt-1">Unrestricted system locks</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Actions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <h5 class="card-title h6 mb-4 fw-bold">RBAC Operations</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-dark w-100 py-3 text-start">
                        <i class="fa-solid fa-plus-circle mb-2 text-primary fs-4 d-block"></i>
                        <strong class="d-block small">Create Role</strong>
                        <span class="text-muted extra-small d-block">Define a new authority tier</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.users-roles.index') }}" class="btn btn-outline-dark w-100 py-3 text-start">
                        <i class="fa-solid fa-user-gear mb-2 text-warning fs-4 d-block"></i>
                        <strong class="d-block small">Assign Users Roles</strong>
                        <span class="text-muted extra-small d-block">Bind profiles to roles</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-dark w-100 py-3 text-start">
                        <i class="fa-solid fa-sliders mb-2 text-gold fs-4 d-block" style="color: var(--button-background, #b19356);"></i>
                        <strong class="d-block small">Permissions Matrix</strong>
                        <span class="text-muted extra-small d-block">Configure mapping grid</span>
                    </a>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-bold mb-2 small"><i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Security Notice</h6>
                <p class="text-muted small mb-0">
                    System locked roles (e.g. Super Admin) cannot be deleted or have their unique key updated. This prevents accidental locked states.
                </p>
            </div>
        </div>
    </div>

    <!-- Chart Panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <h5 class="card-title h6 mb-4 fw-bold">Role Distribution</h5>
            <div class="chart-container" style="position: relative; height:200px; width:100%">
                <canvas id="rbacDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('rbacDistributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ['Super Admins', 'Administrators', 'Editors', 'Viewers'],
            datasets: [{
                data: [1, 2, 4, 3],
                backgroundColor: ['#d9534f', '#0275d8', '#b19356', '#292b2c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: { size: 10 }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endonce
