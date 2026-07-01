@extends('layouts.app')

@section('title', 'Our Projects')
@section('content')
<div class="container py-5">
  <div class="row align-items-end mb-5">
    <div class="col-lg-8">
      <h1 class="display-6 fw-bold">Our Projects</h1>
      <p class="text-muted">A showcase of residential and commercial transformations completed with precision and care.</p>
    </div>
    <div class="col-lg-4 text-lg-end">
      <a href="{{ route('projects.index') }}" class="btn btn-outline-dark">View All</a>
    </div>
  </div>

  @if($featuredProject)
    <div class="card shadow-sm mb-5 border-0">
      <div class="row g-0">
        <div class="col-lg-6">
          <img src="{{ $featuredProject->coverImage?->url ?? asset('images/default-project.jpg') }}" alt="{{ $featuredProject->title }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 320px;">
        </div>
        <div class="col-lg-6 p-4 p-lg-5">
          <span class="badge bg-dark">Featured Project</span>
          <h2 class="h3 mt-3">{{ $featuredProject->title }}</h2>
          <p class="text-muted">{{ $featuredProject->short_description }}</p>
          <div class="d-flex flex-wrap gap-2 mb-3">
            @if($featuredProject->category)
              <span class="badge bg-light text-dark">{{ $featuredProject->category->name }}</span>
            @endif
          </div>
          <a href="{{ route('projects.show', $featuredProject->slug) }}" class="btn btn-dark">View Project</a>
        </div>
      </div>
    </div>
  @endif

  <div class="row g-4">
    @forelse($projects as $project)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="{{ $project->coverImage?->url ?? asset('images/default-project.jpg') }}" class="card-img-top" alt="{{ $project->title }}" style="height: 220px; object-fit: cover;">
          <div class="card-body">
            <div class="d-flex justify-content-between gap-2 mb-2">
              <span class="badge bg-light text-dark">{{ $project->category?->name ?? 'Project' }}</span>
              <span class="text-muted small">{{ $project->end_date ? \Illuminate\Support\Carbon::parse($project->end_date)->format('M Y') : ($project->start_date ? \Illuminate\Support\Carbon::parse($project->start_date)->format('M Y') : '') }}</span>
            </div>
            <h3 class="h5">{{ $project->title }}</h3>
            <p class="text-muted small">{{ \Illuminate\Support\Str::limit($project->short_description, 120) }}</p>
            <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-outline-dark btn-sm">Read More</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-light">No projects have been published yet.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-4">
    {{ $projects->links() }}
  </div>
</div>
@endsection
