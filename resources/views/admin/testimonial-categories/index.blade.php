@extends('admin.layouts.app')

@section('title', 'Testimonial Categories')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Testimonial Categories</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.testimonial-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Category
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search categories..."
                        value="{{ $search }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            @if ($categories->count())
                <form method="POST" id="bulkActionForm">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="bulkAction">
                            <option value="">-- Bulk Actions --</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                            <option value="restore">Restore</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px">
                                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                    </th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th style="width: 150px">Testimonials</th>
                                    <th style="width: 100px">Status</th>
                                    <th style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr class="@if ($category->deleted_at) table-secondary @endif">
                                        <td>
                                            <input type="checkbox" class="form-check-input itemCheckbox"
                                                name="selected[]" value="{{ $category->id }}">
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if ($category->deleted_at)
                                                <span class="badge bg-danger ms-2">Deleted</span>
                                            @endif
                                        </td>
                                        <td><code>{{ $category->slug }}</code></td>
                                        <td>
                                            <span class="badge bg-info">{{ $category->testimonials_count }}</span>
                                        </td>
                                        <td>
                                            @if ($category->deleted_at)
                                                <span class="badge bg-secondary">Archived</span>
                                            @else
                                                <span
                                                    class="badge @if ($category->status) bg-success @else bg-danger @endif">
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if (! $category->deleted_at)
                                                    <a href="{{ route('admin.testimonial-categories.edit', $category) }}"
                                                        class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonial-categories.toggle-status', $category) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning"
                                                            title="Toggle Status"
                                                            onclick="return confirm('Toggle status?')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonial-categories.destroy', $category) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Delete this category?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonial-categories.restore', $category->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success"
                                                            title="Restore"
                                                            onclick="return confirm('Restore this category?')">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of
                        {{ $categories->total() }} categories
                    </div>
                    {{ $categories->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No testimonial categories found.
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('selectAllCheckbox')?.addEventListener('change', function() {
            document.querySelectorAll('.itemCheckbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('bulkAction')?.addEventListener('change', function() {
            if (this.value) {
                const selected = document.querySelectorAll('.itemCheckbox:checked');
                if (selected.length === 0) {
                    alert('Please select at least one item');
                    this.value = '';
                    return;
                }
                document.querySelector('input[name="action"]').value = this.value;
                document.getElementById('bulkActionForm').submit();
            }
        });

        document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
            const action = document.getElementById('bulkAction').value;
            if (!action) {
                e.preventDefault();
            }
        });
    </script>
@endpush
@endsection
