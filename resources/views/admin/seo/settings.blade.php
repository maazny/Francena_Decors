@extends('admin.layouts.app')

@section('title', 'Global SEO Settings')
@section('page-title', 'Global SEO Defaults')
@section('page-description', 'Configure fallback metadata, verification codes, Open Graph assets, and robots.txt rules.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.seo.settings.update') }}">
            @csrf
            @method('PUT')

            <!-- Global Defaults Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Site & Metadata Defaults</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="site_name" class="form-label fw-semibold small text-muted uppercase">Site Name</label>
                            <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" placeholder="e.g. Fancy Decorators">
                        </div>
                        <div class="col-md-6">
                            <label for="meta_title" class="form-label fw-semibold small text-muted uppercase">Default Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control char-counted" data-max="60" value="{{ old('meta_title', $settings->meta_title) }}" placeholder="Default browser tab title">
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 60 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_description" class="form-label fw-semibold small text-muted uppercase">Default Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control char-counted" data-max="160" rows="3" placeholder="Search result summary snippet fallback...">{{ old('meta_description', $settings->meta_description) }}</textarea>
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 160 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label fw-semibold small text-muted uppercase">Default Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings->meta_keywords) }}" placeholder="comma, separated, terms">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Default Open Graph & Twitter Branding</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="og_title" class="form-label fw-semibold small text-muted uppercase">OG Title</label>
                            <input type="text" name="og_title" id="og_title" class="form-control" value="{{ old('og_title', $settings->og_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="og_image" class="form-label fw-semibold small text-muted uppercase">OG Image (URL)</label>
                            <input type="text" name="og_image" id="og_image" class="form-control" value="{{ old('og_image', $settings->og_image) }}" placeholder="https://example.com/logo-og.jpg">
                        </div>
                        <div class="col-12">
                            <label for="og_description" class="form-label fw-semibold small text-muted uppercase">OG Description</label>
                            <textarea name="og_description" id="og_description" class="form-control" rows="2">{{ old('og_description', $settings->og_description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Webmaster Verification Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Webmaster verification rules</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="robots_txt_rules" class="form-label fw-semibold small text-muted uppercase">robots.txt configuration</label>
                            <textarea name="robots_txt_rules" id="robots_txt_rules" class="form-control font-monospace" rows="5" placeholder="User-agent: *&#10;Disallow: /admin/">{{ old('robots_txt_rules', $settings->robots_txt_rules) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-5">
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Save Defaults
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.char-counted').forEach(input => {
        const counter = input.nextElementSibling.querySelector('.counter');
        const max = parseInt(input.getAttribute('data-max'));

        const updateCounter = () => {
            const len = input.value.length;
            counter.textContent = len;
            if (len > max) {
                counter.classList.add('text-danger');
            } else {
                counter.classList.remove('text-danger');
            }
        };

        input.addEventListener('input', updateCounter);
        updateCounter(); // Initialize
    });
});
</script>
@endpush
@endonce
