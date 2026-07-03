@extends('admin.layouts.app')

@section('title', 'Job Openings')
@section('page-title', 'Job Openings')
@section('page-description', 'Manage open vacancies, positions, status states, and featured roles.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Active Vacancies</h2>
        <p class="text-muted mb-0">Create, edit, duplicate, and configure details of careers postings.</p>
      </div>
      <a href="{{ route('admin.careers.jobs.create') }}" class="btn btn-primary btn-sm">
        New Job Opening
      </a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Department</th>
            <th>Category</th>
            <th>Location</th>
            <th>Type</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($jobs as $job)
            <tr>
              <td>
                <strong>{{ $job->title }}</strong>
                @if($job->featured)
                  <span class="badge bg-warning text-dark ms-1">Featured</span>
                @endif
                @if($job->trashed())
                  <span class="badge bg-danger ms-1">Deleted</span>
                @endif
              </td>
              <td>{{ $job->department->name ?? 'N/A' }}</td>
              <td>{{ $job->category->name ?? 'N/A' }}</td>
              <td>{{ $job->location->name ?? 'N/A' }}</td>
              <td>{{ $job->employment_type }}</td>
              <td>
                <span class="badge bg-{{ $job->status ? 'success' : 'secondary' }}">
                  {{ $job->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="text-end">
                @if($job->trashed())
                  <form action="{{ route('admin.careers.jobs.restore', $job->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                  </form>
                @else
                  <a href="{{ route('admin.careers.jobs.edit', $job) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('admin.careers.jobs.toggle-status', $job) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle</button>
                  </form>
                  <form action="{{ route('admin.careers.jobs.duplicate', $job) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-info">Clone</button>
                  </form>
                  <form action="{{ route('admin.careers.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-muted">No job openings found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="mt-3">
      {{ $jobs->links() }}
    </div>
  </div>
</div>
@endsection
