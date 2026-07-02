@extends('admin.layouts.app')

@section('title', 'Edit Blog Post')
@section('page-title', 'Edit Blog Post')
@section('page-description', 'Update the details, publishing options, and SEO tags.')

@section('content')
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card shadow-sm">
  <div class="card-body p-4">
    <ul class="nav nav-tabs mb-4" id="blogPostTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active fw-semibold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General Information</button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-semibold" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">Media & Gallery</button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-semibold" id="publish-tab" data-bs-toggle="tab" data-bs-target="#publish" type="button" role="tab">Publish Options</button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-semibold" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">SEO Settings</button>
      </li>
    </ul>

    <form method="POST" action="{{ route('admin.blog-posts.update', $post) }}" novalidate>
      @csrf
      @method('PUT')
      <div class="tab-content" id="blogPostTabsContent">
        <!-- GENERAL INFORMATION TAB -->
        <div class="tab-pane fade show active" id="general" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Category</label>
              <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                <option value="">Select category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $post->slug) }}">
              @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Author</label>
              <select name="author_id" class="form-select @error('author_id') is-invalid @enderror">
                <option value="">Select author</option>
                @foreach($authors as $author)
                  <option value="{{ $author->id }}" {{ old('author_id', $post->author_id) == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                @endforeach
              </select>
              @error('author_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Excerpt</label>
              <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="2" placeholder="Brief summary of the article">{{ old('excerpt', $post->getRawOriginal('excerpt')) }}</textarea>
              @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Content</label>
              <textarea name="content" class="form-control rich-editor @error('content') is-invalid @enderror" rows="10">{{ old('content', $post->content) }}</textarea>
              @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Tags</label>
              <select name="tags[]" class="form-select @error('tags') is-invalid @enderror" multiple style="height: 100px;">
                @php($postTags = old('tags', $post->tags->pluck('id')->toArray()))
                @foreach($tags as $tag)
                  <option value="{{ $tag->id }}" {{ in_array($tag->id, $postTags) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
              </select>
              <div class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple tags.</div>
              @error('tags')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- MEDIA & GALLERY TAB -->
        <div class="tab-pane fade" id="media" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="card p-3 border shadow-sm">
                <label class="form-label fw-semibold">Featured Image</label>
                <input type="hidden" name="featured_image_id" id="featured_image_id" value="{{ old('featured_image_id', $post->featured_image_id) }}">
                <div class="d-flex align-items-center gap-3">
                  <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#featuredImageModal">Select from Media Library</button>
                  <img id="featured_image_id_preview" src="{{ $post->featuredImage ? image_url($post->featuredImage) : '' }}" class="img-thumbnail {{ $post->featuredImage ? '' : 'd-none' }}" style="max-height: 80px; max-width: 120px; object-fit: cover;">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card p-3 border shadow-sm">
                <label class="form-label fw-semibold">Banner Image</label>
                <input type="hidden" name="banner_image_id" id="banner_image_id" value="{{ old('banner_image_id', $post->banner_image_id) }}">
                <div class="d-flex align-items-center gap-3">
                  <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bannerImageModal">Select from Media Library</button>
                  <img id="banner_image_id_preview" src="{{ $post->bannerImage ? image_url($post->bannerImage) : '' }}" class="img-thumbnail {{ $post->bannerImage ? '' : 'd-none' }}" style="max-height: 80px; max-width: 120px; object-fit: cover;">
                </div>
              </div>
            </div>
            <div class="col-12 mt-4">
              <div class="card p-4 border shadow-sm">
                <h5 class="card-title h6 mb-3 fw-bold text-uppercase text-muted">Gallery Images</h5>
                <button type="button" class="btn btn-outline-primary mb-3 d-inline-block w-auto align-self-start" data-bs-toggle="modal" data-bs-target="#galleryImagePickerModal">
                  <i class="fa-solid fa-plus me-1"></i> Add Image to Gallery
                </button>
                <div id="gallery-container" class="row g-3 border rounded p-3 bg-light" style="min-height: 180px;">
                  <!-- Dynamic gallery items injected here -->
                </div>
                <div class="form-text text-muted mt-2"><i class="fa-solid fa-info-circle me-1"></i> Drag and drop cards to reorder images in the gallery.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- PUBLISH OPTIONS TAB -->
        <div class="tab-pane fade" id="publish" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Published At</label>
              <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
              <div class="form-text">Leave empty to publish immediately (or when status is set to published).</div>
              @error('published_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Reading Time (Minutes)</label>
              <input type="number" name="reading_time" class="form-control @error('reading_time') is-invalid @enderror" value="{{ old('reading_time', $post->reading_time) }}">
              @error('reading_time')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Display Order</label>
              <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $post->display_order) }}">
              @error('display_order')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Allow Comments</label>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', true) ? 'checked' : '' }}>
                <label class="form-check-label">Enable comment section for this post</label>
              </div>
            </div>
            <div class="col-12 mt-3">
              <div class="d-flex gap-4">
                <div class="form-check form-switch">
                  <input type="hidden" name="status" value="0">
                  <input class="form-check-input" type="checkbox" name="status" id="status-switch" value="1" {{ old('status', $post->status) ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="status-switch">Published / Active</label>
                </div>
                <div class="form-check form-switch">
                  <input type="hidden" name="is_featured" value="0">
                  <input class="form-check-input" type="checkbox" name="is_featured" id="featured-switch" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="featured-switch">Featured Article</label>
                </div>
                <div class="form-check form-switch">
                  <input type="hidden" name="is_homepage_featured" value="0">
                  <input class="form-check-input" type="checkbox" name="is_homepage_featured" id="homepage-featured-switch" value="1" {{ old('is_homepage_featured', $post->is_homepage_featured) ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold" for="homepage-featured-switch">Homepage Featured</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SEO SETTINGS TAB -->
        <div class="tab-pane fade" id="seo" role="tabpanel">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">SEO Title</label>
              <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $post->seo_title) }}">
              @error('seo_title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">SEO Keywords</label>
              <input type="text" name="seo_keywords" class="form-control @error('seo_keywords') is-invalid @enderror" value="{{ old('seo_keywords', $post->seo_keywords) }}">
              @error('seo_keywords')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">SEO Description</label>
              <textarea name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="3">{{ old('seo_description', $post->seo_description) }}</textarea>
              @error('seo_description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="{{ route('admin.blog-posts.preview', $post) }}" class="btn btn-outline-success" target="_blank">Preview Post</a>
        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<!-- Dummy selector inputs to receive media ID callbacks -->
<input type="hidden" id="temp_gallery_image_id">

<!-- Include existing Media Pickers -->
@include('admin.partials.media-picker-modal', ['modalId' => 'featuredImageModal', 'title' => 'Select Featured Image', 'targetInput' => 'featured_image_id', 'mediaItems' => $imageOptions, 'isImage' => true])
@include('admin.partials.media-picker-modal', ['modalId' => 'bannerImageModal', 'title' => 'Select Banner Image', 'targetInput' => 'banner_image_id', 'mediaItems' => $imageOptions, 'isImage' => true])
@include('admin.partials.media-picker-modal', ['modalId' => 'galleryImagePickerModal', 'title' => 'Select Gallery Image', 'targetInput' => 'temp_gallery_image_id', 'mediaItems' => $imageOptions, 'isImage' => true])

<style>
  .gallery-item {
    transition: transform 0.2s ease, border-color 0.2s ease;
  }
  .gallery-item.border-primary {
    border: 2px dashed #0d6efd !important;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Intercept image picking for gallery
    $('#galleryImagePickerModal').on('click', '.js-media-picker', function(e) {
      const mediaId = $(this).data('media-id');
      const mediaUrl = $(this).data('media-url');
      const mediaLabel = $(this).data('media-label');
      addGalleryImage(mediaId, mediaUrl, mediaLabel);
    });

    // Remove gallery items
    $(document).on('click', '.remove-gallery-item', function() {
      $(this).closest('.gallery-item').remove();
    });

    // Drag and drop gallery sorting
    const container = document.getElementById('gallery-container');
    let dragSource = null;

    container.addEventListener('dragstart', (e) => {
      const item = e.target.closest('.gallery-item');
      if (item) {
        dragSource = item;
        e.dataTransfer.effectAllowed = 'move';
        item.style.opacity = '0.5';
      }
    });

    container.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
    });

    container.addEventListener('dragenter', (e) => {
      const target = e.target.closest('.gallery-item');
      if (target && target !== dragSource) {
        target.classList.add('border-primary');
      }
    });

    container.addEventListener('dragleave', (e) => {
      const target = e.target.closest('.gallery-item');
      if (target) {
        target.classList.remove('border-primary');
      }
    });

    container.addEventListener('drop', (e) => {
      e.preventDefault();
      const target = e.target.closest('.gallery-item');
      if (target && target !== dragSource) {
        target.classList.remove('border-primary');
        const children = Array.from(container.children);
        const sourceIndex = children.indexOf(dragSource);
        const targetIndex = children.indexOf(target);
        if (sourceIndex < targetIndex) {
          container.insertBefore(dragSource, target.nextSibling);
        } else {
          container.insertBefore(dragSource, target);
        }
      }
    });

    container.addEventListener('dragend', (e) => {
      const item = e.target.closest('.gallery-item');
      if (item) {
        item.style.opacity = '1';
      }
      document.querySelectorAll('.gallery-item').forEach(el => el.classList.remove('border-primary'));
    });

    // Populate current gallery items
    @foreach($post->galleries as $gallery)
      @if($gallery->media)
        addGalleryImage({{ $gallery->media_id }}, "{{ image_url($gallery->media) }}", "{{ $gallery->media->title ?: $gallery->media->original_name }}", "{{ $gallery->caption }}");
      @endif
    @endforeach
  });

  function addGalleryImage(mediaId, mediaUrl, mediaLabel, caption = '') {
    if ($(`.gallery-item[data-media-id="${mediaId}"]`).length > 0) {
      return;
    }
    const html = `
      <div class="col-md-3 col-sm-6 mb-3 gallery-item" data-media-id="${mediaId}" draggable="true" style="cursor: grab;">
        <div class="card shadow-sm h-100 position-relative border">
          <img src="${mediaUrl}" class="card-img-top" style="height: 100px; object-fit: cover;">
          <div class="card-body p-2">
            <input type="hidden" name="gallery_media_ids[]" value="${mediaId}">
            <input type="text" name="gallery_captions[]" class="form-control form-control-sm" placeholder="Caption" value="${caption}">
          </div>
          <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-gallery-item"><i class="fa-solid fa-trash"></i></button>
          <div class="drag-handle position-absolute top-0 start-0 m-1 bg-dark text-white rounded px-1" style="cursor: move; opacity: 0.7; font-size: 0.8rem;">
            <i class="fa-solid fa-arrows-up-down-left-right"></i>
          </div>
        </div>
      </div>
    `;
    $('#gallery-container').append(html);
  }
</script>
@endsection
