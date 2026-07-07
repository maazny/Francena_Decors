@extends('admin.layouts.app')

@section('title', 'Sitemap Configuration')
@section('page-title', 'Sitemap Manager')
@section('page-description', 'Regenerate search index sitemaps containing dynamic blog posts, portfolios, services, and routing layouts.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm bg-white mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0 fw-bold">Sitemap Index Overview</h5>
            </div>
            <div class="card-body p-4">
                <div class="p-3 bg-light rounded-3 mb-4">
                    <div class="row text-center font-monospace">
                        <div class="col-6 border-end">
                            <span class="text-muted small d-block uppercase">Sitemap File Location</span>
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="fw-bold mt-1 d-block text-decoration-none">
                                /sitemap.xml <i class="fa-solid fa-up-right-from-square small ms-1"></i>
                            </a>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block uppercase">Dynamic Content Mapped</span>
                            <span class="fw-bold mt-1 d-block text-dark">Blog Posts, Projects, Services</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Manual Sitemap Regeneration</h6>
                        <p class="text-muted small mb-0">Re-scans all static pages and database models to construct a new XML sitemap index immediately.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.seo.sitemaps.generate') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: var(--button-background, #b19356); border-color: var(--button-background, #b19356);">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> Regenerate XML
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sitemap Settings guidelines card -->
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0 fw-bold">Search Index Guidelines</h5>
            </div>
            <div class="card-body p-4 text-muted small">
                <p class="mb-2"><strong>Tip:</strong> Search engines like Google and Bing check your sitemap daily. By saving your sitemap to the public directory, web servers serve the XML statically, minimizing database queries and maximizing loading speeds.</p>
                <p class="mb-0">Ensure that your robots.txt file references your sitemap correctly to assist crawlers in finding new site paths automatically.</p>
            </div>
        </div>

    </div>
</div>
@endsection
