@extends('admin.layouts.app')

@section('title', 'Add JSON-LD Schema')
@section('page-title', 'Configure Structured Data Schema')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.seo.structured-data.store') }}">
            @csrf

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-4 fw-bold">Schema Settings</h5>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 mb-3 small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="seo_page_id" class="form-label small fw-semibold text-muted uppercase">Target Page Override Mapping</label>
                        <select name="seo_page_id" id="seo_page_id" class="form-select" required>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->page_key ?: 'Page' }} ({{ $page->slug }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="schema_type" class="form-label small fw-semibold text-muted uppercase">Structured Schema Type</label>
                        <select name="type" id="schema_type" class="form-select" required>
                            <option value="organization">Organization</option>
                            <option value="local_business">Local Business</option>
                            <option value="website">WebSite</option>
                            <option value="webpage">WebPage</option>
                            <option value="breadcrumb">Breadcrumb List</option>
                            <option value="article">Article</option>
                            <option value="faq">FAQ Page</option>
                            <option value="service">Service</option>
                            <option value="contact">Contact Page</option>
                            <option value="gallery">Image Gallery</option>
                            <option value="person">Person Profile</option>
                            <option value="custom">Custom JSON-LD</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="custom_json_ld" class="form-label small fw-semibold text-muted uppercase">Custom JSON-LD Script</label>
                    <textarea name="custom_json_ld" id="custom_json_ld" class="form-control font-monospace" rows="8" placeholder='{&#10;  "@context": "https://schema.org",&#10;  "@type": "WebSite",&#10;  "name": "Fancy Decorators"&#10;}' required></textarea>
                    
                    <!-- Live JSON Validation Alert -->
                    <div class="mt-2 d-none" id="json-validation-msg">
                        <span class="badge" id="json-validation-badge">Valid JSON</span>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-muted small" for="is_active">
                        Activate schema script inclusion immediately
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.seo.structured-data.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Save Schema Block
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
    const textarea = document.getElementById('custom_json_ld');
    const msg = document.getElementById('json-validation-msg');
    const badge = document.getElementById('json-validation-badge');

    textarea.addEventListener('input', () => {
        const val = textarea.value.trim();
        if (!val) {
            msg.classList.add('d-none');
            return;
        }

        msg.classList.remove('d-none');
        try {
            JSON.parse(val);
            badge.className = 'badge bg-success';
            badge.textContent = 'JSON Structure Validated';
        } catch (e) {
            badge.className = 'badge bg-danger';
            badge.textContent = 'JSON Syntax Error: ' + e.message;
        }
    });
});
</script>
@endpush
@endonce
