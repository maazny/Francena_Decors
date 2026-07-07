@extends('admin.layouts.app')

@section('title', 'Audit Record Details')
@section('page-title', 'Audit Record Details')
@section('page-description', 'Detailed security metrics, request state metadata, and database change tracker logs.')

@section('content')
<div class="row g-4">
    <!-- Main Audit Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title h6 mb-0 fw-bold"><i class="fa-solid fa-circle-info text-primary me-2"></i>Activity Information</h5>
                <div>
                    @if($log->status->value === 'success')
                        <span class="badge bg-success-subtle text-success py-2 px-3"><i class="fa-solid fa-check-circle me-1"></i> Success</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger py-2 px-3"><i class="fa-solid fa-exclamation-circle me-1"></i> Failed</span>
                    @endif
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Log Identifier</label>
                        <strong>#{{ $log->id }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">UUID</label>
                        <code>{{ $log->uuid }}</code>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Module Group</label>
                        <span class="badge bg-light text-dark border">{{ ucfirst($log->module) }}</span>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Action Type</label>
                        <span class="badge bg-primary-subtle text-primary">{{ strtoupper(str_replace('_', ' ', $log->action->value)) }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small d-block">Audit Description</label>
                        <p class="mb-0 text-dark font-monospace bg-light p-3 rounded border">{{ $log->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON State Changes (Before & After) -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-white h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="card-title mb-0 fw-bold text-danger"><i class="fa-solid fa-backward me-2"></i>Old Values (Before Change)</h6>
                    </div>
                    <div class="card-body border-top bg-light-subtle">
                        @if(!empty($log->old_values))
                            <pre class="bg-dark text-white p-3 rounded small overflow-auto" style="max-height: 350px;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <div class="text-muted py-4 text-center italic small"><i class="fa-solid fa-ban d-block mb-2"></i>No historical prior state logged.</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm bg-white h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="card-title mb-0 fw-bold text-success"><i class="fa-solid fa-forward me-2"></i>New Values (After Change)</h6>
                    </div>
                    <div class="card-body border-top bg-light-subtle">
                        @if(!empty($log->new_values))
                            <pre class="bg-dark text-white p-3 rounded small overflow-auto" style="max-height: 350px;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        @else
                            <div class="text-muted py-4 text-center italic small"><i class="fa-solid fa-ban d-block mb-2"></i>No modifications logged.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Metadata Metrics -->
    <div class="col-lg-4">
        <!-- Executor Profile -->
        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-user-shield me-2 text-secondary"></i>Executor Profile</h6>
            </div>
            <div class="card-body border-top">
                @if($log->user)
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge p-3 bg-secondary-subtle text-secondary rounded-circle me-3">
                            <i class="fa-solid fa-user fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $log->user->name }}</h6>
                            <span class="text-muted small">{{ $log->user->email }}</span>
                        </div>
                    </div>
                    @if($log->role)
                        <div class="mb-2">
                            <label class="text-muted small d-block">Assigned Role</label>
                            <span class="badge bg-light text-dark border">{{ $log->role->label }}</span>
                        </div>
                    @endif
                @else
                    <div class="d-flex align-items-center text-muted">
                        <div class="badge p-3 bg-warning-subtle text-warning rounded-circle me-3">
                            <i class="fa-solid fa-robot fa-xl"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">System Action</h6>
                            <span class="small">Triggered internally by system kernel.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Technical Environment Metrics -->
        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-desktop me-2 text-secondary"></i>Client Environment</h6>
            </div>
            <div class="card-body border-top">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">IP Address</span>
                        <strong>{{ $log->ip_address ?: 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">OS Engine</span>
                        <strong>{{ $log->operating_system ?: 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Web Browser</span>
                        <strong>{{ $log->browser ?: 'N/A' }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Device Category</span>
                        <strong>{{ $log->device ?: 'N/A' }}</strong>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Request Details -->
        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="card-title mb-0 fw-bold"><i class="fa-solid fa-server me-2 text-secondary"></i>HTTP Request Info</h6>
            </div>
            <div class="card-body border-top">
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0">
                        <span class="text-muted d-block mb-1">Target URL</span>
                        <code class="text-wrap text-break">{{ $log->url ?: 'N/A' }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Method</span>
                        <span class="badge bg-secondary">{{ $log->method ?: 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Session ID</span>
                        <code class="small text-truncate" style="max-width: 150px;">{{ $log->session_id ?: 'N/A' }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Request Token ID</span>
                        <code class="small text-truncate" style="max-width: 150px;">{{ $log->request_id ?: 'N/A' }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Captured At</span>
                        <span>{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Navigation Actions -->
<div class="d-flex justify-content-between mt-4">
    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Audit History
    </a>
    <a href="{{ route('admin.activity-logs.print', $log->id) }}" class="btn btn-dark" target="_blank">
        <i class="fa-solid fa-print me-1"></i> Print This Record
    </a>
</div>
@endsection
