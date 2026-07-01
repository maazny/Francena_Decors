@extends('admin.layouts.app')

@section('title', 'Project Categories')
@section('page-title', 'Project Categories')
@section('page-description', 'Organize construction projects into high-level categories.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Project Categories</h2>
        <p class="text-muted mb-0">Create and manage the categories used across the projects CMS.</p>
      </div>
      <a href="{{ route('admin.project-categories.create') }}" class="btn btn-primary btn-sm">New Category</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search categories" value="{{ $search }}">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.project-categories.bulk') }}" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Name</th>
              <th>Slug</th>
              <th>Status</th>
              <th>Projects</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $category->id }}" class="bulk-checkbox"></td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td><span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">{{ $category->status ? 'Active' : 'Inactive' }}</span></td>
                <td>{{ $category->projects_count }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.project-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('admin.project-categories.toggle-status', $category) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">{{ $category->status ? 'Deactivate' : 'Activate' }}</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No project categories found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="activate">Activate</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="deactivate">Deactivate</button>
          <button type="button" class="btn btn-outline-danger btn-sm bulk-action" data-action="delete">Delete</button>
        </div>
        <div>{{ $categories->links() }}</div>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('select-all')?.addEventListener('change', function () {
    document.querySelectorAll('.bulk-checkbox').forEach(function (checkbox) {
      checkbox.checked = this.checked;
    }.bind(this));
  });

  document.querySelectorAll('.bulk-action').forEach(function (button) {
    button.addEventListener('click', function () {
      document.getElementById('bulk-action').value = this.dataset.action;
      document.getElementById('bulk-form').submit();
    });
  });
</script>
@endsection
