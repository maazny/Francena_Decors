@extends('admin.layouts.app')

@section('title', 'Page SEO Overrides')
@section('page-title', 'Page SEO Manager')
@section('page-description', 'Manage specific meta tags, og graphs, indexing options, and structured scripts per page route.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.seo.pages.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Page Override
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
        <h5 class="card-title h6 mb-0 fw-bold">Configured Pages</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Page / Key</th>
                    <th>Type</th>
                    <th>Slug Path</th>
                    <th>Meta Title</th>
                    <th>Structured Data</th>
                    <th>Robots</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>
                            <strong>{{ $page->page_key ?: 'Unnamed override' }}</strong>
                            @if($page->seo_pageable_type)
                                <span class="d-block small text-muted font-monospace">{{ class_basename($page->seo_pageable_type) }} #{{ $page->seo_pageable_id }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ ucfirst($page->page_type->value) }}</span>
                        </td>
                        <td><code>{{ $page->slug ?: '/' }}</code></td>
                        <td>{{ \Illuminate\Support\Str::limit($page->title, 40) ?: 'Default fallback' }}</td>
                        <td>
                            @if($page->structuredData->count() > 0)
                                <span class="badge bg-success-subtle text-success">{{ $page->structuredData->count() }} Schema(s)</span>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td><code>{{ $page->robots }}</code></td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.seo.pages.edit', $page->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Metadata">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.seo.pages.clone', $page->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info" title="Clone defaults to override">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.seo.pages.destroy', $page->id) }}" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Configuration">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-magnifying-glass fa-2x mb-3 d-block"></i>
                            No custom page metadata overrides configured yet.
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pages->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $pages->links() }}
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
                text: "This will remove the custom SEO overrides for this page, reverting it to global site settings defaults.",
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
