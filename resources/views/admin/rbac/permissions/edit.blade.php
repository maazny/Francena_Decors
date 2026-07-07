@extends('admin.layouts.app')

@section('title', 'Edit System Permission')
@section('page-title', 'Edit Permission Key')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-4 fw-bold">Permission Parameters</h5>
                
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
                    <label for="permission_group_id" class="form-label small fw-semibold text-muted uppercase">Permission Module Group</label>
                    <select name="permission_group_id" id="permission_group_id" class="form-select" required>
                        @foreach($groups as $grp)
                            <option value="{{ $grp->id }}" {{ $permission->permission_group_id == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-muted uppercase">Permission Key (Unique Identifier)</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
                    <div class="form-text small text-muted">Use snake_case naming keys. Used in Blade `@can('key')` check hooks.</div>
                </div>

                <div class="mb-3">
                    <label for="label" class="form-label small fw-semibold text-muted uppercase">Display Name</label>
                    <input type="text" name="label" id="label" class="form-control" value="{{ old('label', $permission->label) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-muted uppercase">Permission Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $permission->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Update Permission
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
