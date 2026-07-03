@extends('admin.layouts.app')

@section('title', 'Edit Job Opening')
@section('page-title', 'Edit Job Opening')
@section('page-description', 'Update the vacancy details, SEO configurations, or status flags.')

@section('content')
<form action="{{ route('admin.careers.jobs.update', $job) }}" method="POST" class="row g-4">
  @csrf
  @method('PUT')

  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Position Information</h4>

        <div class="mb-3">
          <label class="form-label">Job Title</label>
          <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Slug</label>
          <input type="text" name="slug" class="form-control" value="{{ $job->slug }}">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select" required>
              @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ $job->department_id == $dept->id ? 'selected' : '' }}>
                  {{ $dept->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $job->category_id == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Location</label>
            <select name="location_id" class="form-select" required>
              @foreach($locations as $loc)
                <option value="{{ $loc->id }}" {{ $job->location_id == $loc->id ? 'selected' : '' }}>
                  {{ $loc->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Employment Type</label>
            <select name="employment_type" class="form-select" required>
              @foreach(['Full-time', 'Part-time', 'Contract', 'Internship', 'Remote'] as $type)
                <option value="{{ $type }}" {{ $job->employment_type == $type ? 'selected' : '' }}>{{ $type }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Experience Level</label>
            <select name="experience_level" class="form-select" required>
              @foreach(['Entry-level', 'Mid-level', 'Senior', 'Lead', 'Executive'] as $level)
                <option value="{{ $level }}" {{ $job->experience_level == $level ? 'selected' : '' }}>{{ $level }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Vacancies</label>
            <input type="number" name="vacancies" class="form-control" value="{{ $job->vacancies }}" required min="1">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Job Description</label>
          <textarea name="description" class="form-control rich-editor" rows="8">{{ $job->description }}</textarea>
        </div>
      </div>
    </div>

    <!-- SEO Metadata -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">SEO Settings</h4>
        <div class="mb-3">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="{{ $job->meta_title }}">
        </div>
        <div class="mb-3">
          <label class="form-label">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="3">{{ $job->meta_description }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Meta Keywords</label>
          <input type="text" name="meta_keywords" class="form-control" value="{{ $job->meta_keywords }}">
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Publish Settings</h4>

        <div class="mb-3">
          <label class="form-label">Reference Number</label>
          <input type="text" name="reference_no" class="form-control" value="{{ $job->reference_no }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Salary Range</label>
          <input type="text" name="salary_range" class="form-control" value="{{ $job->salary_range }}">
        </div>

        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="statusSwitch" {{ $job->status ? 'checked' : '' }}>
          <label class="form-check-label" for="statusSwitch">Active Status (Visible to candidates)</label>
        </div>

        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="featured" value="1" class="form-check-input" id="featuredSwitch" {{ $job->featured ? 'checked' : '' }}>
          <label class="form-check-label" for="featuredSwitch">Featured Position</label>
        </div>

        <div class="form-check form-switch mb-4">
          <input type="checkbox" name="homepage_featured" value="1" class="form-check-input" id="homepageSwitch" {{ $job->homepage_featured ? 'checked' : '' }}>
          <label class="form-check-label" for="homepageSwitch">Feature on Homepage</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Update Position</button>
        <a href="{{ route('admin.careers.jobs.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
      </div>
    </div>
  </div>
</form>
@endsection
