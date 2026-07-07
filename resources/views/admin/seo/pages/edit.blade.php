@extends('admin.layouts.app')

@section('title', 'Edit Page SEO Overrides')
@section('page-title', 'Edit Page SEO Overrides')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        
        <!-- Live Google Snippet Preview Simulator -->
        <div class="card border-0 shadow-sm mb-4 bg-white">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0 fw-bold"><i class="fa-brands fa-google text-primary me-2"></i> Real-time Google Search Result Simulator</h5>
            </div>
            <div class="card-body p-4">
                <div class="google-snippet-preview p-3 border rounded-3 bg-light" style="max-width: 600px; font-family: arial, sans-serif;">
                    <div class="preview-url text-success small mb-1" id="snippet-url" style="color: #006621; font-size: 14px;">
                        {{ url($page->slug) }}
                    </div>
                    <h3 class="preview-title h5 mb-1" id="snippet-title" style="color: #1a0dab; font-family: arial, sans-serif; font-size: 20px; font-weight: normal; line-height: 1.3; cursor: pointer; text-decoration: none;">
                        {{ $page->title ?: 'Page Title Override Fallback' }}
                    </h3>
                    <div class="preview-desc text-muted" id="snippet-desc" style="color: #545454; font-size: 14px; line-height: 1.4;">
                        {{ $page->meta_description ?: 'Configure a meta description override below to customize this snippet text fallback on Google Search results.' }}
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.seo.pages.update', $page->id) }}">
            @csrf
            @method('PUT')
            
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
                                <option value="static" {{ $page->page_type->value === 'static' ? 'selected' : '' }}>Static Page</option>
                                <option value="dynamic" {{ $page->page_type->value === 'dynamic' ? 'selected' : '' }}>Dynamic Page</option>
                                <option value="module" {{ $page->page_type->value === 'module' ? 'selected' : '' }}>Module Target</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="page_key" class="form-label fw-semibold small text-muted uppercase">Page Key / Unique Label</label>
                            <input type="text" name="page_key" id="page_key" class="form-control" value="{{ old('page_key', $page->page_key) }}" required>
                        </div>
                        <div class="col-12">
                            <label for="slug" class="form-label fw-semibold small text-muted uppercase">Slug Path Location</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required>
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
                            <input type="text" name="title" id="title" class="form-control char-counted" data-max="60" value="{{ old('title', $page->title) }}" placeholder="Page-specific tab title">
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 60 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_description" class="form-label fw-semibold small text-muted uppercase">Meta Description Override</label>
                            <textarea name="meta_description" id="meta_description" class="form-control char-counted" data-max="160" rows="3" placeholder="Page-specific search description snippet...">{{ old('meta_description', $page->meta_description) }}</textarea>
                            <div class="form-text small text-muted">Recommendation: <span class="counter">0</span> / 160 characters</div>
                        </div>
                        <div class="col-12">
                            <label for="meta_keywords" class="form-label fw-semibold small text-muted uppercase">Meta Keywords Override</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="canonical_url" class="form-label fw-semibold small text-muted uppercase">Canonical URL Override</label>
                            <input type="url" name="canonical_url" id="canonical_url" class="form-control" value="{{ old('canonical_url', $page->canonical_url) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="robots" class="form-label fw-semibold small text-muted uppercase">Robots Config Override</label>
                            <input type="text" name="robots" id="robots" class="form-control" value="{{ old('robots', $page->robots) }}">
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
                            <input type="text" name="og_title" id="og_title" class="form-control" value="{{ old('og_title', $page->og_title) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="og_image" class="form-label fw-semibold small text-muted uppercase">OG Image (URL)</label>
                            <input type="text" name="og_image" id="og_image" class="form-control" value="{{ old('og_image', $page->og_image) }}">
                        </div>
                        <div class="col-12">
                            <label for="og_description" class="form-label fw-semibold small text-muted uppercase">OG Description</label>
                            <textarea name="og_description" id="og_description" class="form-control" rows="2">{{ old('og_description', $page->og_description) }}</textarea>
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
                            <textarea name="custom_head_scripts" id="custom_head_scripts" class="form-control font-monospace" rows="4">{{ old('custom_head_scripts', $page->custom_head_scripts) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="custom_footer_scripts" class="form-label fw-semibold small text-muted uppercase">Footer Script Block</label>
                            <textarea name="custom_footer_scripts" id="custom_footer_scripts" class="form-control font-monospace" rows="4">{{ old('custom_footer_scripts', $page->custom_footer_scripts) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.seo.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Update Configuration
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
    // Snippet simulator updates
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('meta_description');
    const slugInput = document.getElementById('slug');

    const previewTitle = document.getElementById('snippet-title');
    const previewDesc = document.getElementById('snippet-desc');
    const previewUrl = document.getElementById('snippet-url');

    const baseUrl = "{{ url('/') }}";

    const updatePreview = () => {
        previewTitle.textContent = titleInput.value || 'Page Title Override Fallback';
        previewDesc.textContent = descInput.value || 'Configure a meta description override below to customize this snippet text fallback on Google Search results.';
        
        const rawSlug = slugInput.value || '/';
        const formattedSlug = rawSlug.startsWith('/') ? rawSlug : '/' + rawSlug;
        previewUrl.textContent = baseUrl + formattedSlug;
    };

    titleInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    slugInput.addEventListener('input', updatePreview);

    // Initialize character counters
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
