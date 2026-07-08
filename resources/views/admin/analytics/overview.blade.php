@extends('admin.layouts.app')

@section('title', 'Website Traffic Overview')
@section('page-title', 'Website Overview')
@section('page-description', 'Visualizing traffic paths, session averages, and browser metrics.')

@section('content')
<div class="card border-0 shadow-sm p-4">
    <h5 class="mb-4">Traffic Performance over Period</h5>
    <canvas id="overviewChart" style="height: 350px;"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('overviewChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Page Views',
                    data: [1200, 1900, 3000, 5000, 4000, 6000],
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
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
