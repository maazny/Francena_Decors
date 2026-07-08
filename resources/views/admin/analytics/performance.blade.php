@extends('admin.layouts.app')

@section('title', 'API Performance timings')
@section('page-title', 'API Performance')
@section('page-description', 'Query execution details, server load response timing benchmarks.')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="mb-3">Database latency metrics</h5>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>Database Connection</td>
                        <td class="text-end fw-bold">{{ $perf['database_connection'] }}</td>
                    </tr>
                    <tr>
                        <td>Avg API latency</td>
                        <td class="text-end fw-bold text-success">{{ $perf['average_api_response_ms'] }} ms</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
