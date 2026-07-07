@extends('admin.layouts.app')

@section('page-title', 'Backup Archive details')
@section('page-description', 'Audit metadata fields, run checksum validation checks, or trigger restorations.')

@section('content')
<div class="container-fluid">
    <!-- Navigation Tabs / Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap">
                    <a href="{{ route('admin.backups.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i>Back to List
                    </a>
                    <div class="d-flex gap-2">
                        @can('backup.download')
                        @if($backup->status->value === 'completed' || $backup->status->value === 'restored')
                        <a href="{{ route('admin.backups.download', $backup->id) }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-download me-1"></i>Secure Download
                        </a>
                        @endif
                        @endcan

                        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fa-solid fa-print me-1"></i>Print View
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="row g-4">
        <!-- Left details Card -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">General Metadata Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Archive Name</span>
                            <strong>{{ $backup->backup_name }}</strong>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Task Status</span>
                            @php
                                $badgeClass = 'bg-warning';
                                if ($backup->status->value === 'completed' || $backup->status->value === 'restored') $badgeClass = 'bg-success';
                                if ($backup->status->value === 'failed') $badgeClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }} text-uppercase">{{ $backup->status->value }}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Type Category</span>
                            <span class="badge bg-secondary text-uppercase">{{ $backup->backup_type->value }}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Archive Storage Path</span>
                            <code class="text-dark">{{ $backup->storage_path }}</code>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Physical Size</span>
                            <strong>{{ number_format($backup->file_size / 1024 / 1024, 2) }} MB</strong>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-muted d-block small">Download Count</span>
                            <strong>{{ $backup->download_count }} downloads</strong>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12 col-md-4">
                            <span class="text-muted d-block small">Task Started At</span>
                            <span>{{ $backup->started_at ? $backup->started_at->toDayDateTimeString() : 'N/A' }}</span>
                        </div>
                        <div class="col-12 col-md-4">
                            <span class="text-muted d-block small">Task Completed At</span>
                            <span>{{ $backup->completed_at ? $backup->completed_at->toDayDateTimeString() : 'N/A' }}</span>
                        </div>
                        <div class="col-12 col-md-4">
                            <span class="text-muted d-block small">Task Execution Duration</span>
                            <span>{{ $backup->duration_seconds ?? 0 }} seconds</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes or Error Failures Remarks -->
            @if($backup->failure_reason)
            <div class="card border-0 shadow-sm mb-4 border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-danger mb-1 font-weight-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i>Task Execution Failure Reason</h6>
                    <pre class="bg-light p-3 rounded mb-0 font-monospace text-wrap" style="font-size: 13px;">{{ $backup->failure_reason }}</pre>
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">System Notes</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $backup->notes ?? 'No custom notes assigned.' }}</p>
                </div>
            </div>
        </div>

        <!-- Right Side Panel -->
        <div class="col-12 col-lg-4">
            <!-- Integrity & Validation Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">Integrity Checksum</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted d-block small mb-1">SHA-256 Calculated HASH</span>
                        <code class="text-wrap d-block p-2 bg-light rounded text-break" style="font-size: 12px;">{{ $backup->checksum ?? 'Pending compilation...' }}</code>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>Verification Status:</span>
                        @if($backup->is_verified)
                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Integrity Verified</span>
                        @else
                            <span class="badge bg-secondary">Unverified</span>
                        @endif
                    </div>
                    <hr>
                    @can('backup.verify')
                    <button type="button" class="btn btn-outline-info w-100" onclick="verifyBackup({{ $backup->id }})">
                        <i class="fa-solid fa-shield-halved me-1"></i>Re-Validate Checksum
                    </button>
                    @endcan
                </div>
            </div>

            <!-- Destination disk details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title mb-0">Storage Target Driver</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-hard-drive fa-2x text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0 text-uppercase font-weight-bold">{{ $backup->storage_disk }}</h6>
                            <span class="text-muted small">Disk Configuration Driver</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restore Action Card -->
            @can('backup.restore')
            @if($backup->status->value === 'completed' || $backup->status->value === 'restored')
            <div class="card border-0 shadow-sm mb-4 border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-success mb-2 font-weight-bold">System Restoration Gateway</h6>
                    <p class="text-muted small mb-3">Applying this backup replaces database tables. Only Super Admins are allowed to trigger this action.</p>
                    <button type="button" class="btn btn-success w-100" onclick="restoreBackup({{ $backup->id }})">
                        <i class="fa-solid fa-rotate-left me-1"></i>Recover From This Point
                    </button>
                </div>
            </div>
            @endif
            @endcan
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function verifyBackup(id) {
        Swal.fire({
            title: 'Verifying Checksum...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        $.post(`/admin/backups/${id}/verify`, { _token: "{{ csrf_token() }}" }, function(res) {
            Swal.fire({
                icon: res.verified ? 'success' : 'error',
                title: res.verified ? 'Integrity Clear' : 'Integrity Failed',
                text: res.message
            }).then(() => {
                window.location.reload();
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
                    }).then(() => {
                        window.location.reload();
                    });
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
</script>
@endpush
