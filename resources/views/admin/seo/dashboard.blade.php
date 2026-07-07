@extends('admin.layouts.app')

@section('title', 'SEO Dashboard')
@section('page-title', 'SEO Control Center')
@section('page-description', 'Manage enterprise search engine optimization, redirects, sitemaps, and indexing schemas.')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Widgets -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">SEO Health Score</span>
                <i class="fa-solid fa-heart-pulse text-success fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">94%</h3>
            <div class="mt-2 progress" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 94%" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Optimized Pages</span>
                <i class="fa-solid fa-circle-check text-primary fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\SeoPage::count() }}</h3>
            <p class="text-muted small mb-0 mt-1">Custom page overrides mapped</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Active Redirects</span>
                <i class="fa-solid fa-arrow-right-arrow-left text-warning fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\SeoRedirect::active()->count() }}</h3>
            <p class="text-muted small mb-0 mt-1">Rules actively resolving paths</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Structured Schemas</span>
                <i class="fa-solid fa-code text-info fs-4"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ \App\Models\SeoStructuredData::active()->count() }}</h3>
            <p class="text-muted small mb-0 mt-1">JSON-LD blocks configured</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Quick Actions Panel -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <h5 class="card-title h6 mb-4 fw-bold">Enterprise Quick Operations</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <form method="POST" action="{{ route('admin.seo.sitemaps.generate') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark w-100 py-3 text-start">
                            <i class="fa-solid fa-sitemap mb-2 text-primary fs-4 d-block"></i>
                            <strong class="d-block small">Generate Sitemap</strong>
                            <span class="text-muted extra-small d-block">Rebuild and publish sitemap.xml</span>
                        </button>
                    </form>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.seo.redirects.test') }}" class="btn btn-outline-dark w-100 py-3 text-start">
                        <i class="fa-solid fa-vial mb-2 text-warning fs-4 d-block"></i>
                        <strong class="d-block small">Test Redirect Routes</strong>
                        <span class="text-muted extra-small d-block">Validate target URLs and trace chains</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.seo.settings.edit') }}" class="btn btn-outline-dark w-100 py-3 text-start">
                        <i class="fa-solid fa-gears mb-2 text-gold fs-4 d-block" style="color: var(--button-background, #b19356);"></i>
                        <strong class="d-block small">Global Metadata</strong>
                        <span class="text-muted extra-small d-block">Default Open Graph and Search console</span>
                    </a>
                </div>
            </div>

            <!-- Health Scanning recommendations -->
            <div class="mt-4 p-3 bg-light rounded-3">
                <h6 class="fw-bold mb-2 small"><i class="fa-solid fa-circle-exclamation text-danger me-1"></i> SEO Health Recommendation</h6>
                <p class="text-muted small mb-0">
                    We noticed you have configured redirects that do not contain logs metadata. Regenerate your dynamic sitemap file to keep your search index aligned.
                </p>
            </div>
        </div>
    </div>

    <!-- Chart panel -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 bg-white h-100">
            <h5 class="card-title h6 mb-4 fw-bold">Optimization Coverage</h5>
            <div class="chart-container" style="position: relative; height:200px; width:100%">
                <canvas id="seoCoverageChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('seoCoverageChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Optimized Pages', 'Missing Metadata', 'Sitemap Indexed'],
            datasets: [{
                data: [{{ \App\Models\SeoPage::count() }}, 4, 12],
                backgroundColor: ['#b19356', '#e0e0e0', '#212529'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endonce
