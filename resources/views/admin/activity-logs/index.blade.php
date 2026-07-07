@extends('admin.layouts.app')

@section('title', 'Activity Audit Trail')
@section('page-title', 'Activity Logs & Audit Trail')
@section('page-description', 'Enterprise activity logging, user audits, security tracking and diagnostics logs.')

@section('content')
<!-- Custom Styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .card-stat {
        transition: transform 0.2s;
    }
    .card-stat:hover {
        transform: translateY(-3px);
    }
    .badge-login { background-color: #e3f2fd; color: #0d47a1; }
    .badge-logout { background-color: #f3e5f5; color: #4a148c; }
    .badge-create { background-color: #e8f5e9; color: #1b5e20; }
    .badge-update { background-color: #fff8e1; color: #ff6f00; }
    .badge-delete { background-color: #ffebee; color: #b71c1c; }
    .badge-restore { background-color: #e0f2f1; color: #004d40; }
    .badge-permission { background-color: #ede7f6; color: #311b92; }
    .badge-role { background-color: #fbe9e7; color: #e65100; }
    .badge-settings { background-color: #eceff1; color: #263238; }
</style>

<!-- 1. Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-primary-subtle text-primary rounded-3 me-3">
                    <i class="fa-solid fa-clock-rotate-left fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Total Activities</h6>
                    <h3 class="mb-0 fw-bold">{{ number_format($statistics['total_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-info-subtle text-info rounded-3 me-3">
                    <i class="fa-solid fa-calendar-day fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Today's Logs</h6>
                    <h3 class="mb-0 fw-bold">{{ number_format($statistics['today_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-secondary-subtle text-secondary rounded-3 me-3">
                    <i class="fa-solid fa-calendar-week fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Weekly Logs</h6>
                    <h3 class="mb-0 fw-bold">{{ number_format($statistics['weekly_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-dark-subtle text-dark rounded-3 me-3">
                    <i class="fa-solid fa-calendar-days fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Monthly Logs</h6>
                    <h3 class="mb-0 fw-bold">{{ number_format($statistics['monthly_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-success-subtle text-success rounded-3 me-3">
                    <i class="fa-solid fa-circle-check fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Success Logs</h6>
                    <h3 class="mb-0 fw-bold text-success">{{ number_format($statistics['successful_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-0 shadow-sm bg-white h-100 card-stat">
            <div class="card-body py-3 d-flex align-items-center">
                <div class="badge p-3 bg-danger-subtle text-danger rounded-3 me-3">
                    <i class="fa-solid fa-circle-exclamation fa-xl"></i>
                </div>
                <div>
                    <h6 class="text-muted small mb-1">Failed Logs</h6>
                    <h3 class="mb-0 fw-bold text-danger">{{ number_format($statistics['failed_logs']) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Dashboard Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-chart-bar me-2 text-primary"></i>Activities Per Module</h6>
            </div>
            <div class="card-body">
                <canvas id="moduleChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-pie-chart me-2 text-info"></i>Success vs Failed Ratio</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- 3. Advanced Collapsible Filters -->
<div class="card border-0 shadow-sm bg-white mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title h6 mb-0 fw-bold">
            <i class="fa-solid fa-sliders me-2 text-secondary"></i>Advanced Filter Controls
        </h5>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">
            <i class="fa-solid fa-chevron-down"></i> Toggle Filters
        </button>
    </div>
    <div class="collapse show" id="filterPanel">
        <div class="card-body bg-light-subtle">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">User Profile</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">User Role</label>
                    <select name="role_id" class="form-select form-select-sm">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Target Module</label>
                    <select name="module" class="form-select form-select-sm">
                        <option value="">All Modules</option>
                        @foreach($modules as $moduleName)
                            <option value="{{ $moduleName }}">{{ ucfirst($moduleName) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Action Type</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action->value }}">{{ ucfirst(str_replace('_', ' ', $action->value)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Execution Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">IP Address</label>
                    <input type="text" name="ip_address" class="form-control form-control-sm" placeholder="e.g. 127.0.0.1">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Browser Type</label>
                    <input type="text" name="browser" class="form-control form-control-sm" placeholder="e.g. Chrome">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Device Category</label>
                    <select name="device" class="form-select form-select-sm">
                        <option value="">All Devices</option>
                        <option value="Desktop">Desktop</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Tablet">Tablet</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Operating System</label>
                    <select name="operating_system" class="form-select form-select-sm">
                        <option value="">All OS</option>
                        <option value="Windows">Windows</option>
                        <option value="macOS">macOS</option>
                        <option value="Linux">Linux</option>
                        <option value="iOS">iOS</option>
                        <option value="Android">Android</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">General Keyword</label>
                    <input type="text" name="keyword" id="tableSearch" class="form-control form-control-sm" placeholder="Description search...">
                </div>
                <div class="col-12 d-flex justify-content-between mt-3 border-top pt-3">
                    <div>
                        <button type="submit" class="btn btn-sm btn-primary me-2">
                            <i class="fa-solid fa-filter me-1"></i> Apply Filters
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fa-solid fa-rotate me-1"></i> Reset
                        </button>
                        <button type="button" id="refreshBtn" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-sync me-1"></i> Refresh Table
                        </button>
                    </div>
                    @can('export_activity_logs')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-download me-1"></i> Export Data
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item export-action" href="#" data-type="csv"><i class="fa-solid fa-file-csv me-2 text-success"></i>CSV format</a></li>
                            <li><a class="dropdown-item export-action" href="#" data-type="excel"><i class="fa-solid fa-file-excel me-2 text-success"></i>Excel Sheet</a></li>
                            <li><a class="dropdown-item export-action" href="#" data-type="print" target="_blank"><i class="fa-solid fa-print me-2 text-dark"></i>Print View</a></li>
                        </ul>
                    </div>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 4. DataTables Audit Listing -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title h6 mb-0 fw-bold">Audit History Records</h5>
        <span class="badge bg-secondary-subtle text-secondary small py-2 px-3">Read Only History Logs</span>
    </div>
    <div class="table-responsive p-3">
        <table id="activityLogsTable" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
                <tr>
                    <th width="30"><input type="checkbox" id="bulkSelectAll" class="form-check-input"></th>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated dynamically via server-side DataTables -->
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function () {
    const tableElement = $('#activityLogsTable');
    
    const dataTable = tableElement.DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
            url: "{{ route('admin.activity-logs.index') }}",
            data: function (d) {
                // Attach advanced filters
                d.user_id = $('select[name="user_id"]').val();
                d.role_id = $('select[name="role_id"]').val();
                d.module = $('select[name="module"]').val();
                d.action = $('select[name="action"]').val();
                d.status = $('select[name="status"]').val();
                d.date_from = $('input[name="date_from"]').val();
                d.date_to = $('input[name="date_to"]').val();
                d.ip_address = $('input[name="ip_address"]').val();
                d.browser = $('input[name="browser"]').val();
                d.device = $('select[name="device"]').val();
                d.operating_system = $('select[name="operating_system"]').val();
                d.keyword = $('#tableSearch').val();
            },
            dataSrc: function (json) {
                return json.data;
            }
        },
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `<input type="checkbox" class="form-check-input row-select" value="${data}">`;
                }
            },
            { data: 'id' },
            {
                data: 'user',
                render: function (data) {
                    return data ? `<strong>${data.name}</strong><br><span class="text-muted small">${data.email}</span>` : '<span class="text-muted italic">System</span>';
                }
            },
            {
                data: 'role',
                render: function (data) {
                    return data ? `<span class="badge bg-light text-dark border">${data.label}</span>` : '<span class="text-muted small">N/A</span>';
                }
            },
            {
                data: 'module',
                render: function (data) {
                    return `<code class="text-secondary">${data}</code>`;
                }
            },
            {
                data: 'action',
                render: function (data) {
                    const actionClass = `badge-${data.value || data}`;
                    const label = (data.value || data).toUpperCase().replace('_', ' ');
                    return `<span class="badge ${actionClass}">${label}</span>`;
                }
            },
            {
                data: 'description',
                render: function (data) {
                    return `<span class="small text-dark">${data || 'No description'}</span>`;
                }
            },
            { data: 'ip_address' },
            {
                data: 'status',
                render: function (data) {
                    const statusVal = data.value || data;
                    if (statusVal === 'success') {
                        return `<span class="badge bg-success-subtle text-success"><i class="fa-solid fa-circle-check me-1"></i> Success</span>`;
                    }
                    return `<span class="badge bg-danger-subtle text-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Failed</span>`;
                }
            },
            {
                data: 'created_at',
                render: function (data) {
                    return new Date(data).toLocaleString();
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function (data) {
                    const viewUrl = "{{ route('admin.activity-logs.show', ':id') }}".replace(':id', data);
                    return `
                        <div class="btn-group">
                            <a href="${viewUrl}" class="btn btn-sm btn-outline-primary" title="View Audit Details">
                                <i class="fa-solid fa-eye"></i> View Detail
                            </a>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'desc']],
        pageLength: 20,
        lengthMenu: [10, 20, 50, 100],
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>"
    });

    // Submit Filter Form
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        dataTable.draw();
    });

    // Reset Filters
    $('#resetBtn').on('click', function () {
        $('#filterForm')[0].reset();
        dataTable.draw();
    });

    // Refresh Table
    $('#refreshBtn').on('click', function () {
        dataTable.ajax.reload();
    });

    // Global Keyup Search
    $('#tableSearch').on('keyup', function () {
        dataTable.draw();
    });

    // Bulk Select All Checkbox
    $('#bulkSelectAll').on('click', function () {
        $('.row-select').prop('checked', this.checked);
    });

    // Export Action handler
    $('.export-action').on('click', function (e) {
        e.preventDefault();
        const exportType = $(this).data('type');
        const formParams = $('#filterForm').serialize();
        const exportUrl = "{{ route('admin.activity-logs.export') }}?" + formParams + "&export_type=" + exportType;
        window.open(exportUrl, '_blank');
    });

    // 5. Chart JS implementations
    const modulesData = @json($statistics['most_active_modules']);
    const moduleLabels = modulesData.map(m => m.module.toUpperCase());
    const moduleCounts = modulesData.map(m => m.count);

    new Chart(document.getElementById('moduleChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: moduleLabels,
            datasets: [{
                label: 'Activity logs',
                data: moduleCounts,
                backgroundColor: 'rgba(13, 110, 253, 0.75)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Success', 'Failed'],
            datasets: [{
                data: [{{ $statistics['successful_logs'] }}, {{ $statistics['failed_logs'] }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection
