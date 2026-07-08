@extends('admin.layouts.app')

@section('title', 'Analytical Snapshots')
@section('page-title', 'Analytical Snapshots')
@section('page-description', 'Periodical snapshots of database, traffic, media sizes, and KPI statistics.')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <h5 class="mb-3">Snapshot Metrics Logs</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Snapshot Name</th>
                    <th>Metric Type</th>
                    <th>Module</th>
                    <th>Metric Key</th>
                    <th>Metric Value</th>
                    <th>Captured At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($snapshots as $snapshot)
                    <tr>
                        <td>{{ $snapshot->snapshot_name }}</td>
                        <td><span class="badge bg-secondary">{{ $snapshot->metric_type->value }}</span></td>
                        <td>{{ $snapshot->module }}</td>
                        <td><code>{{ $snapshot->metric_key }}</code></td>
                        <td>{{ number_format((float)$snapshot->metric_value, 2) }}</td>
                        <td>{{ $snapshot->captured_at->toDateTimeString() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No snapshots captured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
