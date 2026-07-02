@extends('admin.layouts.app')

@section('title', 'New Blog Post')
@section('page-title', 'New Blog Post')
@section('page-description', 'Create and draft a new blog article.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.blog-posts.store') }}" novalidate>
      @csrf
      <div class="row g-4">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Category</label>
          <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">Select category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
          </select>
          @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Slug</label>
          <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="Auto-generated if left empty" value="{{ old('slug') }}">
          @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Author</label>
          <select name="author_id" class="form-select @error('author_id') is-invalid @enderror">
            <option value="">Select author</option>
            @foreach($authors as $author)
              <option value="{{ $author->id }}" {{ old('author_id', auth()->id()) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
            @endforeach
          </select>
          @error('author_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Excerpt</label>
          <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="2" placeholder="Brief summary of the article">{{ old('excerpt') }}</textarea>
          @error('excerpt')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Content</label>
          <textarea name="content" class="form-control rich-editor @error('content') is-invalid @enderror" rows="10">{{ old('content') }}</textarea>
          @error('content')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Featured Image</label>
          <select name="featured_image_id" class="form-select @error('featured_image_id') is-invalid @enderror">
            <option value="">Select image</option>
            @foreach($imageOptions as $image)
              <option value="{{ $image->id }}" {{ old('featured_image_id') == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
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
              <option value="{{ $image->id }}" {{ old('banner_image_id') == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
            @endforeach
          </select>
          @error('banner_image_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Tags</label>
          <select name="tags[]" class="form-select @error('tags') is-invalid @enderror" multiple style="height: 120px;">
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
          </select>
          <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple tags.</div>
          @error('tags')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-12 mb-3">
              <label class="form-label fw-semibold">Display Order</label>
              <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $post->display_order) }}">
              @error('display_order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Published At</label>
              <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
              <div class="form-text">Leave empty to publish immediately (or when status is set to active).</div>
              @error('published_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="d-flex gap-4">
            <div class="form-check">
              <input type="hidden" name="status" value="0">
              <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $post->status) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold">Published</label>
            </div>
            <div class="form-check">
              <input type="hidden" name="is_featured" value="0">
              <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold">Featured Article</label>
            </div>
            <div class="form-check">
              <input type="hidden" name="is_homepage_featured" value="0">
              <input class="form-check-input" type="checkbox" name="is_homepage_featured" value="1" {{ old('is_homepage_featured', $post->is_homepage_featured) ? 'checked' : '' }}>
              <label class="form-check-label fw-semibold">Homepage Featured</label>
            </div>
          </div>
        </div>

        <div class="col-12 mt-4">
          <h4 class="h6 text-uppercase fw-bold text-muted border-bottom pb-2">SEO Settings</h4>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">SEO Title</label>
          <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title') }}">
          @error('seo_title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">SEO Keywords</label>
          <input type="text" name="seo_keywords" class="form-control @error('seo_keywords') is-invalid @enderror" value="{{ old('seo_keywords') }}">
          @error('seo_keywords')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">SEO Description</label>
          <textarea name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="2">{{ old('seo_description') }}</textarea>
          @error('seo_description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Create Post</button>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
