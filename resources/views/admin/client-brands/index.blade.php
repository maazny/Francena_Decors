@extends('admin.layouts.app')

@section('title', 'Clients & Brands')
@section('page-title', 'Clients & Brands')
@section('page-description', 'Manage brands, partners, and featured client relationships.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Clients & Brands</h2>
        <p class="text-muted mb-0">Showcase trusted partners, clients, and brand affiliations.</p>
      </div>
      <a href="{{ route('admin.client-brands.create') }}" class="btn btn-primary btn-sm">New Entry</a>
    </div>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search name, category or description" value="{{ $search }}">
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select">
          <option value="">All status</option>
          <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
          <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
      </div>
      <div class="col-md-2">
        <select name="category" class="form-select">
          <option value="">All categories</option>
          @foreach($categories as $item)
            <option value="{{ $item }}" {{ $category === $item ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <select name="featured" class="form-select">
          <option value="">All featured</option>
          <option value="1" {{ $featured === '1' ? 'selected' : '' }}>Featured</option>
          <option value="0" {{ $featured === '0' ? 'selected' : '' }}>Not featured</option>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
      </div>
    </form>

    <form method="POST" action="{{ route('admin.client-brands.bulk') }}" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Name</th>
              <th>Category</th>
              <th>Status</th>
              <th>Featured</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($brands as $brand)
              <tr>
                <td><input type="checkbox" name="selected[]" value="{{ $brand->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $brand->name }}</strong>
                  <div class="text-muted small">{{ Str::limit(strip_tags($brand->description), 70) }}</div>
                </td>
                <td>{{ $brand->category ?? '—' }}</td>
                <td><span class="badge bg-{{ $brand->status === 'published' ? 'success' : 'secondary' }}">{{ ucfirst($brand->status) }}</span></td>
                <td>{{ $brand->featured ? 'Yes' : 'No' }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.client-brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                  <form action="{{ route('admin.client-brands.toggle-status', $brand) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">{{ $brand->status === 'published' ? 'Unpublish' : 'Publish' }}</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted py-4">No clients or brands found.</td></tr>
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
        <div>{{ $brands->links() }}</div>
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
