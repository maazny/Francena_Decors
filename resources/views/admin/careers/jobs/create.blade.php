@extends('admin.layouts.app')

@section('title', 'New Job Opening')
@section('page-title', 'New Job Opening')
@section('page-description', 'Create a new job posting for candidates.')

@section('content')
<form action="{{ route('admin.careers.jobs.store') }}" method="POST" class="row g-4">
  @csrf

  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Position Information</h4>

        <div class="mb-3">
          <label class="form-label">Job Title</label>
          <input type="text" name="title" class="form-control" required placeholder="e.g. Senior Interior Designer">
        </div>

        <div class="mb-3">
          <label class="form-label">Slug (Optional)</label>
          <input type="text" name="slug" class="form-control" placeholder="auto-generated if left blank">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Department</label>
            <select name="department_id" class="form-select" required>
              <option value="">Select Department</option>
              @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
              <option value="">Select Category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Location</label>
            <select name="location_id" class="form-select" required>
              <option value="">Select Location</option>
              @foreach($locations as $loc)
                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Employment Type</label>
            <select name="employment_type" class="form-select" required>
              <option value="Full-time">Full-time</option>
              <option value="Part-time">Part-time</option>
              <option value="Contract">Contract</option>
              <option value="Internship">Internship</option>
              <option value="Remote">Remote</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Experience Level</label>
            <select name="experience_level" class="form-select" required>
              <option value="Entry-level">Entry-level</option>
              <option value="Mid-level">Mid-level</option>
              <option value="Senior">Senior</option>
              <option value="Lead">Lead</option>
              <option value="Executive">Executive</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Vacancies</label>
            <input type="number" name="vacancies" class="form-control" value="1" required min="1">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Job Description</label>
          <textarea name="description" class="form-control rich-editor" rows="8"></textarea>
        </div>
      </div>
    </div>

    <!-- SEO Metadata -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">SEO Settings</h4>
        <div class="mb-3">
          <label class="form-label">Meta Title</label>
          <input type="text" name="meta_title" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Meta Description</label>
          <textarea name="meta_description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Meta Keywords</label>
          <input type="text" name="meta_keywords" class="form-control" placeholder="comma-separated values">
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
          <input type="text" name="reference_no" class="form-control" placeholder="e.g. FD-ENG-023">
        </div>

        <div class="mb-3">
          <label class="form-label">Salary Range</label>
          <input type="text" name="salary_range" class="form-control" placeholder="e.g. $80,000 - $100,000">
        </div>

        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="statusSwitch" checked>
          <label class="form-check-label" for="statusSwitch">Active Status (Visible to candidates)</label>
        </div>

        <div class="form-check form-switch mb-2">
          <input type="checkbox" name="featured" value="1" class="form-check-input" id="featuredSwitch">
          <label class="form-check-label" for="featuredSwitch">Featured Position</label>
        </div>

        <div class="form-check form-switch mb-4">
          <input type="checkbox" name="homepage_featured" value="1" class="form-check-input" id="homepageSwitch">
          <label class="form-check-label" for="homepageSwitch">Feature on Homepage</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Save & Publish</button>
        <a href="{{ route('admin.careers.jobs.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
      </div>
    </div>
  </div>
</form>
@endsection
