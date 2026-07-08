@extends('admin.layouts.app')

@section('title', 'Report Details')
@section('page-title', 'Report Details')
@section('page-description', 'Review generated metrics, download files, and inspect filters configuration.')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="mb-3">Report parameters</h5>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td class="text-end fw-bold">{{ $report->report_name }}</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td class="text-end"><span class="badge bg-secondary">{{ $report->report_type->value }}</span></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td class="text-end">
                            <span class="badge @if($report->status->value === 'completed') bg-success @else bg-warning @endif">
                                {{ $report->status->value }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
            @if($report->file_path)
                <div class="mt-3">
                    <a href="{{ route('admin.reports.download', $report->id) }}" class="btn btn-success w-100">
                        <i class="fa-solid fa-download me-1"></i>Download Report file
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="mb-3">Generated Report Data</h5>
            <pre class="bg-light p-3 rounded"><code>{{ json_encode($report->report_data, JSON_PRETTY_PRINT) }}</code></pre>
        </div>
    </div>
</div>
@endsection
