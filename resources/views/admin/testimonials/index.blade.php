@extends('admin.layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Testimonials</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Testimonial
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search client or company..."
                        value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- All Status --</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="published" @selected($status === 'published')>Published</option>
                        <option value="archived" @selected($status === 'archived')>Archived</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">-- All Categories --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-select">
                        <option value="">-- All Ratings --</option>
                        <option value="5" @selected($rating === '5')>5 Stars</option>
                        <option value="4" @selected($rating === '4')>4 Stars</option>
                        <option value="3" @selected($rating === '3')>3 Stars</option>
                        <option value="2" @selected($rating === '2')>2 Stars</option>
                        <option value="1" @selected($rating === '1')>1 Star</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>

            @if ($testimonials->count())
                <form method="POST" id="bulkActionForm">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="bulkAction">
                            <option value="">-- Bulk Actions --</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
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
                                    <th>Client</th>
                                    <th>Company</th>
                                    <th>Rating</th>
                                    <th>Category</th>
                                    <th style="width: 100px">Status</th>
                                    <th style="width: 200px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $testimonial)
                                    <tr class="@if ($testimonial->deleted_at) table-secondary @endif">
                                        <td>
                                            <input type="checkbox" class="form-check-input itemCheckbox"
                                                name="selected[]" value="{{ $testimonial->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($testimonial->clientPhoto)
                                                    <img src="{{ $testimonial->clientPhoto->thumbnail_url }}"
                                                        alt="{{ $testimonial->client_name }}" class="rounded-circle"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-secondary"
                                                        style="width: 32px; height: 32px;"></div>
                                                @endif
                                                <div>
                                                    <strong>{{ $testimonial->client_name }}</strong>
                                                    @if ($testimonial->deleted_at)
                                                        <span class="badge bg-danger ms-2">Deleted</span>
                                                    @endif
                                                    @if ($testimonial->featured)
                                                        <span class="badge bg-warning">Featured</span>
                                                    @endif
                                                    @if ($testimonial->homepage_featured)
                                                        <span class="badge bg-success">Homepage</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $testimonial->client_company ?? '-' }}</td>
                                        <td>
                                            <div class="text-warning">
                                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @for ($i = $testimonial->rating; $i < 5; $i++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>
                                            @if ($testimonial->category)
                                                <span class="badge bg-info">{{ $testimonial->category->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($testimonial->deleted_at)
                                                <span class="badge bg-secondary">Archived</span>
                                            @else
                                                <span
                                                    class="badge @if ($testimonial->status === 'published') bg-success @elseif ($testimonial->status === 'draft') bg-warning @else bg-danger @endif">
                                                    {{ ucfirst($testimonial->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                @if (! $testimonial->deleted_at)
                                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                                        class="btn btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonials.toggle-status', $testimonial) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning"
                                                            title="Toggle Status"
                                                            onclick="return confirm('Toggle status?')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonials.duplicate', $testimonial) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info"
                                                            title="Duplicate"
                                                            onclick="return confirm('Duplicate this testimonial?')">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                            title="Delete"
                                                            onclick="return confirm('Delete this testimonial?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('admin.testimonials.restore', $testimonial->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success"
                                                            title="Restore"
                                                            onclick="return confirm('Restore this testimonial?')">
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
                        Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of
                        {{ $testimonials->total() }} testimonials
                    </div>
                    {{ $testimonials->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No testimonials found.
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
