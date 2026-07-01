@extends('admin.layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')
@section('page-description', 'Manage your completed and ongoing projects.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Projects</h2>
        <p class="text-muted mb-0">Create, update, and publish portfolio projects.</p>
      </div>
      <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">New Project</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search projects" value="{{ $search }}">
      </div>
      <div class="col-md-3">
        <select name="category_id" class="form-select">
          <option value="">All categories</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (string)$category_id === (string)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select">
          <option value="">All status</option>
          <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Published</option>
          <option value="0" {{ (string)$status === '0' ? 'selected' : '' }}>Draft</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.projects.bulk') }}" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Title</th>
              <th>Category</th>
              <th>Status</th>
              <th>Featured</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($projects as $project)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $project->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $project->title }}</strong>
                  <div class="text-muted small">{{ Str::limit($project->short_description, 70) }}</div>
                </td>
                <td>{{ $project->category?->name ?? 'Uncategorized' }}</td>
                <td><span class="badge bg-{{ $project->status ? 'success' : 'secondary' }}">{{ $project->status ? 'Published' : 'Draft' }}</span></td>
                <td>{{ $project->is_featured ? 'Yes' : 'No' }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('admin.projects.toggle-status', $project) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">{{ $project->status ? 'Unpublish' : 'Publish' }}</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No projects found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="publish">Publish</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="draft">Draft</button>
          <button type="button" class="btn btn-outline-danger btn-sm bulk-action" data-action="delete">Delete</button>
        </div>
        <div>{{ $projects->links() }}</div>
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
