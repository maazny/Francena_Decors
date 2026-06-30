<div class="card shadow-sm">
  <div class="card-body p-4">
    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ $action }}">
      @csrf
      @if($method !== 'POST') @method($method) @endif

      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label" for="year">Year</label>
          <input id="year" name="year" class="form-control" value="{{ old('year', $companyTimeline->year) }}" required maxlength="20">
        </div>
        <div class="col-md-5">
          <label class="form-label" for="title">Title</label>
          <input id="title" name="title" class="form-control" value="{{ old('title', $companyTimeline->title) }}" required maxlength="191">
        </div>
        <div class="col-md-2">
          <label class="form-label" for="display_order">Order</label>
          <input id="display_order" name="display_order" type="number" min="0" class="form-control" value="{{ old('display_order', $companyTimeline->display_order) }}" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <div class="form-check form-switch">
            <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $companyTimeline->status) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
          </div>
        </div>
        <div class="col-12">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $companyTimeline->description) }}</textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label">Timeline Image</label>
          <input id="image_id" name="image_id" type="hidden" value="{{ old('image_id', $companyTimeline->image_id) }}">
          <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#timelineImageModal">Select Image</button>
          <img id="image_id_preview" src="{{ $companyTimeline->image ? image_url($companyTimeline->image) : '' }}" alt="Timeline image preview" class="img-fluid border rounded mt-2 {{ $companyTimeline->image ? '' : 'd-none' }}" style="height: 120px; width: 100%; object-fit: cover;">
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.about-sections.edit') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

@include('admin.partials.media-picker-modal', ['modalId' => 'timelineImageModal', 'title' => 'Select Timeline Image', 'targetInput' => 'image_id', 'mediaItems' => $imageOptions, 'isImage' => true])
