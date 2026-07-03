@extends('admin.layouts.app')

@section('title', 'Job Categories')
@section('page-title', 'Job Categories')
@section('page-description', 'Manage functional categories for job postings.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Job Categories</h2>
        <p class="text-muted mb-0">Define specific positions structure mapped to their parent departments.</p>
      </div>
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
        New Category
      </button>
    </div>

    <div class="table-responsive">
      <table class="table align-middle table-hover">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Parent Department</th>
            <th>Slug</th>
            <th>Display Order</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr>
              <td>
                <strong>{{ $category->name }}</strong>
                @if($category->trashed())
                  <span class="badge bg-danger">Deleted</span>
                @endif
              </td>
              <td>{{ $category->department->name ?? 'N/A' }}</td>
              <td><code>{{ $category->slug }}</code></td>
              <td>{{ $category->display_order }}</td>
              <td>
                <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                  {{ $category->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="text-end">
                @if($category->trashed())
                  <form action="{{ route('admin.careers.categories.restore', $category->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                  </form>
                @else
                  <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                    Edit
                  </button>
                  <form action="{{ route('admin.careers.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Toggle</button>
                  </form>
                  <form action="{{ route('admin.careers.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                @endif
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form action="{{ route('admin.careers.categories.update', $category) }}" method="POST" class="modal-content">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Parent Department</label>
                      <select name="department_id" class="form-select" required>
                        @foreach($departments as $dept)
                          <option value="{{ $dept->id }}" {{ $category->department_id == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Name</label>
                      <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" class="form-control" value="{{ $category->slug }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Description</label>
                      <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Display Order</label>
                      <input type="number" name="display_order" class="form-control" value="{{ $category->display_order }}">
                    </div>
                    <div class="form-check">
                      <input type="checkbox" name="status" value="1" class="form-check-input" id="editStatus{{ $category->id }}" {{ $category->status ? 'checked' : '' }}>
                      <label class="form-check-label" for="editStatus{{ $category->id }}">Active</label>
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
              <td colspan="6" class="text-center py-4 text-muted">No categories found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="mt-3">
      {{ $categories->links() }}
    </div>
  </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.careers.categories.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Parent Department</label>
          <select name="department_id" class="form-select" required>
            <option value="">Select Department</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
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
        <button type="submit" class="btn btn-primary">Create Category</button>
      </div>
    </form>
  </div>
</div>
@endsection
