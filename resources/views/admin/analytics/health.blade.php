@extends('admin.layouts.app')

@section('title', 'System Health & Performance')
@section('page-title', 'System Health')
@section('page-description', 'Queue latency checkers, storage capacity meters, and runtime drivers indicators.')

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="mb-3">Storage space usage</h5>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>Database Size</td>
                        <td class="text-end fw-bold">{{ $stats['database_size_formatted'] }}</td>
                    </tr>
                    <tr>
                        <td>Cache Driver</td>
                        <td class="text-end fw-bold">{{ $stats['cache_driver'] }}</td>
                    </tr>
                    <tr>
                        <td>Queue Driver</td>
                        <td class="text-end fw-bold">{{ $stats['queue_connection'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
