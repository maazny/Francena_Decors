@extends('admin.layouts.app')

@section('title', 'Contact Categories')
@section('page-title', 'Contact Categories')
@section('page-description', 'Manage categories to route customer inquiries.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Inquiry Categories</h2>
        <p class="text-muted mb-0">Define specific routing category names, slugs, and display order.</p>
      </div>
      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
        New Category
      </button>
    </div>

    <!-- Bulk Actions Form -->
    <form method="POST" action="" id="bulk-form">
      @csrf
      <input type="hidden" name="action" id="bulk-action" value="">
      <input type="hidden" name="status" id="bulk-status-value" value="">

      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="table-light">
            <tr>
              <th width="40"><input type="checkbox" id="select-all"></th>
              <th>Category Name</th>
              <th>Slug</th>
              <th>Display Order</th>
              <th>Status</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
              <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $category->id }}" class="bulk-checkbox"></td>
                <td>
                  <strong>{{ $category->name }}</strong>
                  @if($category->description)
                    <div class="text-muted small">{{ Str::limit($category->description, 60) }}</div>
                  @endif
                  @if($category->trashed())
                    <span class="badge bg-danger">Deleted</span>
                  @endif
                </td>
                <td><code>{{ $category->slug }}</code></td>
                <td>{{ $category->display_order }}</td>
                <td>
                  <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                    {{ $category->status ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="text-end">
                  @if($category->trashed())
                    <form action="{{ route('admin.contacts.categories.restore', $category->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                      Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-status-btn" data-url="{{ route('admin.contacts.categories.toggle-status', $category) }}">
                      Toggle Status
                    </button>
                    <form action="{{ route('admin.contacts.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                  <form action="{{ route('admin.contacts.categories.update', $category) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Category</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                        <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="{{ $category->display_order }}">
                      </div>
                      <div class="form-check form-switch">
                        <input type="checkbox" name="status" value="1" class="form-check-input" id="editStatus{{ $category->id }}" {{ $category->status ? 'checked' : '' }}>
                        <label class="form-check-label" for="editStatus{{ $category->id }}">Active Status</label>
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

      <!-- Bulk Actions Controls -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="d-flex align-items-center gap-2">
          <div class="btn-group">
            <button type="button" class="btn btn-outline-danger btn-sm bulk-btn" data-action="delete">Delete Selected</button>
          </div>
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
              Update Status
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item status-bulk-item" href="#" data-status="1">Active</a></li>
              <li><a class="dropdown-item status-bulk-item" href="#" data-status="0">Inactive</a></li>
            </ul>
          </div>
        </div>
        <div>
          {{ $categories->links() }}
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.contacts.categories.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required placeholder="e.g. Support Request">
        </div>
        <div class="mb-3">
          <label class="form-label">Slug (Optional)</label>
          <input type="text" name="slug" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="0">
        </div>
        <div class="form-check form-switch">
          <input type="checkbox" name="status" value="1" class="form-check-input" id="createStatus" checked>
          <label class="form-check-label" for="createStatus">Active Status</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create Category</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    const bulkForm = document.getElementById('bulk-form');
    const bulkAction = document.getElementById('bulk-action');
    const bulkStatusVal = document.getElementById('bulk-status-value');

    selectAll?.addEventListener('change', function () {
      checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.querySelectorAll('.bulk-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one item.');
          return;
        }

        const action = btn.dataset.action;
        if (action === 'delete' && !confirm('Are you sure you want to delete the selected categories?')) {
          return;
        }

        bulkAction.value = action;
        bulkForm.action = "{{ route('admin.contacts.categories.bulk-delete') }}";
        bulkForm.submit();
      });
    });

    document.querySelectorAll('.status-bulk-item').forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one item.');
          return;
        }

        bulkAction.value = 'status';
        bulkStatusVal.value = item.dataset.status;
        bulkForm.action = "{{ route('admin.contacts.categories.bulk-status') }}";
        bulkForm.submit();
      });
    });

    // Toggle status ajax handler
    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const url = btn.dataset.url;
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });
        if (response.ok) {
          window.location.reload();
        } else {
          alert('Failed to update status.');
        }
      });
    });
  });
</script>
@endsection
