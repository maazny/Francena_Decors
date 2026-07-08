@extends('admin.layouts.app')

@section('title', 'Reports History')
@section('page-title', 'Reports History Logs')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <h5>Historical runs of reports engine</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Report</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->report_name }}</td>
                    <td>{{ $report->status->value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
