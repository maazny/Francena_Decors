@extends('admin.layouts.app')

@section('title', 'Edit Blog Category')
@section('page-title', 'Edit Blog Category')
@section('page-description', 'Update the details and SEO settings of this category.')

@section('content')
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.blog-categories.update', $category) }}" novalidate>
      @csrf
      @method('PUT')
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Slug</label>
          <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}">
          @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Short Description</label>
          <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="2">{{ old('short_description', $category->short_description) }}</textarea>
          @error('short_description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" class="form-control rich-editor @error('description') is-invalid @enderror" rows="5">{{ old('description', $category->description) }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Featured Image</label>
          <select name="featured_image_id" class="form-select @error('featured_image_id') is-invalid @enderror">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('featured_image_id', $category->featured_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
          @error('featured_image_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Banner Image</label>
          <select name="banner_image_id" class="form-select @error('banner_image_id') is-invalid @enderror">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('banner_image_id', $category->banner_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
          @error('banner_image_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Display Order</label>
          <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $category->display_order) }}">
          @error('display_order')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Status</label>
          <div class="form-check mt-2">
            <input type="hidden" name="status" value="0">
            <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $category->status) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
          </div>
        </div>

        <div class="col-12 mt-4">
          <h4 class="h6 text-uppercase fw-bold text-muted border-bottom pb-2">SEO Settings</h4>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">SEO Title</label>
          <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $category->seo_title) }}">
          @error('seo_title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">SEO Keywords</label>
          <input type="text" name="seo_keywords" class="form-control @error('seo_keywords') is-invalid @enderror" value="{{ old('seo_keywords', $category->seo_keywords) }}">
          @error('seo_keywords')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">SEO Description</label>
          <textarea name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="2">{{ old('seo_description', $category->seo_description) }}</textarea>
          @error('seo_description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
