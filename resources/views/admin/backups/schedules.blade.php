@extends('admin.layouts.app')

@section('page-title', 'Backup Schedules')
@section('page-description', 'Configure automated backup intervals, cron expressions, and retention limits.')

@section('content')
<div class="container-fluid">
    <!-- Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 d-flex justify-content-between align-items-center flex-wrap">
                    <ul class="nav nav-pills border-0">
                        <li class="nav-item">
                            <a class="nav-link text-secondary" href="{{ route('admin.backups.index') }}">
                                <i class="fa-solid fa-clock-history me-2"></i>History & Analytics
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('admin.backup-schedules.index') }}">
                                <i class="fa-solid fa-calendar-days me-2"></i>Schedule Timers
                            </a>
                        </li>
                    </ul>
                    @can('backup.schedule')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScheduleModal">
                        <i class="fa-solid fa-calendar-plus me-1"></i>Configure Schedule
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle w-100" id="schedulesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Schedule Name</th>
                                    <th>Type</th>
                                    <th>Frequency</th>
                                    <th>Cron Rule</th>
                                    <th>Storage Disk</th>
                                    <th>Retain Amount</th>
                                    <th>State</th>
                                    <th>Last Run</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                <tr>
                                    <td>
                                        <strong>{{ $schedule->schedule_name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">{{ $schedule->backup_type->value }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark text-capitalize">{{ $schedule->frequency->value }}</span>
                                    </td>
                                    <td>
                                        <code class="text-dark">{{ $schedule->cron_expression ?? '-' }}</code>
                                    </td>
                                    <td>{{ $schedule->storage_disk }}</td>
                                    <td>{{ $schedule->retain_backups }} archives</td>
                                    <td>
                                        @if($schedule->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $schedule->last_run_at ? $schedule->last_run_at->diffForHumans() : 'Never' }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            @if($schedule->is_active)
                                                <button class="btn btn-sm btn-outline-warning" onclick="toggleSchedule({{ $schedule->id }}, 'disable')" title="Disable">
                                                    <i class="fa-solid fa-pause"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline-success" onclick="toggleSchedule({{ $schedule->id }}, 'enable')" title="Enable">
                                                    <i class="fa-solid fa-play"></i>
                                                </button>
                                            @endif

                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSchedule({{ $schedule->id }})" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-calendar-xmark fa-2x mb-3 d-block"></i>
                                        No active backup schedules configured.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Schedule Modal -->
@can('backup.schedule')
<div class="modal fade" id="createScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="createScheduleForm" onsubmit="submitCreateSchedule(event)">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">Configure Automated Backup Schedule</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Schedule Name</label>
                        <input type="text" class="form-control" name="schedule_name" placeholder="e.g. Daily Database Dump" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Backup Type</label>
                        <select class="form-select" name="backup_type" required>
                            <option value="database">Database Only</option>
                            <option value="storage">Storage Only</option>
                            <option value="media">Media Library</option>
                            <option value="full">Full Website Backup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frequency</label>
                        <select class="form-select" name="frequency" id="schedule-frequency" onchange="toggleCronField()" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="custom">Custom (Cron Expression)</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="cron-expression-wrapper">
                        <label class="form-label">Cron Expression</label>
                        <input type="text" class="form-control" name="cron_expression" placeholder="* * * * *">
                        <span class="text-muted small">Standard 5-field crontab formatting.</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Storage Disk</label>
                        <select class="form-select" name="storage_disk" required>
                            <option value="local">local (Local Storage)</option>
                            <option value="public">public (Public Access)</option>
                            <option value="s3">s3 (AWS S3 Cloud)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Retain Backup Count</label>
                        <input type="number" class="form-control" name="retain_backups" value="30" min="1" required>
                        <span class="text-muted small">Limit the number of history backups retained on target disk.</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Enter notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Timer Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleCronField() {
        const freq = $('#schedule-frequency').val();
        if (freq === 'custom') {
            $('#cron-expression-wrapper').removeClass('d-none');
        } else {
            $('#cron-expression-wrapper').addClass('d-none');
        }
    }

    function submitCreateSchedule(e) {
        e.preventDefault();
        const data = $('#createScheduleForm').serialize();

        $.post("{{ route('admin.backup-schedules.store') }}", data, function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Configured',
                text: res.message,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Configuration Error',
                text: xhr.responseJSON ? xhr.responseJSON.message : 'Error creating schedule.'
            });
        });
    }

    function toggleSchedule(id, action) {
        $.post(`/admin/backup-schedules/${id}/${action}`, { _token: "{{ csrf_token() }}" }, function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: res.message,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        });
    }

    function deleteSchedule(id) {
        Swal.fire({
            title: 'Delete Backup Schedule?',
            text: 'This action is irreversible.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/backup-schedules/${id}`,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: res.message,
                            timer: 1500
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
