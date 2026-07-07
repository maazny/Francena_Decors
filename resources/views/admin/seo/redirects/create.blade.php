@extends('admin.layouts.app')

@section('title', 'Add Redirect Rule')
@section('page-title', 'Create URL Redirection')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.seo.redirects.store') }}">
            @csrf

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-4 fw-bold">Redirection Rules Parameters</h5>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 mb-3 small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="source_url" class="form-label small fw-semibold text-muted uppercase">Source Request Path</label>
                    <input type="text" name="source_url" id="source_url" class="form-control" value="{{ old('source_url') }}" placeholder="e.g. /old-services or /old-portfolio/*" required>
                    <div class="form-text small text-muted">Include wildcards using an asterisk <code>*</code> (e.g. <code>/old-blog/*</code>).</div>
                </div>

                <div class="mb-3">
                    <label for="target_url" class="form-label small fw-semibold text-muted uppercase">Redirection Destination URL</label>
                    <input type="text" name="target_url" id="target_url" class="form-control" value="{{ old('target_url') }}" placeholder="e.g. /services or https://external-domain.com/blog" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label small fw-semibold text-muted uppercase">HTTP Code</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="301" {{ old('type') == '301' ? 'selected' : '' }}>301 - Permanent Redirect</option>
                            <option value="302" {{ old('type') == '302' ? 'selected' : '' }}>302 - Temporary Redirect</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_wildcard" id="is_wildcard" value="1" {{ old('is_wildcard') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-muted small" for="is_wildcard">
                                Enable Wildcard Matching
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-muted small" for="is_active">
                        Activate this redirect rule immediately
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.seo.redirects.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Save Redirect Rule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
