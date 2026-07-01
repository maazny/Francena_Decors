@extends('admin.layouts.app')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('page-description', 'Update the project details and publishing options.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.projects.update', $project) }}" novalidate>
      @csrf
      @method('PUT')
      <div class="row g-4">
        <div class="col-md-8">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $project->title) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Category</label>
          <select name="project_category_id" class="form-select">
            <option value="">Select category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('project_category_id', $project->project_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" value="{{ old('slug', $project->slug) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="draft" {{ old('status', $project->status) == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $project->status) == 'published' ? 'selected' : '' }}>Published</option>
            <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $project->display_order) }}">
        </div>
        <div class="col-12">
          <label class="form-label">Short Description</label>
          <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $project->short_description) }}</textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="8">{{ old('description', $project->description) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Client Name</label>
          <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $project->client_name) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Client Company</label>
          <input type="text" name="client_company" class="form-control" value="{{ old('client_company', $project->client_company) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Budget</label>
          <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget', $project->budget) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Currency</label>
          <input type="text" name="currency" class="form-control" value="{{ old('currency', $project->currency) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Project Manager</label>
          <input type="text" name="project_manager" class="form-control" value="{{ old('project_manager', $project->project_manager) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Completion %</label>
          <input type="number" name="completion_percentage" class="form-control" value="{{ old('completion_percentage', $project->completion_percentage) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" value="{{ old('location', $project->location) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Latitude</label>
          <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $project->latitude) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Longitude</label>
          <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $project->longitude) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Cover Image</label>
          <select name="cover_image_id" class="form-select">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('cover_image_id', $project->cover_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Banner Image</label>
          <select name="banner_image_id" class="form-select">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('banner_image_id', $project->banner_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Project Duration</label>
          <input type="text" name="project_duration" class="form-control" value="{{ old('project_duration', $project->project_duration) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Project Area</label>
          <input type="text" name="project_area" class="form-control" value="{{ old('project_area', $project->project_area) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Video URL</label>
          <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $project->video_url) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">YouTube URL</label>
          <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $project->youtube_url) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">SEO Title</label>
          <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $project->seo_title) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">SEO Keywords</label>
          <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $project->seo_keywords) }}">
        </div>
        <div class="col-12">
          <label class="form-label">SEO Description</label>
          <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $project->seo_description) }}</textarea>
        </div>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}>
            <label class="form-check-label">Featured</label>
          </div>
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="homepage_featured" value="1" {{ old('homepage_featured', $project->homepage_featured) ? 'checked' : '' }}>
            <label class="form-check-label">Homepage Featured</label>
          </div>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Project</button>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
