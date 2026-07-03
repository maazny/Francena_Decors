@extends('admin.layouts.app')

@section('title', 'Application Details')
@section('page-title', 'Application Details')
@section('page-description', 'Review the candidate credentials, resume, and manage notes.')

@section('content')
<div class="row g-4">
  <!-- Left Side: Profile & Letter -->
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
          <div>
            <h3 class="h4 mb-1">{{ $application->full_name }}</h3>
            <p class="text-muted mb-0">Applied for: <strong>{{ $application->jobOpening->title ?? 'Deleted Position' }}</strong></p>
          </div>
          <div>
            <span class="badge fs-6 bg-{{ 
              match($application->application_status) {
                'applied' => 'info',
                'reviewed' => 'secondary',
                'shortlisted' => 'primary',
                'interviewed' => 'warning',
                'offered' => 'success',
                'rejected' => 'danger',
                'withdrawn' => 'dark',
                default => 'secondary'
              }
            }}">
              {{ ucfirst($application->application_status) }}
            </span>
          </div>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Email Address</h6>
            <p class="mb-0"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></p>
          </div>
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Phone Number</h6>
            <p class="mb-0"><a href="tel:{{ $application->phone }}">{{ $application->phone }}</a></p>
          </div>
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Experience Level</h6>
            <p class="mb-0">{{ $application->years_of_experience }} years</p>
          </div>
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Current Company</h6>
            <p class="mb-0">{{ $application->current_company ?? 'N/A' }}</p>
          </div>
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Expected Salary</h6>
            <p class="mb-0">${{ number_format($application->expected_salary, 2) }}</p>
          </div>
          <div class="col-sm-6">
            <h6 class="text-uppercase small text-muted mb-1">Applied Date</h6>
            <p class="mb-0">{{ $application->applied_at ? $application->applied_at->format('M d, Y H:i') : $application->created_at->format('M d, Y H:i') }}</p>
          </div>
        </div>

        @if($application->resumeMedia)
          <div class="card bg-light border-0 mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <i class="fa-solid fa-file-pdf fa-2x text-danger me-3"></i>
                <div>
                  <h6 class="mb-0">Candidate Resume</h6>
                  <small class="text-muted">{{ $application->resumeMedia->original_name }}</small>
                </div>
              </div>
              <a href="{{ route('admin.media.download', $application->resumeMedia->id) }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-download me-1"></i> Download Resume
              </a>
            </div>
          </div>
        @else
          <div class="alert alert-warning mb-4">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> No resume attachment found for this application.
          </div>
        @endif

        <h5 class="h6 text-uppercase small text-muted mb-2">Cover Letter</h5>
        <div class="border rounded p-3 bg-light" style="white-space: pre-line;">
          {{ $application->cover_letter ?? 'No cover letter provided.' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side: Status Updates & Admin Notes -->
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Application Management</h4>

        <form action="{{ route('admin.careers.applications.update', $application) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Update Status</label>
            <select name="application_status" class="form-select">
              @foreach(['applied', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn'] as $status)
                <option value="{{ $status }}" {{ $application->application_status == $status ? 'selected' : '' }}>
                  {{ ucfirst($status) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Admin Collaboration Notes</label>
            <textarea name="admin_notes" class="form-control" rows="6" placeholder="Write internal team reviews, interview timings, or notes here...">{{ $application->admin_notes }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">Save Updates</button>
        </form>
      </div>
    </div>

    <!-- Back Button -->
    <a href="{{ route('admin.careers.applications.index') }}" class="btn btn-outline-secondary w-100">
      <i class="fa-solid fa-arrow-left me-1"></i> Back to Applications
    </a>
  </div>
</div>
@endsection
