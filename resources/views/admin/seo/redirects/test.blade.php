@extends('admin.layouts.app')

@section('title', 'Redirection Test Results')
@section('page-title', 'Redirection Test Results')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm bg-white p-5">
            <h1 class="h4 font-heading mb-4"><i class="fa-solid fa-vial text-primary me-2"></i> Redirection Tester</h1>
            <p class="text-muted small">Evaluates how the system resolves the requested path.</p>

            <div class="mb-4 p-3 bg-light rounded-3 font-monospace">
                <strong>Testing Path:</strong> <code>{{ $path }}</code>
            </div>

            @if($match)
                <div class="alert alert-success border-0 p-4 mb-4 rounded-3">
                    <h5 class="fw-bold mb-2"><i class="fa-solid fa-circle-check me-2"></i> Match Found!</h5>
                    <p class="mb-3">The requested path successfully matches a registered redirection rule.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered bg-white mb-0 font-monospace text-start">
                            <tbody>
                                <tr>
                                    <th width="150" class="bg-light">Matched Rule</th>
                                    <td><code>{{ $match->source_url }}</code></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Redirect Target</th>
                                    <td><code>{{ $match->target_url }}</code></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">HTTP Code</th>
                                    <td><span class="badge bg-dark">{{ $match->type->value }}</span></td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Rule Type</th>
                                    <td>{{ $match->is_wildcard ? 'Wildcard Match' : 'Exact Match' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-warning border-0 p-4 mb-4 rounded-3">
                    <h5 class="fw-bold mb-2"><i class="fa-solid fa-circle-info me-2"></i> No Matches</h5>
                    <p class="mb-0">This path will load normally (returns 200 OK) or trigger standard Laravel router execution (unless dynamic page templates fail, returning 404).</p>
                </div>
            @endif

            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('admin.seo.redirects.index') }}" class="btn btn-outline-secondary">
                    Back to Redirects
                </a>
                <a href="{{ route('admin.seo.redirects.index', ['path' => $path]) }}" class="btn btn-primary px-4" style="background-color: var(--button-background, #b19356); border-color: var(--button-background, #b19356);">
                    Test Another Path
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
