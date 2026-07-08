@extends('admin.layouts.app')

@section('title', 'Generate Reports')
@section('page-title', 'Generate Reports')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <h5>Configure and Run analytical reports compilation</h5>
    <form action="{{ route('admin.reports.generate') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Report Name</label>
            <input type="text" name="report_name" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Generate</button>
    </form>
</div>
@endsection
