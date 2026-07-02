@extends('admin.layouts.app')

@section('title', 'Blog Tags')
@section('page-title', 'Blog Tags')
@section('page-description', 'Manage tags to organize blog posts.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Blog Tags</h2>
        <p class="text-muted mb-0">Create and manage the tags used across the blog posts.</p>
      </div>
      <a href="{{ route('admin.blog-tags.create') }}" class="btn btn-primary btn-sm">New Tag</a>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="card bg-dark text-white p-3 shadow-sm border-0">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-uppercase small mb-1 text-muted">Total Tags</h6>
              <h3 class="mb-0">{{ \App\Models\BlogTag::withTrashed()->count() }}</h3>
            </div>
            <i class="fa-solid fa-tags fa-2x opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-success text-white p-3 shadow-sm border-0">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-uppercase small mb-1 text-white-50">Active</h6>
              <h3 class="mb-0">{{ \App\Models\BlogTag::active()->count() }}</h3>
            </div>
            <i class="fa-solid fa-circle-check fa-2x opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-warning text-white p-3 shadow-sm border-0">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-uppercase small mb-1 text-white-50">Inactive</h6>
              <h3 class="mb-0">{{ \App\Models\BlogTag::where('status', false)->count() }}</h3>
            </div>
            <i class="fa-solid fa-circle-xmark fa-2x opacity-50"></i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-danger text-white p-3 shadow-sm border-0">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h6 class="text-uppercase small mb-1 text-white-50">Trashed</h6>
              <h3 class="mb-0">{{ \App\Models\BlogTag::onlyTrashed()->count() }}</h3>
            </div>
            <i class="fa-solid fa-trash-can fa-2x opacity-50"></i>
          </div>
        </div>
      </div>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search tags" value="{{ $search }}">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.blog-tags.bulk') }}" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Name</th>
              <th>Slug</th>
              <th>Display Order</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tags as $tag)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $tag->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $tag->name }}</strong>
                  @if($tag->trashed())
                    <span class="badge bg-danger ms-1">Deleted</span>
                  @endif
                </td>
                <td>{{ $tag->slug }}</td>
                <td>{{ $tag->display_order }}</td>
                <td>
                  <span class="badge bg-{{ $tag->status ? 'success' : 'secondary' }}">
                    {{ $tag->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-end">
                  @if($tag->trashed())
                    <form action="{{ route('admin.blog-tags.restore', $tag->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <a href="{{ route('admin.blog-tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.blog-tags.toggle-status', $tag) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-secondary">{{ $tag->status ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                    <form action="{{ route('admin.blog-tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No blog tags found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="activate">Activate</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="deactivate">Deactivate</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="restore">Restore</button>
          <button type="button" class="btn btn-outline-danger btn-sm bulk-action" data-action="delete">Delete</button>
        </div>
        <div>{{ $tags->links() }}</div>
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
      if (document.querySelectorAll('.bulk-checkbox:checked').length === 0) {
        alert('Please select at least one item.');
        return;
      }
      if (this.dataset.action === 'delete' && !confirm('Are you sure you want to delete the selected items?')) {
        return;
      }
      document.getElementById('bulk-action').value = this.dataset.action;
      document.getElementById('bulk-form').submit();
    });
  });
</script>
@endsection
