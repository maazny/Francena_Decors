@extends('admin.layouts.app')

@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')
@section('page-description', 'Manage, edit, and publish blog articles.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Blog Posts</h2>
        <p class="text-muted mb-0">Create, update, and manage your articles and publications.</p>
      </div>
      <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm">New Post</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Search by title" value="{{ $search }}">
      </div>
      <div class="col-md-3">
        <select name="status" class="form-select">
          <option value="">All status</option>
          <option value="1" {{ (string)$status === '1' ? 'selected' : '' }}>Published / Active</option>
          <option value="0" {{ (string)$status === '0' ? 'selected' : '' }}>Draft / Inactive</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.blog-posts.bulk') }}" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Title</th>
              <th>Category</th>
              <th>Author</th>
              <th>Status</th>
              <th>Featured</th>
              <th>Homepage</th>
              <th>Published At</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($posts as $post)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $post->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $post->title }}</strong>
                  <div class="text-muted small">Reading Time: {{ $post->reading_time }} min</div>
                  @if($post->trashed())
                    <span class="badge bg-danger">Deleted</span>
                  @endif
                </td>
                <td>{{ $post->category?->name ?? 'Uncategorized' }}</td>
                <td>{{ $post->author?->name ?? 'N/A' }}</td>
                <td>
                  <span class="badge bg-{{ $post->status ? 'success' : 'secondary' }}">
                    {{ $post->status ? 'Published' : 'Draft' }}
                  </span>
                </td>
                <td>{{ $post->is_featured ? 'Yes' : 'No' }}</td>
                <td>{{ $post->is_homepage_featured ? 'Yes' : 'No' }}</td>
                <td>{{ $post->published_at ? $post->published_at->format('M d, Y H:i') : 'Immediate' }}</td>
                <td class="text-end">
                  @if($post->trashed())
                    <form action="{{ route('admin.blog-posts.restore', $post->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.blog-posts.toggle-status', $post) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-secondary">{{ $post->status ? 'Unpublish' : 'Publish' }}</button>
                    </form>
                    <form action="{{ route('admin.blog-posts.duplicate', $post) }}" method="POST" class="d-inline">
                      @csrf
                      <button class="btn btn-sm btn-outline-info">Duplicate</button>
                    </form>
                    <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="9" class="text-center text-muted py-4">No blog posts found.</td></tr>
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
        <div>{{ $posts->links() }}</div>
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
