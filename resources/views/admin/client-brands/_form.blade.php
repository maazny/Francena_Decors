<div class="row g-4">
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $brand->name) }}" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $brand->slug) }}" placeholder="optional-slug">
          @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" rows="5" class="form-control rich-editor @error('description') is-invalid @enderror">{{ old('description', $brand->description) }}</textarea>
          @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Website URL</label>
          <input type="url" name="website_url" class="form-control @error('website_url') is-invalid @enderror" value="{{ old('website_url', $brand->website_url) }}">
          @error('website_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $brand->category) }}" placeholder="Residential, Commercial, Technology...">
          @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-body p-4">
        <div class="mb-3">
          <label class="form-label">Logo</label>
          <select name="logo_id" class="form-select @error('logo_id') is-invalid @enderror">
            <option value="">No logo</option>
            @foreach($mediaOptions as $media)
              <option value="{{ $media->id }}" {{ old('logo_id', $brand->logo_id) == $media->id ? 'selected' : '' }}>{{ $media->file_name }}</option>
            @endforeach
          </select>
          @error('logo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $brand->display_order) }}">
          @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select @error('status') is-invalid @enderror">
            <option value="draft" {{ old('status', $brand->status) === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ old('status', $brand->status) === 'published' ? 'selected' : '' }}>Published</option>
          </select>
          @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" {{ old('featured', $brand->featured) ? 'checked' : '' }}>
          <label class="form-check-label" for="featured">Featured</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="homepage_featured" value="1" id="homepage_featured" {{ old('homepage_featured', $brand->homepage_featured) ? 'checked' : '' }}>
          <label class="form-check-label" for="homepage_featured">Homepage featured</label>
        </div>
        <div class="d-grid gap-2 mt-4">
          <button type="submit" class="btn btn-primary">Save</button>
          <a href="{{ route('admin.client-brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
</div>
