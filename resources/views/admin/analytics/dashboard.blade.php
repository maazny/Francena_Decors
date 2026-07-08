@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics & KPI Control Center')
@section('page-description', 'Real-time performance metrics, content aggregates, and system health checks.')

@section('content')
<div class="row g-4 mb-4">
    <!-- KPI Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase mb-2">Total Visitors</h6>
                    <h3 class="mb-0">{{ number_format($metrics['visitors']['value'] ?? 0) }}</h3>
                </div>
                <div class="text-primary"><i class="fa-solid fa-users fa-2x"></i></div>
            </div>
            <div class="mt-2 small">
                <span class="{{ ($metrics['visitors']['change'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fa-solid {{ ($metrics['visitors']['change'] ?? 0) >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-1"></i>
                    {{ $metrics['visitors']['change'] ?? 0 }}%
                </span> vs last week
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase mb-2">Blog Posts</h6>
                    <h3 class="mb-0">{{ number_format($metrics['blogs']['value'] ?? 0) }}</h3>
                </div>
                <div class="text-success"><i class="fa-solid fa-file-pen fa-2x"></i></div>
            </div>
            <div class="mt-2 small">
                <span class="text-success"><i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $metrics['blogs']['change'] ?? 0 }}%</span> vs last week
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase mb-2">Projects</h6>
                    <h3 class="mb-0">{{ number_format($metrics['projects']['value'] ?? 0) }}</h3>
                </div>
                <div class="text-warning"><i class="fa-solid fa-diagram-project fa-2x"></i></div>
            </div>
            <div class="mt-2 small">
                <span class="text-success"><i class="fa-solid fa-arrow-trend-up me-1"></i>{{ $metrics['projects']['change'] ?? 0 }}%</span> vs last week
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted text-uppercase mb-2">API Calls</h6>
                    <h3 class="mb-0">{{ number_format($metrics['api_requests']['value'] ?? 0) }}</h3>
                </div>
                <div class="text-info"><i class="fa-solid fa-network-wired fa-2x"></i></div>
            </div>
            <div class="mt-2 small">
                <span class="{{ ($metrics['api_requests']['change'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fa-solid {{ ($metrics['api_requests']['change'] ?? 0) >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} me-1"></i>
                    {{ $metrics['api_requests']['change'] ?? 0 }}%
                </span> vs last week
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Charts section -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title">Daily Web Traffic & Api Timings</h5>
            <canvas id="trafficChart" style="height: 300px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title">Content Share Breakdown</h5>
            <canvas id="contentShareChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-4">
    <h5 class="mb-3">Real-time Quick Actions</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary"><i class="fa-solid fa-file-export me-1"></i>Generate Analytics Report</a>
        <a href="{{ route('admin.analytics.health') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-heart-pulse me-1"></i>System Diagnostics</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Page Visitors',
                    data: [120, 150, 180, 220, 240, 190, 210],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Content Share Doughnut Chart
        const contentCtx = document.getElementById('contentShareChart').getContext('2d');
        new Chart(contentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Blogs', 'Projects', 'Services'],
                datasets: [{
                    data: [
                        {{ $metrics['blogs']['value'] ?? 1 }},
                        {{ $metrics['projects']['value'] ?? 1 }},
                        {{ $metrics['services']['value'] ?? 1 }}
                    ],
                    backgroundColor: ['#198754', '#ffc107', '#0dcaf0']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush
