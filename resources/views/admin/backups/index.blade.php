@extends('admin.layouts.app')

@section('page-title', 'Backup & Restore Management')
@section('page-description', 'Manage enterprise system archives, scheduled cron states, and restorations.')

@section('content')
<div class="container-fluid">
    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 d-flex justify-content-between align-items-center flex-wrap">
                    <ul class="nav nav-pills border-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.backups.index') }}">
                                <i class="fa-solid fa-clock-history me-2"></i>History & Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('admin.backup-schedules.index') }}">
                                <i class="fa-solid fa-calendar-days me-2"></i>Schedule Timers
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex gap-2 mt-2 mt-md-0">
                        <button type="button" class="btn btn-outline-secondary" onclick="refreshStats()">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>Refresh Stats
                        </button>
                        @can('backup.export')
                        <a href="{{ route('admin.backups.export') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-file-export me-1"></i>Export History
                        </a>
                        @endcan
                        @can('backup.create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                            <i class="fa-solid fa-circle-plus me-1"></i>New Backup
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Statistics KPI Cards -->
    <div class="row g-3 mb-4" id="kpiCardsContainer">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 bg-light-blue" style="border-left: 4px solid #0d6efd !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1 small">Total Archives</h6>
                    <h3 class="mb-0 text-dark font-weight-bold" id="stat-total">{{ $statistics['total_backups'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1 small">Database Only</h6>
                    <h3 class="mb-0 text-dark font-weight-bold" id="stat-db">{{ $statistics['database_backups'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #0dcaf0 !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1 small">Full Website</h6>
                    <h3 class="mb-0 text-dark font-weight-bold" id="stat-full">{{ $statistics['full_backups'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1 small">Storage Size Used</h6>
                    <h3 class="mb-0 text-dark font-weight-bold" id="stat-size">
                        {{ number_format(($statistics['storage_used'] ?? 0) / 1024 / 1024, 2) }} MB
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-1 small">Failed Tasks</h6>
                    <h3 class="mb-0 text-danger font-weight-bold" id="stat-failed">{{ $statistics['failed_backups'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">Storage Growth Profile</h5>
                </div>
                <div class="card-body">
                    <canvas id="storageGrowthChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">Categories Composition</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 220px;">
                        <canvas id="backupTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Data Table with Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="card-title mb-0">Execution Records</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fa-solid fa-filter me-1"></i>Toggle Filters
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filters Collapse -->
                    <div class="collapse show mb-4" id="filtersCollapse">
                        <form id="filterForm" class="row g-3 p-3 bg-light rounded">
                            <div class="col-12 col-md-3">
                                <label class="form-label font-weight-bold">Backup Type</label>
                                <select class="form-select" name="backup_type" id="filter-type">
                                    <option value="">All Types</option>
                                    <option value="database">Database Only</option>
                                    <option value="storage">Storage Only</option>
                                    <option value="media">Media Library</option>
                                    <option value="full">Full Website</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Task Status</label>
                                <select class="form-select" name="status" id="filter-status">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="running">Running</option>
                                    <option value="completed">Completed</option>
                                    <option value="failed">Failed</option>
                                    <option value="restoring">Restoring</option>
                                    <option value="restored">Restored</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Storage Disk</label>
                                <select class="form-select" name="storage_disk" id="filter-disk">
                                    <option value="">All Disks</option>
                                    <option value="local">local</option>
                                    <option value="public">public</option>
                                    <option value="s3">s3</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-secondary w-100" onclick="applyFilters()">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i>Search Records
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle w-100" id="backupsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Disk</th>
                                    <th>File Size</th>
                                    <th>Integrity</th>
                                    <th>Created At</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via Ajax server-side -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Backup Modal -->
@can('backup.create')
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createBackupForm" onsubmit="submitCreateBackup(event)">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">Initialize System Backup</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Backup Name</label>
                        <input type="text" class="form-control" name="backup_name" placeholder="e.g. Weekly_Prod_System" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target Strategy Type</label>
                        <select class="form-select" name="backup_type" required>
                            <option value="database">Database Only</option>
                            <option value="storage">Storage Only</option>
                            <option value="media">Media Library</option>
                            <option value="full">Full Website Backup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Storage Target Disk</label>
                        <select class="form-select" name="storage_disk" required>
                            <option value="local">local (Local Storage)</option>
                            <option value="public">public (Public Access)</option>
                            <option value="s3">s3 (AWS S3 Cloud)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Remarks</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter notes or tags..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-circle-play me-1"></i>Launch Task
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .bg-light-blue { background-color: #f0f8ff; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let table;
    let growthChart;
    let typesChart;

    $(document).ready(function() {
        // Initialize DataTable
        table = $('#backupsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.backups.history') }}",
                data: function (d) {
                    d.backup_type = $('#filter-type').val();
                    d.status = $('#filter-status').val();
                    d.storage_disk = $('#filter-disk').val();
                },
                dataSrc: function (json) {
                    return json.data;
                }
            },
            columns: [
                { data: 'backup_name', name: 'backup_name', render: function(data, type, row) {
                    return `<a href="/admin/backups/${row.id}" class="text-decoration-none font-weight-bold">${data}</a>`;
                }},
                { data: 'backup_type', name: 'backup_type', render: function(data) {
                    return `<span class="badge bg-secondary text-uppercase">${data}</span>`;
                }},
                { data: 'status', name: 'status', render: function(data) {
                    let badgeClass = 'bg-warning';
                    if (data === 'completed' || data === 'restored') badgeClass = 'bg-success';
                    if (data === 'failed') badgeClass = 'bg-danger';
                    return `<span class="badge ${badgeClass} text-uppercase">${data}</span>`;
                }},
                { data: 'storage_disk', name: 'storage_disk' },
                { data: 'file_size', name: 'file_size', render: function(data) {
                    return (data / 1024 / 1024).toFixed(2) + ' MB';
                }},
                { data: 'is_verified', name: 'is_verified', render: function(data) {
                    return data ? '<span class="badge bg-success"><i class="fa-solid fa-circle-check"></i> Verified</span>' : '<span class="badge bg-light text-dark">Unverified</span>';
                }},
                { data: 'created_at', name: 'created_at', render: function(data) {
                    return new Date(data).toLocaleString();
                }},
                { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) {
                    let actions = `<div class="d-flex justify-content-end gap-1">`;
                    
                    @can('backup.download')
                    if (row.status === 'completed' || row.status === 'restored') {
                        actions += `<a href="/admin/backups/${data}/download" class="btn btn-sm btn-outline-secondary" title="Download"><i class="fa-solid fa-download"></i></a>`;
                    }
                    @endcan

                    @can('backup.verify')
                    actions += `<button class="btn btn-sm btn-outline-info" onclick="verifyBackup(${data})" title="Verify Checksum"><i class="fa-solid fa-shield-halved"></i></button>`;
                    @endcan

                    @can('backup.restore')
                    if (row.status === 'completed' || row.status === 'restored') {
                        actions += `<button class="btn btn-sm btn-outline-success" onclick="restoreBackup(${data})" title="Restore"><i class="fa-solid fa-rotate-left"></i></button>`;
                    }
                    @endcan

                    if (row.status === 'failed') {
                        actions += `<button class="btn btn-sm btn-outline-primary" onclick="retryBackup(${data})" title="Retry"><i class="fa-solid fa-arrow-rotate-right"></i></button>`;
                    }

                    @can('backup.delete')
                    actions += `<button class="btn btn-sm btn-outline-danger" onclick="deleteBackup(${data})" title="Delete"><i class="fa-solid fa-trash"></i></button>`;
                    @endcan

                    actions += `</div>`;
                    return actions;
                }}
            ],
            order: [[6, 'desc']],
            pageLength: 15,
            lengthMenu: [10, 15, 30, 50]
        });

        // Initialize Charts
        initCharts();
    });

    function applyFilters() {
        table.ajax.reload();
    }

    function refreshStats() {
        $.getJSON("{{ route('admin.backups.statistics') }}", function(data) {
            $('#stat-total').text(data.total_backups);
            $('#stat-db').text(data.database_backups);
            $('#stat-full').text(data.full_backups);
            $('#stat-size').text((data.storage_used / 1024 / 1024).toFixed(2) + ' MB');
            $('#stat-failed').text(data.failed_backups);

            // Reload DataTables and Chart
            table.ajax.reload();
            updateChartData(data);
            Swal.fire({
                icon: 'success',
                title: 'Refreshed',
                text: 'Dashboard metrics and charts updated.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }

    function initCharts() {
        const growthCtx = document.getElementById('storageGrowthChart').getContext('2d');
        growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Size (MB)',
                    data: [15, 25, 42, 60, 85, 120],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        const typesCtx = document.getElementById('backupTypesChart').getContext('2d');
        typesChart = new Chart(typesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Database', 'Full Website', 'Media', 'Storage'],
                datasets: [{
                    data: [
                        {{ $statistics['database_backups'] ?? 4 }},
                        {{ $statistics['full_backups'] ?? 2 }},
                        1,
                        1
                    ],
                    backgroundColor: ['#198754', '#0d6efd', '#ffc107', '#0dcaf0']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    function updateChartData(data) {
        typesChart.data.datasets[0].data = [
            data.database_backups,
            data.full_backups,
            1,
            1
        ];
        typesChart.update();
    }

    function submitCreateBackup(e) {
        e.preventDefault();
        const form = $('#createBackupForm');
        const data = form.serialize();

        Swal.fire({
            title: 'Launching Backup Task...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.post("{{ route('admin.backups.store') }}", data, function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Dispatched Successfully',
                text: res.message,
                timer: 2000
            });
            $('#createBackupModal').modal('hide');
            form[0].reset();
            refreshStats();
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Task Rejected',
                text: xhr.responseJSON ? xhr.responseJSON.message : 'Error starting backup.'
            });
        });
    }

    function verifyBackup(id) {
        Swal.fire({
            title: 'Verifying Checksum...',
            text: 'Please wait while we compute file SHA-256 signature...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.post(`/admin/backups/${id}/verify`, { _token: "{{ csrf_token() }}" }, function(res) {
            Swal.fire({
                icon: res.verified ? 'success' : 'error',
                title: res.verified ? 'Integrity Clear' : 'Integrity Compromised',
                text: res.message
            });
            table.ajax.reload(null, false);
        }).fail(function() {
            Swal.fire({
                icon: 'error',
                title: 'Verification Failed',
                text: 'Could not contact verification daemon.'
            });
        });
    }

    function restoreBackup(id) {
        Swal.fire({
            title: 'Confirm System Restore?',
            text: 'Warning: This replaces active database parameters and cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#secondary',
            confirmButtonText: 'Yes, Restore Now'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Restoring Database...',
                    text: 'Extracting and re-applying parameters. Do not close browser.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                $.post(`/admin/backups/${id}/restore`, {
                    _token: "{{ csrf_token() }}",
                    confirm_restore: 1
                }, function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Restored',
                        text: res.message
                    });
                    table.ajax.reload(null, false);
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Restore Failed',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Error restoring backup.'
                    });
                });
            }
        });
    }

    function retryBackup(id) {
        Swal.fire({
            title: 'Retry compiling backup?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Retry'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/admin/backups/${id}/retry`, { _token: "{{ csrf_token() }}" }, function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Retrying',
                        text: res.message,
                        timer: 1500
                    });
                    table.ajax.reload(null, false);
                });
            }
        });
    }

    function deleteBackup(id) {
        Swal.fire({
            title: 'Delete Backup Record?',
            text: 'This deletes the file permanently from storage disk.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/backups/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: res.message,
                            timer: 1500
                        });
                        refreshStats();
                    }
                });
            }
        });
    }
</script>
@endpush
