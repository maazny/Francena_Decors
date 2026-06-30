<div class="card shadow-sm">
  <div class="card-body p-4">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ $action }}">
      @csrf
      @if($method !== 'POST') @method($method) @endif

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="title">Title</label>
          <input id="title" name="title" class="form-control" value="{{ old('title', $companyValue->title) }}" required maxlength="191">
        </div>
        <div class="col-md-3">
          <label class="form-label" for="icon">Icon Class</label>
          <input id="icon" name="icon" class="form-control" value="{{ old('icon', $companyValue->icon) }}" maxlength="100">
        </div>
        <div class="col-md-3">
          <label class="form-label" for="display_order">Display Order</label>
          <input id="display_order" name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $companyValue->display_order) }}" required>
        </div>
        <div class="col-12">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $companyValue->description) }}</textarea>
        </div>
        <div class="col-12">
          <div class="form-check form-switch">
            <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $companyValue->status) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.about-sections.edit') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
