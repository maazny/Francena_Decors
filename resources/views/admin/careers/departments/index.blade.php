@extends('admin.layouts.app')

@section('title', 'Job Departments')
@section('page-title', 'Job Departments')
@section('page-description', 'Manage structural company departments for careers listings.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Company Departments</h2>
        <p class="text-muted mb-0">Create and edit departments to structure your job openings.</p>
      </div>
      <!-- Trigger simple modal or show create form inline -->
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
        New Department
      </button>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Display Order</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($departments as $dept)
            <tr>
              <td>
                <strong>{{ $dept->name }}</strong>
                @if($dept->trashed())
                  <span class="badge bg-danger">Deleted</span>
                @endif
              </td>
              <td><code>{{ $dept->slug }}</code></td>
              <td>{{ Str::limit($dept->description, 50) }}</td>
              <td>{{ $dept->display_order }}</td>
              <td>
                <span class="badge bg-{{ $dept->status ? 'success' : 'secondary' }}">
                  {{ $dept->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="text-end">
                @if($dept->trashed())
                  <form action="{{ route('admin.careers.departments.restore', $dept->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                  </form>
                @else
                  <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $dept->id }}">
                    Edit
                  </button>
                  <form action="{{ route('admin.careers.departments.toggle-status', $dept) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle</button>
                  </form>
                  <form action="{{ route('admin.careers.departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                @endif
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $dept->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form action="{{ route('admin.careers.departments.update', $dept) }}" method="POST" class="modal-content">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" class="form-control" value="{{ $dept->slug }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Description</label>
                      <textarea name="description" class="form-control">{{ $dept->description }}</textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Display Order</label>
                      <input type="number" name="display_order" class="form-control" value="{{ $dept->display_order }}">
                    </div>
                    <div class="form-check">
                      <input type="checkbox" name="status" value="1" class="form-check-input" id="editStatus{{ $dept->id }}" {{ $dept->status ? 'checked' : '' }}>
                      <label class="form-check-label" for="editStatus{{ $dept->id }}">Active</label>
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
              <td colspan="6" class="text-center py-4 text-muted">No departments found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="mt-3">
      {{ $departments->links() }}
    </div>
  </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.careers.departments.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">New Department</h5>
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
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="0">
        </div>
        <div class="form-check">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="createStatus" checked>
          <label class="form-check-label" for="createStatus">Active</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Department</button>
      </div>
    </form>
  </div>
</div>
@endsection
