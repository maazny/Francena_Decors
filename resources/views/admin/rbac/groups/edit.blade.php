@extends('admin.layouts.app')

@section('title', 'Edit Module Group')
@section('page-title', 'Edit Permission Group')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('admin.permission-groups.update', $group->id) }}">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <h5 class="card-title h6 mb-4 fw-bold">Group Parameters</h5>
                
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
                    <label for="name" class="form-label small fw-semibold text-muted uppercase">Group / Module Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold text-muted uppercase">Group Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $group->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.permission-groups.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">
                    <i class="fa-solid fa-save me-1"></i> Update Module Group
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
