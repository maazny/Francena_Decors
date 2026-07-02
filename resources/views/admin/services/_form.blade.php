<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $service->title) }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $service->slug) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
      <option value="">Select category</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Display Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $service->display_order) }}" required>
  </div>
  <div class="col-12">
    <label class="form-label">Short Description</label>
    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $service->short_description) }}</textarea>
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control rich-editor" rows="8">{{ old('description', $service->description) }}</textarea>
  </div>
  <div class="col-md-4">
    <label class="form-label">Starting Price</label>
    <input type="number" step="0.01" name="starting_price" class="form-control" value="{{ old('starting_price', $service->starting_price) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Duration</label>
    <input type="text" name="duration" class="form-control" value="{{ old('duration', $service->duration) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Location</label>
    <input type="text" name="location" class="form-control" value="{{ old('location', $service->location) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Featured Image</label>
    <select name="featured_image_id" class="form-select">
      <option value="">Select image</option>
      @foreach($imageOptions as $image)
        <option value="{{ $image->id }}" {{ old('featured_image_id', $service->featured_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Banner Image</label>
    <select name="banner_image_id" class="form-select">
      <option value="">Select image</option>
      @foreach($imageOptions as $image)
        <option value="{{ $image->id }}" {{ old('banner_image_id', $service->banner_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Icon</label>
    <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}">
  </div>
  <div class="col-md-4">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
      <label class="form-check-label">Featured</label>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $service->status) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>
  </div>
  <div class="col-md-6">
    <label class="form-label">SEO Title</label>
    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $service->seo_title) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">SEO Keywords</label>
    <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $service->seo_keywords) }}">
  </div>
  <div class="col-12">
    <label class="form-label">SEO Description</label>
    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $service->seo_description) }}</textarea>
  </div>
</div>
