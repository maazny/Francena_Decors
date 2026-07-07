@extends('admin.layouts.app')

@section('title', 'Create Template')
@section('page-title', 'Create Template')
@section('page-description', 'Design a reusable template layout for emails using HTML and personalization placeholders.')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0">Template Details</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.newsletter.templates.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Monthly Newsletter Layout">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Default Subject</label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="e.g. Latest Updates from Fancy Decorators">
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="html_content" class="form-label mb-0">HTML Content <span class="text-danger">*</span></label>
                            <span class="text-muted small">Placeholders: <code>@{{name}}</code>, <code>@{{email}}</code>, <code>@{{unsubscribe_url}}</code></span>
                        </div>
                        <textarea name="html_content" id="html_content" class="form-control rich-editor @error('html_content') is-invalid @enderror" rows="15">{{ old('html_content', '<h1>Fancy Decorators</h1><p>Hello {{name}},</p><p>Add your newsletter content here...</p><hr><p>To stop receiving emails, click here: <a href="{{unsubscribe_url}}">Unsubscribe</a></p>') }}</textarea>
                        @error('html_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="plain_content" class="form-label">Plain Text Content</label>
                        <textarea name="plain_content" id="plain_content" class="form-control @error('plain_content') is-invalid @enderror" rows="5" placeholder="Alternative plain text format for clients that do not render HTML...">{{ old('plain_content') }}</textarea>
                        @error('plain_content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.newsletter.templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
