@php
  $dep = $department ?? $teamDepartment ?? null;
@endphp

<div class="card shadow-sm">
  <div class="card-body p-4">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
          <label class="form-label" for="name">Name</label>
          <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $dep->name ?? '') }}" required maxlength="255">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="slug">Slug</label>
          <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $dep->slug ?? '') }}" maxlength="255">
          @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $dep->description ?? '') }}</textarea>
          @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label" for="display_order">Display Order</label>
          <input id="display_order" name="display_order" type="number" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $dep->display_order ?? 0) }}">
          @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Status</label>
          <div class="form-check form-switch">
            <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $dep->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.team-departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
