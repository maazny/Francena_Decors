@extends('admin.layouts.app')

@section('title', 'URL Redirections')
@section('page-title', 'Redirection Manager')
@section('page-description', 'Manage HTTP 301 and 302 route redirection mappings, wildcard matching, and incoming target resolution tests.')

@section('content')
<div class="row g-4">
    <!-- Test Path Validator Tool -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-white p-4">
            <h5 class="card-title h6 mb-3 fw-bold"><i class="fa-solid fa-vial text-primary me-2"></i> Route Redirection Tester</h5>
            <p class="text-muted small">Input a request path location to test matching rules resolving circular redirects.</p>
            
            <form method="GET" action="{{ route('admin.seo.redirects.test') }}">
                <div class="mb-3">
                    <label for="test_path" class="form-label small text-muted uppercase fw-semibold">Test Request Path</label>
                    <input type="text" name="path" id="test_path" class="form-control" placeholder="e.g. /old-blog/some-page" value="{{ request('path') }}" required>
                </div>
                <button type="submit" class="btn btn-outline-dark btn-sm w-100">
                    Test Redirect Rules
                </button>
            </form>
        </div>
    </div>

    <!-- Redirects List -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('admin.seo.redirects.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Redirect Rule
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm bg-white">
            <div class="card-header bg-white py-3">
                <h5 class="card-title h6 mb-0 fw-bold">Active Mappings</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Source URL</th>
                            <th>Destination URL</th>
                            <th>Type</th>
                            <th>Hits</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($redirects as $redirect)
                            <tr>
                                <td>
                                    <code>{{ $redirect->source_url }}</code>
                                    @if($redirect->is_wildcard)
                                        <span class="badge bg-info-subtle text-info small ms-1">Wildcard</span>
                                    @endif
                                </td>
                                <td><code>{{ $redirect->target_url }}</code></td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $redirect->type->value }}</span>
                                </td>
                                <td>{{ $redirect->hit_count }}</td>
                                <td>
                                    @if($redirect->is_active)
                                        <span class="badge bg-success-subtle text-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Disabled</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.seo.redirects.edit', $redirect->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.seo.redirects.destroy', $redirect->id) }}" class="d-inline delete-form">
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
                                <td colspan="6" class="text-center py-4 text-muted">No redirects configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($redirects->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $redirects->links() }}
                </div>
            @endif
        </div>
    </div>
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
                text: "Deleting this rule will restore standard URL routing actions, possibly triggering 404 errors for legacy URLs.",
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
