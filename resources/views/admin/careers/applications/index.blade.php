@extends('admin.layouts.app')

@section('title', 'Job Applications')
@section('page-title', 'Job Applications')
@section('page-description', 'Manage and review applicant submissions for open positions.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Applicant Submissions</h2>
        <p class="text-muted mb-0">Track and update applicant statuses, review resumes, and manage notes.</p>
      </div>
    </div>

    <!-- Filters & Search -->
    <form method="GET" action="{{ route('admin.careers.applications.index') }}" class="row g-3 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
      </div>
      <div class="col-md-3">
        <select name="job_opening_id" class="form-select">
          <option value="">All Job Openings</option>
          @foreach($jobOpenings as $opening)
            <option value="{{ $opening->id }}" {{ request('job_opening_id') == $opening->id ? 'selected' : '' }}>
              {{ $opening->title }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          @foreach(['applied', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn'] as $statusOption)
            <option value="{{ $statusOption }}" {{ request('status') == $statusOption ? 'selected' : '' }}>
              {{ ucfirst($statusOption) }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>

    <!-- Bulk Actions Form -->
    <form method="POST" action="" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <input type="hidden" name="status" id="bulk-status-value" value="">

      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="table-light">
            <tr>
              <th width="40"><input type="checkbox" id="select-all"></th>
              <th>Applicant Name</th>
              <th>Job Position</th>
              <th>Applied Date</th>
              <th>Experience</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($applications as $application)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $application->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $application->full_name }}</strong>
                  <div class="text-muted small">{{ $application->email }} | {{ $application->phone }}</div>
                  @if($application->trashed())
                    <span class="badge bg-danger">Deleted</span>
                  @endif
                </td>
                <td>
                  {{ $application->jobOpening->title ?? 'Deleted Position' }}
                </td>
                <td>
                  {{ $application->applied_at ? $application->applied_at->format('M d, Y') : $application->created_at->format('M d, Y') }}
                </td>
                <td>
                  {{ $application->years_of_experience }} years
                </td>
                <td>
                  <span class="badge bg-{{ 
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
                </td>
                <td class="text-end">
                  @if($application->trashed())
                    <form action="{{ route('admin.careers.applications.restore', $application->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <a href="{{ route('admin.careers.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a>
                    <form action="{{ route('admin.careers.applications.toggle-status', $application) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle Status</button>
                    </form>
                    <form action="{{ route('admin.careers.applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this application?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">No applications found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Bulk Actions Controls -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="d-flex align-items-center gap-2">
          <div class="btn-group">
            <button type="button" class="btn btn-outline-danger btn-sm bulk-btn" data-action="delete">Delete Selected</button>
          </div>
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
              Update Status
            </button>
            <ul class="dropdown-menu">
              @foreach(['applied', 'reviewed', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn'] as $status)
                <li>
                  <a class="dropdown-item status-bulk-item" href="#" data-status="{{ $status }}">
                    {{ ucfirst($status) }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div>
          {{ $applications->links() }}
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    const bulkForm = document.getElementById('bulk-form');
    const bulkAction = document.getElementById('bulk-action');
    const bulkStatusVal = document.getElementById('bulk-status-value');

    selectAll?.addEventListener('change', function () {
      checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.querySelectorAll('.bulk-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one application.');
          return;
        }

        const action = btn.dataset.action;
        if (action === 'delete' && !confirm('Are you sure you want to delete the selected applications?')) {
          return;
        }

        bulkAction.value = action;
        bulkForm.action = "{{ route('admin.careers.applications.bulk-delete') }}";
        bulkForm.submit();
      });
    });

    document.querySelectorAll('.status-bulk-item').forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one application.');
          return;
        }

        const targetStatus = item.dataset.status;
        bulkAction.value = 'status';
        bulkStatusVal.value = targetStatus;
        bulkForm.action = "{{ route('admin.careers.applications.bulk-status') }}";
        bulkForm.submit();
      });
    });
  });
</script>
@endsection
