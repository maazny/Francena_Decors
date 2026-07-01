@extends('admin.layouts.app')

@section('title', 'Edit Project Category')
@section('page-title', 'Edit Project Category')
@section('page-description', 'Update the project category details and SEO settings.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.project-categories.update', $projectCategory) }}" novalidate>
      @csrf
      @method('PUT')
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $projectCategory->name) }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" value="{{ old('slug', $projectCategory->slug) }}">
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4">{{ old('description', $projectCategory->description) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Icon</label>
          <input type="text" name="icon" class="form-control" value="{{ old('icon', $projectCategory->icon) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $projectCategory->display_order) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Featured Image</label>
          <select name="featured_image_id" class="form-select">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('featured_image_id', $projectCategory->featured_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Banner Image</label>
          <select name="banner_image_id" class="form-select">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('banner_image_id', $projectCategory->banner_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">SEO Title</label>
          <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $projectCategory->seo_title) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">SEO Keywords</label>
          <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $projectCategory->seo_keywords) }}">
        </div>
        <div class="col-12">
          <label class="form-label">SEO Description</label>
          <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $projectCategory->seo_description) }}</textarea>
        </div>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $projectCategory->status) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
          </div>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('admin.project-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
