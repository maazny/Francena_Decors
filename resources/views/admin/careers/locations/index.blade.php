@extends('admin.layouts.app')

@section('title', 'Job Locations')
@section('page-title', 'Job Locations')
@section('page-description', 'Manage geographic office branch locations for job vacancies.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Company Locations</h2>
        <p class="text-muted mb-0">Track and manage branch details for job vacancy listings.</p>
      </div>
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
        New Location
      </button>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Slug</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($locations as $loc)
            <tr>
              <td>
                <strong>{{ $loc->name }}</strong>
                @if($loc->trashed())
                  <span class="badge bg-danger">Deleted</span>
                @endif
              </td>
              <td>
                {{ $loc->address ? $loc->address . ', ' : '' }}{{ $loc->city }}{{ $loc->state ? ', ' . $loc->state : '' }}
              </td>
              <td><code>{{ $loc->slug }}</code></td>
              <td>
                <span class="badge bg-{{ $loc->status ? 'success' : 'secondary' }}">
                  {{ $loc->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="text-end">
                @if($loc->trashed())
                  <form action="{{ route('admin.careers.locations.restore', $loc->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                  </form>
                @else
                  <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $loc->id }}">
                    Edit
                  </button>
                  <form action="{{ route('admin.careers.locations.toggle-status', $loc) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle</button>
                  </form>
                  <form action="{{ route('admin.careers.locations.destroy', $loc) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                @endif
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $loc->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form action="{{ route('admin.careers.locations.update', $loc) }}" method="POST" class="modal-content">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" value="{{ $loc->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" class="form-control" value="{{ $loc->slug }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Address</label>
                      <input type="text" name="address" class="form-control" value="{{ $loc->address }}">
                    </div>
                    <div class="row g-2">
                      <div class="col-6 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ $loc->city }}" required>
                      </div>
                      <div class="col-6 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control" value="{{ $loc->state }}">
                      </div>
                    </div>
                    <div class="row g-2">
                      <div class="col-6 mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ $loc->country }}" required>
                      </div>
                      <div class="col-6 mb-3">
                        <label class="form-label">Zip Code</label>
                        <input type="text" name="zip_code" class="form-control" value="{{ $loc->zip_code }}">
                      </div>
                    </div>
                    <div class="form-check">
                      <input type="checkbox" name="status" value="1" class="form-check-input" id="editStatus{{ $loc->id }}" {{ $loc->status ? 'checked' : '' }}>
                      <label class="form-check-label" for="editStatus{{ $loc->id }}">Active</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-muted">No locations found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="mt-3">
      {{ $locations->links() }}
    </div>
  </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.careers.locations.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">New Location</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Slug (Optional)</label>
          <input type="text" name="slug" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control">
        </div>
        <div class="row g-2">
          <div class="col-6 mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" required>
          </div>
          <div class="col-6 mb-3">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control">
          </div>
        </div>
        <div class="row g-2">
          <div class="col-6 mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="United States" required>
          </div>
          <div class="col-6 mb-3">
            <label class="form-label">Zip Code</label>
            <input type="text" name="zip_code" class="form-control">
          </div>
        </div>
        <div class="form-check">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="createStatus" checked>
          <label class="form-check-label" for="createStatus">Active</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Location</button>
      </div>
    </form>
  </div>
</div>
@endsection
