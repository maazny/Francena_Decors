@extends('admin.layouts.app')

@section('title', 'Services')
@section('page-title', 'Services')
@section('page-description', 'Manage the service offerings, publish status, and FAQ content for each service.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Services</h2>
        <p class="text-muted mb-0">Create and manage service pages, categories, and FAQ entries.</p>
      </div>
      <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">New Service</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search services" value="{{ $search }}">
      </div>
      <div class="col-md-3">
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

    <form method="POST" action="{{ route('admin.services.bulk') }}" id="bulk-form">
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
            @forelse($services as $service)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $service->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $service->title }}</strong>
                  <div class="text-muted small">{{ Str::limit($service->short_description, 70) }}</div>
                </td>
                <td>{{ $service->category?->name ?? 'Uncategorized' }}</td>
                <td><span class="badge bg-{{ $service->status ? 'success' : 'secondary' }}">{{ $service->status ? 'Published' : 'Draft' }}</span></td>
                <td>{{ $service->is_featured ? 'Yes' : 'No' }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">{{ $service->status ? 'Unpublish' : 'Publish' }}</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No services found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="btn-group">
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="activate">Publish</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="deactivate">Draft</button>
          <button type="button" class="btn btn-outline-danger btn-sm bulk-action" data-action="delete">Delete</button>
          <button type="button" class="btn btn-outline-secondary btn-sm bulk-action" data-action="restore">Restore</button>
        </div>
        <div>{{ $services->links() }}</div>
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
