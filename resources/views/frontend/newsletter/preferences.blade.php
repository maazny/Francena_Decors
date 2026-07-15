@extends('layouts.app')

@section('title', 'Email Subscription Preferences | Francena Decors')

@section('content')
<section class="preferences-section py-5 section-bg" style="min-height: 75vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 p-5 shadow-lg bg-white rounded-4" data-aos="fade-up">
                    <h1 class="h3 font-heading mb-2 text-dark">Subscription Preferences</h1>
                    <p class="text-muted mb-5">
                        Manage your email subscription settings, group cohorts, and preferred language for <strong>{{ $subscriber->email }}</strong>.
                    </p>

                    <form method="POST" action="{{ URL::signedRoute('newsletter.update-preferences', ['token' => $subscriber->unsubscribe_token]) }}">
                        @csrf
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="pref_name" class="form-label fw-semibold text-muted small uppercase">Full Name</label>
                                <input type="text" name="name" id="pref_name" class="form-control" value="{{ old('name', $subscriber->name) }}" placeholder="Your name">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pref_email" class="form-label fw-semibold text-muted small uppercase">Email Address</label>
                                <input type="email" name="email" id="pref_email" class="form-control" value="{{ old('email', $subscriber->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pref_lang" class="form-label fw-semibold text-muted small uppercase">Preferred Language</label>
                                <select name="preferred_language" id="pref_lang" class="form-select">
                                    <option value="en" {{ old('preferred_language', $subscriber->preferred_language) == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ old('preferred_language', $subscriber->preferred_language) == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="fr" {{ old('preferred_language', $subscriber->preferred_language) == 'fr' ? 'selected' : '' }}>Français</option>
                                </select>
                                @error('preferred_language')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pref_status" class="form-label fw-semibold text-muted small uppercase">Subscription Status</label>
                                <select name="status" id="pref_status" class="form-select">
                                    <option value="active" {{ $subscriber->status->value !== 'unsubscribed' ? 'selected' : '' }}>Active Subscriber</option>
                                    <option value="unsubscribe">Unsubscribe Entirely</option>
                                </select>
                            </div>
                        </div>

                        <!-- Interest Groups -->
                        <div class="mb-5">
                            <label class="form-label fw-semibold text-muted small uppercase d-block mb-3">Group Segments / Interests</label>
                            <div class="card p-3 bg-light border-0">
                                @php
                                    $assignedGroups = $subscriber->groups->pluck('id')->toArray();
                                @endphp
                                <div class="row g-3">
                                    @forelse($groups as $group)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}" id="group_{{ $group->id }}" {{ in_array($group->id, old('groups', $assignedGroups)) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="group_{{ $group->id }}">
                                                    {{ $group->name }}
                                                    @if($group->description)
                                                        <span class="text-muted d-block small fw-normal">{{ $group->description }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center text-muted small py-2">No interest groups available.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ url('/') }}" class="text-muted text-decoration-none small">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Site
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
