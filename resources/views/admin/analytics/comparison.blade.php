@extends('admin.layouts.app')

@section('title', 'Comparison Reports')
@section('page-title', 'Comparison Reports')
@section('page-description', 'Assess and compare key indicators performance benchmarks over distinct range blocks.')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <h5 class="mb-3">Define Ranges for comparison</h5>
    <form action="" method="GET">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Period 1 Start</label>
                <input type="date" name="start1" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Period 1 End</label>
                <input type="date" name="end1" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Period 2 Start</label>
                <input type="date" name="start2" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Period 2 End</label>
                <input type="date" name="end2" class="form-control" required />
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Compare Periods</button>
        </div>
    </form>
</div>
@endsection
