@extends('admin.layouts.app')

@section('title', 'Edit Group')
@section('page-title', 'Edit Group')
@section('page-description', 'Update segment details, slugs, and visibility settings.')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0">Edit Details: {{ $group->name }}</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.newsletter.groups.update', $group->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Group Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $group->name) }}" required placeholder="e.g. VIP Customers">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $group->slug) }}" placeholder="Auto-generated if left empty">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Explain who belongs to this list segment...">{{ old('description', $group->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $group->display_order) }}">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_dynamic" value="0">
                        <input type="checkbox" name="is_dynamic" value="1" id="is_dynamic" class="form-check-input @error('is_dynamic') is-invalid @enderror" {{ old('is_dynamic', $group->is_dynamic) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_dynamic">Dynamic Group (automatically segments based on database filters)</label>
                        @error('is_dynamic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" id="status" class="form-check-input @error('status') is-invalid @enderror" {{ old('status', $group->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active (visible when targeting campaigns)</label>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.newsletter.groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
