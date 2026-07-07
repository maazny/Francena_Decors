@extends('admin.layouts.app')

@section('title', 'Campaign Delivery Details & Dispatch')
@section('page-title')
    Campaign: {{ $campaign->title }}
@endsection
@section('page-description', 'Monitor delivery metrics, click rates, and trigger manual dispatch targets.')

@section('content')
<div class="row g-3 mb-4">
    <!-- Total Targeted -->
    <div class="col-md-3">
        <div class="card border-0 bg-dark text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small mb-1 text-muted">Total Recipients</h6>
                    <h3 class="mb-0">{{ $campaign->logs()->count() }}</h3>
                </div>
                <i class="fa-solid fa-users fs-3 opacity-50"></i>
            </div>
            <div class="mt-2 text-muted small">Total subscribers targeted</div>
        </div>
    </div>
    <!-- Delivered -->
    <div class="col-md-3">
        <div class="card border-0 bg-success text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small mb-1 text-white-50">Delivered</h6>
                    <h3 class="mb-0">{{ $campaign->logs()->where('delivery_status', 'sent')->count() }}</h3>
                </div>
                <i class="fa-solid fa-circle-check fs-3 opacity-50"></i>
            </div>
            <div class="mt-2 text-white-50 small">Successful email dispatches</div>
        </div>
    </div>
    <!-- Opened -->
    <div class="col-md-3">
        <div class="card border-0 bg-info text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small mb-1 text-white-50">Opened</h6>
                    <h3 class="mb-0">{{ $campaign->logs()->where('opened', true)->count() }}</h3>
                </div>
                <i class="fa-solid fa-envelope-open fs-3 opacity-50"></i>
            </div>
            <div class="mt-2 text-white-50 small">Unique opened views</div>
        </div>
    </div>
    <!-- Failed / Bounced -->
    <div class="col-md-3">
        <div class="card border-0 bg-danger text-white p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase small mb-1 text-white-50">Failed / Bounced</h6>
                    <h3 class="mb-0">{{ $campaign->logs()->where(function($q) { $q->where('failed', true)->orWhere('bounced', true); })->count() }}</h3>
                </div>
                <i class="fa-solid fa-triangle-exclamation fs-3 opacity-50"></i>
            </div>
            <div class="mt-2 text-white-50 small">Delivery failures</div>
        </div>
    </div>
</div>

<div class="row g-4">
    @if($campaign->status->value !== 'sent' && $campaign->status->value !== 'sending')
        <!-- Dispatch Settings Card -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0"><i class="fa-solid fa-paper-plane me-1"></i> Dispatch Campaign</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.newsletter.campaigns.send', $campaign->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="group_id" class="form-label">Target List Segment / Group</label>
                            <select name="group_id" id="group_id" class="form-select">
                                <option value="">All Active Subscribers</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }} ({{ $group->subscribers()->count() }} subscribers)
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted">Select a specific target group segment or send to all active subscribers in the system.</div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100" onclick="return confirm('Are you sure you want to dispatch this email campaign? This cannot be undone.')">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send Campaign Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delivery Detail Table -->
    <div class="{{ $campaign->status->value !== 'sent' && $campaign->status->value !== 'sending' ? 'col-md-8' : 'col-12' }}">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0">Delivery Logs</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Recipient</th>
                            <th>Status</th>
                            <th>Opened</th>
                            <th>Clicked</th>
                            <th>Sent At</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->subscriber->name ?: 'Subscriber' }}</strong>
                                    <div class="small text-muted">{{ $log->subscriber->email }}</div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($log->delivery_status) {
                                            'sent' => 'bg-success-subtle text-success',
                                            'pending' => 'bg-warning-subtle text-warning',
                                            'failed' => 'bg-danger-subtle text-danger',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} border">
                                        {{ ucfirst($log->delivery_status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->opened)
                                        <span class="badge bg-info">Yes</span>
                                    @else
                                        <span class="text-muted small">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->clicked)
                                        <span class="badge bg-primary">Yes</span>
                                    @else
                                        <span class="text-muted small">No</span>
                                    @endif
                                </td>
                                <td>{{ $log->sent_at ? $log->sent_at->format('M d, Y H:i') : '-' }}</td>
                                <td>
                                    @if($log->error_message)
                                        <small class="text-danger d-block">{{ $log->error_message }}</small>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No dispatches logged. Run send to initiate delivery.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
