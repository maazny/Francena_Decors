@extends('admin.layouts.app')

@section('title', 'Add Subscriber')
@section('page-title', 'Add Subscriber')
@section('page-description', 'Manually add a new subscriber to the newsletter database.')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0">Subscriber Details</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.newsletter.subscribers.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. John Doe">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="e.g. john@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. +1 234 567 890">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="preferred_language" class="form-label">Preferred Language</label>
                        <select name="preferred_language" id="preferred_language" class="form-select @error('preferred_language') is-invalid @enderror">
                            <option value="en" {{ old('preferred_language', 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ old('preferred_language') == 'es' ? 'selected' : '' }}>Spanish</option>
                            <option value="fr" {{ old('preferred_language') == 'fr' ? 'selected' : '' }}>French</option>
                            <option value="de" {{ old('preferred_language') == 'de' ? 'selected' : '' }}>German</option>
                        </select>
                        @error('preferred_language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Assign to Groups / Segments</label>
                        <div class="card p-3 @error('groups') is-invalid @enderror" style="max-height: 200px; overflow-y: auto;">
                            @forelse($groups as $group)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}" id="group_{{ $group->id }}" {{ is_array(old('groups')) && in_array($group->id, old('groups')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="group_{{ $group->id }}">
                                        {{ $group->name }}
                                        @if($group->description)
                                            <small class="text-muted d-block">{{ $group->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted small text-center py-2">No active groups available.</div>
                            @endforelse
                        </div>
                        @error('groups')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.newsletter.subscribers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Subscriber</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
