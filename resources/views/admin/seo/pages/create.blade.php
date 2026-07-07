@extends('admin.layouts.app')

@section('title', 'Configure Page SEO Overrides')
@section('page-title', 'Configure Page SEO Overrides')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <form method="POST" action="{{ route('admin.seo.pages.store') }}">
            @csrf
            
            <!-- Type & Mapping Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Page Context Definition</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="page_type" class="form-label fw-semibold small text-muted uppercase">Page Type</label>
                            <select name="page_type" id="page_type" class="form-select" required>
                                <option value="static">Static Page</option>
                                <option value="dynamic">Dynamic Page</option>
                                <option value="module">Module Target</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="page_key" class="form-label fw-semibold small text-muted uppercase">Page Key / Unique Label</label>
                            <input type="text" name="page_key" id="page_key" class="form-control" placeholder="e.g. contact_page" required>
                        </div>
                        <div class="col-12">
                            <label for="slug" class="form-label fw-semibold small text-muted uppercase">Slug Path Location</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="e.g. /contact-us" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta tag override details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Meta Content Overrides</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="title" class="form-label fw-semibold small text-muted uppercase">Meta Title Override</label>
                            <input type="text" name="title" id="title" class="form-control char-counted" data-max="60" placeholder="Page-specific tab title">
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 60 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_description" class="form-label fw-semibold small text-muted uppercase">Meta Description Override</label>
                            <textarea name="meta_description" id="meta_description" class="form-control char-counted" data-max="160" rows="3" placeholder="Page-specific search description snippet..."></textarea>
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 160 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label fw-semibold small text-muted uppercase">Meta Keywords Override</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="page, custom, terms">
                        </div>
                        <div class="col-md-6">
                            <label for="canonical_url" class="form-label fw-semibold small text-muted uppercase">Canonical URL Override</label>
                            <input type="url" name="canonical_url" id="canonical_url" class="form-control" placeholder="https://example.com/canonical-path">
                        </div>
                        <div class="col-md-6">
                            <label for="robots" class="form-label fw-semibold small text-muted uppercase">Robots Config Override</label>
                            <input type="text" name="robots" id="robots" class="form-control" value="index, follow">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Social Sharing Graphs Overrides</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="og_title" class="form-label fw-semibold small text-muted uppercase">OG Title</label>
                            <input type="text" name="og_title" id="og_title" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="og_image" class="form-label fw-semibold small text-muted uppercase">OG Image (URL)</label>
                            <input type="text" name="og_image" id="og_image" class="form-control" placeholder="https://example.com/images/share.jpg">
                        </div>
                        <div class="col-12">
                            <label for="og_description" class="form-label fw-semibold small text-muted uppercase">OG Description</label>
                            <textarea name="og_description" id="og_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Injection Script Blocks -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0 fw-bold">Custom Analytics / Target Script Injections</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="custom_head_scripts" class="form-label fw-semibold small text-muted uppercase">Header Script Block</label>
                            <textarea name="custom_head_scripts" id="custom_head_scripts" class="form-control font-monospace" rows="4" placeholder="&lt;script&gt; ... &lt;/script&gt;"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="custom_footer_scripts" class="form-label fw-semibold small text-muted uppercase">Footer Script Block</label>
                            <textarea name="custom_footer_scripts" id="custom_footer_scripts" class="form-control font-monospace" rows="4" placeholder="&lt;script&gt; ... &lt;/script&gt;"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.seo.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Save Configuration
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
