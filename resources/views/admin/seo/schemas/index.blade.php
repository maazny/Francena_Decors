@extends('admin.layouts.app')

@section('title', 'Structured Data JSON-LD Schemas')
@section('page-title', 'Structured Data Manager')
@section('page-description', 'Manage JSON-LD schemas, Organization profiles, local business mappings, and dynamic custom scripts.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.seo.structured-data.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Schema Mappings
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white py-3">
        <h5 class="card-title h6 mb-0 fw-bold">Structured Schema Blocks</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Page Context</th>
                    <th>Slug Path</th>
                    <th>Schema Type</th>
                    <th>Validation State</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schemas as $schema)
                    <tr>
                        <td><strong>{{ $schema->page ? ($schema->page->page_key ?: 'Page Override') : 'Default (Site Global)' }}</strong></td>
                        <td><code>{{ $schema->page ? $schema->page->slug : '/' }}</code></td>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($schema->type->value) }}</span></td>
                        <td>
                            <span class="badge bg-success-subtle text-success">
                                <i class="fa-solid fa-check-circle me-1"></i> JSON Parsed
                            </span>
                        </td>
                        <td>
                            @if($schema->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Disabled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.seo.structured-data.edit', $schema->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.seo.structured-data.destroy', $schema->id) }}" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No structured schemas configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schemas->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $schemas->links() }}
        </div>
    @endif
</div>
@endsection

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this block removes the JSON-LD schema markup from being outputted in HTML templates.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
@endonce
