@extends('admin.layouts.app')

@section('title', 'Blog Categories')
@section('page-title', 'Blog Categories')
@section('page-description', 'Manage and categorize your blog posts.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Blog Categories</h2>
        <p class="text-muted mb-0">Create and manage the categories used across the blog CMS.</p>
      </div>
      <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary btn-sm">New Category</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search categories" value="{{ $search }}">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.blog-categories.bulk') }}" id="bulk-form">
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
              <th>Posts</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $category->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $category->name }}</strong>
                  @if($category->trashed())
                    <span class="badge bg-danger ms-1">Deleted</span>
                  @endif
                </td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->display_order }}</td>
                <td>
                  <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                    {{ $category->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td>{{ $category->posts_count }}</td>
                <td class="text-end">
                  @if($category->trashed())
                    <form action="{{ route('admin.blog-categories.restore', $category->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <a href="{{ route('admin.blog-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.blog-categories.toggle-status', $category) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-secondary">{{ $category->status ? 'Deactivate' : 'Activate' }}</button>
                    </form>
                    <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted py-4">No blog categories found.</td></tr>
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
