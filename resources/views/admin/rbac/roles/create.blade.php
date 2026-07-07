@extends('admin.layouts.app')

@section('title', 'Add Access Role')
@section('page-title', 'Create User Role')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-4 fw-bold">Role Parameters</h5>
                
                @if($errors->any())
                    <div class="alert alert-danger border-0 mb-3 small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-muted uppercase">Role Key (Unique Name)</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. content_moderator" required>
                    <div class="form-text small text-muted">Use snake_case keys for internal queries check.</div>
                </div>

                <div class="mb-3">
                    <label for="label" class="form-label small fw-semibold text-muted uppercase">Display Label</label>
                    <input type="text" name="label" id="label" class="form-control" value="{{ old('label') }}" placeholder="e.g. Content Moderator" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-muted uppercase">Role Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe role capabilities...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Save Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
