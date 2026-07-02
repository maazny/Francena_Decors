@extends('admin.layouts.app')

@section('title', 'New Blog Tag')
@section('page-title', 'New Blog Tag')
@section('page-description', 'Create a new tag to organize blog posts.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.blog-tags.store') }}" novalidate>
      @csrf
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
          @error('name')
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
          <label class="form-label fw-semibold">Display Order</label>
          <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $tag->display_order) }}">
          @error('display_order')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Status</label>
          <div class="form-check mt-2">
            <input type="hidden" name="status" value="0">
            <input class="form-check-input" type="checkbox" name="status" value="1" {{ old('status', $tag->status) ? 'checked' : '' }}>
            <label class="form-check-label">Active</label>
          </div>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Create Tag</button>
        <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
