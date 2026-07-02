@php
  $member = $member ?? $teamMember ?? null;
  $selectedProfile = $member?->profilePhoto ?? null;
  $selectedCover = $member?->coverPhoto ?? null;
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
          <label class="form-label" for="full_name">Full Name</label>
          <input id="full_name" name="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $member->full_name ?? '') }}" required maxlength="255">
          @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="slug">Slug</label>
          <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $member->slug ?? '') }}" maxlength="255">
          @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="department_id">Department</label>
          <select id="department_id" name="department_id" class="form-select">
            <option value="">-- Select Department --</option>
            @foreach($departments as $dep)
              <option value="{{ $dep->id }}" {{ (old('department_id', $member->department_id ?? '') == $dep->id) ? 'selected' : '' }}>{{ $dep->name }}</option>
            @endforeach
          </select>
          @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="designation">Designation</label>
          <input id="designation" name="designation" type="text" class="form-control @error('designation') is-invalid @enderror" value="{{ old('designation', $member->designation ?? '') }}" maxlength="255">
          @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="short_bio">Short Bio</label>
          <textarea id="short_bio" name="short_bio" class="form-control @error('short_bio') is-invalid @enderror" rows="3">{{ old('short_bio', $member->short_bio ?? '') }}</textarea>
          @error('short_bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="full_bio">Full Bio</label>
          <textarea id="full_bio" name="full_bio" class="form-control rich-editor @error('full_bio') is-invalid @enderror" rows="6">{{ old('full_bio', $member->full_bio ?? '') }}</textarea>
          @error('full_bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label">Profile Photo</label>
          <input id="profile_photo_id" name="profile_photo_id" type="hidden" value="{{ old('profile_photo_id', $member->profile_photo_id ?? '') }}">
          <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#profilePhotoModal">Select Profile Photo</button>
          <img id="profile_photo_id_preview" src="{{ $selectedProfile ? thumbnail_url($selectedProfile) : '' }}" alt="Profile preview" class="img-fluid rounded border mt-3 {{ $selectedProfile ? '' : 'd-none' }}" style="height: 140px; width: 100%; object-fit: cover;">
        </div>

        <div class="col-md-6">
          <label class="form-label">Cover Photo</label>
          <input id="cover_photo_id" name="cover_photo_id" type="hidden" value="{{ old('cover_photo_id', $member->cover_photo_id ?? '') }}">
          <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#coverPhotoModal">Select Cover Photo</button>
          <img id="cover_photo_id_preview" src="{{ $selectedCover ? thumbnail_url($selectedCover) : '' }}" alt="Cover preview" class="img-fluid rounded border mt-3 {{ $selectedCover ? '' : 'd-none' }}" style="height: 140px; width: 100%; object-fit: cover;">
        </div>

        <div class="col-md-4">
          <label class="form-label" for="email">Email</label>
          <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email ?? '') }}" maxlength="255">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label" for="phone">Phone</label>
          <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $member->phone ?? '') }}" maxlength="50">
          @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label" for="experience_years">Experience (years)</label>
          <input id="experience_years" name="experience_years" type="number" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $member->experience_years ?? '') }}">
          @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="qualification">Qualification</label>
          <input id="qualification" name="qualification" type="text" class="form-control @error('qualification') is-invalid @enderror" value="{{ old('qualification', $member->qualification ?? '') }}" maxlength="255">
          @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="specialization">Specialization</label>
          <input id="specialization" name="specialization" type="text" class="form-control @error('specialization') is-invalid @enderror" value="{{ old('specialization', $member->specialization ?? '') }}" maxlength="255">
          @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label" for="display_order">Display Order</label>
          <input id="display_order" name="display_order" type="number" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $member->display_order ?? 0) }}">
          @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
          <label class="form-label">Featured</label>
          <div class="form-check form-switch">
            <input id="featured" name="featured" type="checkbox" class="form-check-input" value="1" {{ old('featured', $member->featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="featured">Featured</label>
          </div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Homepage Featured</label>
          <div class="form-check form-switch">
            <input id="homepage_featured" name="homepage_featured" type="checkbox" class="form-check-input" value="1" {{ old('homepage_featured', $member->homepage_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="homepage_featured">Homepage Featured</label>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label" for="joining_date">Joining Date</label>
          <input id="joining_date" name="joining_date" type="date" class="form-control @error('joining_date') is-invalid @enderror" value="{{ old('joining_date', optional($member->joining_date)->format('Y-m-d') ?? '') }}">
          @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <h5 class="mt-3">SEO</h5>
        </div>

        <div class="col-md-6">
          <label class="form-label" for="seo_title">Meta Title</label>
          <input id="seo_title" name="seo_title" type="text" class="form-control @error('seo_title') is-invalid @enderror" value="{{ old('seo_title', $member->seo_title ?? '') }}" maxlength="255">
          @error('seo_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="seo_keywords">Meta Keywords</label>
          <input id="seo_keywords" name="seo_keywords" type="text" class="form-control @error('seo_keywords') is-invalid @enderror" value="{{ old('seo_keywords', $member->seo_keywords ?? '') }}" maxlength="255">
          @error('seo_keywords')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="seo_description">Meta Description</label>
          <textarea id="seo_description" name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" rows="3">{{ old('seo_description', $member->seo_description ?? '') }}</textarea>
          @error('seo_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.team-members.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

@include('admin.hero-sliders._media-modal', ['modalId' => 'profilePhotoModal', 'targetInput' => 'profile_photo_id', 'mediaItems' => $mediaOptions, 'title' => 'Select Profile Photo'])
@include('admin.hero-sliders._media-modal', ['modalId' => 'coverPhotoModal', 'targetInput' => 'cover_photo_id', 'mediaItems' => $mediaOptions, 'title' => 'Select Cover Photo'])
@include('admin.team-members._social-links')
@include('admin.team-members._skills')
@include('admin.team-members._certifications')

