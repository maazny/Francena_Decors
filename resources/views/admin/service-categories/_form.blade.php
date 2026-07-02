<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $serviceCategory->name) }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug', $serviceCategory->slug) }}">
  </div>
  <div class="col-12">
    <label class="form-label">Short Description</label>
    <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $serviceCategory->short_description) }}</textarea>
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="5">{{ old('description', $serviceCategory->description) }}</textarea>
  </div>
  <div class="col-md-6">
    <label class="form-label">Icon</label>
    <input type="text" name="icon" class="form-control" value="{{ old('icon', $serviceCategory->icon) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Display Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $serviceCategory->display_order) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Featured Image</label>
    <select name="featured_image_id" class="form-select">
      <option value="">Select image</option>
      @foreach($imageOptions as $image)
        <option value="{{ $image->id }}" {{ old('featured_image_id', $serviceCategory->featured_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Banner Image</label>
    <select name="banner_image_id" class="form-select">
      <option value="">Select image</option>
      @foreach($imageOptions as $image)
        <option value="{{ $image->id }}" {{ old('banner_image_id', $serviceCategory->banner_image_id) == $image->id ? 'selected' : '' }}>{{ $image->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">SEO Title</label>
    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $serviceCategory->seo_title) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">SEO Keywords</label>
    <input type="text" name="seo_keywords" class="form-control" value="{{ old('seo_keywords', $serviceCategory->seo_keywords) }}">
  </div>
  <div class="col-12">
    <label class="form-label">SEO Description</label>
    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $serviceCategory->seo_description) }}</textarea>
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $serviceCategory->status) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>
