@extends('admin.layouts.app')

@section('title', 'New Campaign')
@section('page-title', 'New Campaign')
@section('page-description', 'Create a new newsletter campaign, subject lines, and schedule options.')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <form method="POST" action="{{ route('admin.newsletter.campaigns.store') }}">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title h6 mb-0">Campaign Info & Settings</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Campaign Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. July Summer Trends Announcement">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="Auto-generated if left empty">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="subject" class="form-label">Subject Line <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required placeholder="e.g. Transform your space this July with these trends!">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="preview_text" class="form-label">Preview Text / Snippet</label>
                            <input type="text" name="preview_text" id="preview_text" class="form-control @error('preview_text') is-invalid @enderror" value="{{ old('preview_text') }}" placeholder="Short summary displayed under subject line in inbox...">
                            @error('preview_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="campaign_type" class="form-label">Campaign Type <span class="text-danger">*</span></label>
                            <select name="campaign_type" id="campaign_type" class="form-select @error('campaign_type') is-invalid @enderror" required>
                                @foreach(\App\Enums\CampaignType::cases() as $typeOption)
                                    <option value="{{ $typeOption->value }}" {{ old('campaign_type', 'newsletter') == $typeOption->value ? 'selected' : '' }}>
                                        {{ ucfirst($typeOption->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('campaign_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sender_name" class="form-label">Sender Name <span class="text-danger">*</span></label>
                            <input type="text" name="sender_name" id="sender_name" class="form-control @error('sender_name') is-invalid @enderror" value="{{ old('sender_name', 'Fancy Decorators') }}" required>
                            @error('sender_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sender_email" class="form-label">Sender Email <span class="text-danger">*</span></label>
                            <input type="email" name="sender_email" id="sender_email" class="form-control @error('sender_email') is-invalid @enderror" value="{{ old('sender_email', 'newsletter@fancydecorators.test') }}" required>
                            @error('sender_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title h6 mb-0">Email Content Layout</h5>
                    <div>
                        <select id="template_id" name="template_id" class="form-select form-select-sm">
                            <option value="">Choose a Template Layout</option>
                            @foreach($templates as $tpl)
                                <option value="{{ $tpl->id }}" {{ old('template_id') == $tpl->id ? 'selected' : '' }}>
                                    {{ $tpl->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="html_content" class="form-label mb-0">HTML Content <span class="text-danger">*</span></label>
                            <span class="text-muted small">Placeholders: <code>@{{name}}</code>, <code>@{{email}}</code>, <code>@{{unsubscribe_url}}</code></span>
                        </div>
                        <textarea name="html_content" id="html_content" class="form-control rich-editor @error('html_content') is-invalid @enderror" rows="15">{{ old('html_content', '<h1>Fancy Decorators Newsletter</h1><p>Hello {{name}},</p><p>We are excited to share our latest design updates with you...</p>') }}</textarea>
                        @error('html_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="plain_text" class="form-label">Plain Text Backup Version</label>
                        <textarea name="plain_text" id="plain_text" class="form-control @error('plain_text') is-invalid @enderror" rows="4" placeholder="Optional backup content for text-only clients...">{{ old('plain_text') }}</textarea>
                        @error('plain_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title h6 mb-0">Delivery Scheduling</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-0">
                                <label for="scheduled_at" class="form-label">Schedule Dispatch (Optional)</label>
                                <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
                                <div class="form-text small text-muted">Leave empty to dispatch manually whenever you are ready. Scheduled campaigns require background cron execution.</div>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title h6 mb-0">UTM Analytics Parameters</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label for="analytics_utm_source" class="form-label small">UTM Source</label>
                                    <input type="text" name="analytics_utm_source" id="analytics_utm_source" class="form-control form-control-sm" value="{{ old('analytics_utm_source', 'newsletter') }}">
                                </div>
                                <div class="col-6">
                                    <label for="analytics_utm_medium" class="form-label small">UTM Medium</label>
                                    <input type="text" name="analytics_utm_medium" id="analytics_utm_medium" class="form-control form-control-sm" value="{{ old('analytics_utm_medium', 'email') }}">
                                </div>
                                <div class="col-12">
                                    <label for="analytics_utm_campaign" class="form-label small">UTM Campaign Name</label>
                                    <input type="text" name="analytics_utm_campaign" id="analytics_utm_campaign" class="form-control form-control-sm" value="{{ old('analytics_utm_campaign') }}" placeholder="e.g. july-newsletter-promo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-5">
                <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save & Continue</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const templates = @json($templates);
        const templateSelect = document.getElementById('template_id');
        const subjectInput = document.getElementById('subject');

        if (templateSelect) {
            templateSelect.addEventListener('change', () => {
                const templateId = templateSelect.value;
                const template = templates.find(t => t.id == templateId);

                if (template) {
                    // Update subject if empty or placeholder
                    if (!subjectInput.value || subjectInput.value === subjectInput.placeholder) {
                        subjectInput.value = template.subject || '';
                    }

                    // Update CKEditor content
                    const editor = document.getElementById('html_content')._ckEditor;
                    if (editor) {
                        editor.setData(template.html_content);
                    }
                }
            });
        }
    });
</script>
@endsection
