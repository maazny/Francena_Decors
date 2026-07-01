@extends('layouts.app')

@section('title', $projectCategory->name . ' Projects')
@section('content')
<div class="container py-5">
  <h1 class="display-6 fw-bold">{{ $projectCategory->name }}</h1>
  <p class="text-muted">{{ $projectCategory->description }}</p>

  <div class="row g-4 mt-3">
    @forelse($projects as $project)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="{{ $project->coverImage?->url ?? asset('images/default-project.jpg') }}" class="card-img-top" alt="{{ $project->title }}" style="height: 220px; object-fit: cover;">
          <div class="card-body">
            <h3 class="h5">{{ $project->title }}</h3>
            <p class="text-muted small">{{ \Illuminate\Support\Str::limit($project->short_description, 120) }}</p>
            <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-outline-dark btn-sm">View Project</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">No projects are available in this category yet.</div></div>
    @endforelse
  </div>
</div>
@endsection
