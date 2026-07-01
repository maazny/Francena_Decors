@extends('admin.layouts.app')

@section('title', 'Create Testimonial')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Create Testimonial</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Client Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="client_name" class="form-label">
                                Client Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('client_name') is-invalid @enderror"
                                id="client_name" name="client_name"
                                value="{{ old('client_name', $testimonial->client_name) }}"
                                placeholder="e.g., John Smith" required>
                            @error('client_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="client_company" class="form-label">Company</label>
                                <input type="text" class="form-control @error('client_company') is-invalid @enderror"
                                    id="client_company" name="client_company"
                                    value="{{ old('client_company', $testimonial->client_company) }}"
                                    placeholder="e.g., ABC Corporation">
                                @error('client_company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="client_designation" class="form-label">Designation</label>
                                <input type="text" class="form-control @error('client_designation') is-invalid @enderror"
                                    id="client_designation" name="client_designation"
                                    value="{{ old('client_designation', $testimonial->client_designation) }}"
                                    placeholder="e.g., CEO, Manager">
                                @error('client_designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location"
                                    value="{{ old('location', $testimonial->location) }}"
                                    placeholder="e.g., New York, USA">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="rating" class="form-label">
                                    Rating <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('rating') is-invalid @enderror"
                                    id="rating" name="rating" required>
                                    <option value="">-- Select Rating --</option>
                                    <option value="5" @selected(old('rating', $testimonial->rating) == 5)>5 Stars ★★★★★</option>
                                    <option value="4" @selected(old('rating', $testimonial->rating) == 4)>4 Stars ★★★★☆</option>
                                    <option value="3" @selected(old('rating', $testimonial->rating) == 3)>3 Stars ★★★☆☆</option>
                                    <option value="2" @selected(old('rating', $testimonial->rating) == 2)>2 Stars ★★☆☆☆</option>
                                    <option value="1" @selected(old('rating', $testimonial->rating) == 1)>1 Star ★☆☆☆☆</option>
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Testimonial Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $testimonial->title) }}"
                                placeholder="e.g., Excellent Service!">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Short testimonial headline (optional)</small>
                        </div>

                        <div class="mb-4">
                            <label for="testimonial" class="form-label">
                                Testimonial <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('testimonial') is-invalid @enderror"
                                id="testimonial" name="testimonial" rows="6"
                                placeholder="The client's testimonial message..."
                                required>{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                            @error('testimonial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Media & Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="video_url" class="form-label">Video URL</label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror"
                                id="video_url" name="video_url"
                                value="{{ old('video_url', $testimonial->video_url) }}"
                                placeholder="https://example.com/video.mp4">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="youtube_url" class="form-label">YouTube URL</label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                                id="youtube_url" name="youtube_url"
                                value="{{ old('youtube_url', $testimonial->youtube_url) }}"
                                placeholder="https://youtube.com/watch?v=...">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="project_id" class="form-label">Related Project</label>
                            <select class="form-select @error('project_id') is-invalid @enderror"
                                id="project_id" name="project_id">
                                <option value="">-- Select Project --</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        @selected(old('project_id', $testimonial->project_id) == $project->id)>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">SEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="seo_title" class="form-label">SEO Title</label>
                            <input type="text" class="form-control @error('seo_title') is-invalid @enderror"
                                id="seo_title" name="seo_title"
                                value="{{ old('seo_title', $testimonial->seo_title) }}"
                                placeholder="Meta title" maxlength="191">
                            @error('seo_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 191 characters</small>
                        </div>

                        <div class="mb-4">
                            <label for="seo_description" class="form-label">SEO Description</label>
                            <textarea class="form-control @error('seo_description') is-invalid @enderror"
                                id="seo_description" name="seo_description" rows="3"
                                placeholder="Meta description" maxlength="500">{{ old('seo_description', $testimonial->seo_description) }}</textarea>
                            @error('seo_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 500 characters</small>
                        </div>

                        <div class="mb-4">
                            <label for="seo_keywords" class="form-label">SEO Keywords</label>
                            <input type="text" class="form-control @error('seo_keywords') is-invalid @enderror"
                                id="seo_keywords" name="seo_keywords"
                                value="{{ old('seo_keywords', $testimonial->seo_keywords) }}"
                                placeholder="Comma separated keywords" maxlength="500">
                            @error('seo_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Max 500 characters</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Publish Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                                <option value="">-- Select Status --</option>
                                <option value="draft" @selected(old('status', $testimonial->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $testimonial->status) === 'published')>Published</option>
                                <option value="archived" @selected(old('status', $testimonial->status) === 'archived')>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="display_order" class="form-label">
                                Display Order <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('display_order') is-invalid @enderror"
                                id="display_order" name="display_order"
                                value="{{ old('display_order', $testimonial->display_order) }}"
                                placeholder="0" min="0" max="9999" required>
                            @error('display_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="featured"
                                name="featured" value="1"
                                @checked(old('featured', $testimonial->featured))>
                            <label class="form-check-label" for="featured">
                                Featured Testimonial
                            </label>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="homepage_featured"
                                name="homepage_featured" value="1"
                                @checked(old('homepage_featured', $testimonial->homepage_featured))>
                            <label class="form-check-label" for="homepage_featured">
                                Homepage Featured
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="testimonial_category_id" class="form-label">Category</label>
                            <select class="form-select @error('testimonial_category_id') is-invalid @enderror"
                                id="testimonial_category_id" name="testimonial_category_id">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected(old('testimonial_category_id', $testimonial->testimonial_category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('testimonial_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Client Media</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="client_photo_id" class="form-label">Client Photo</label>
                            <select class="form-select @error('client_photo_id') is-invalid @enderror"
                                id="client_photo_id" name="client_photo_id">
                                <option value="">-- Select Photo --</option>
                                @foreach ($imageOptions as $image)
                                    <option value="{{ $image->id }}"
                                        @selected(old('client_photo_id', $testimonial->client_photo_id) == $image->id)>
                                        {{ $image->title ?? $image->original_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_photo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Client's profile photo</small>
                        </div>

                        <div class="mb-4">
                            <label for="client_logo_id" class="form-label">Company Logo</label>
                            <select class="form-select @error('client_logo_id') is-invalid @enderror"
                                id="client_logo_id" name="client_logo_id">
                                <option value="">-- Select Logo --</option>
                                @foreach ($imageOptions as $image)
                                    <option value="{{ $image->id }}"
                                        @selected(old('client_logo_id', $testimonial->client_logo_id) == $image->id)>
                                        {{ $image->title ?? $image->original_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_logo_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Client's company logo</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-bottom bg-white pt-3 pb-3 border-top">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Testimonial
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
