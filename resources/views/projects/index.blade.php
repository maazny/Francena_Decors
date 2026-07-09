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
      <div class="col-12 col-md-6 col-lg-4">
        <article class="project-card position-relative overflow-hidden" style="height: 380px;">
          @if($project->coverImage)
            <img src="{{ image_url($project->coverImage) }}" alt="{{ $project->title }}" class="w-100 h-100 object-fit-cover project-cover-img" loading="lazy" decoding="async" />
          @else
            <div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center text-muted">
              <i class="fa-solid fa-image fa-3x"></i>
            </div>
          @endif
          
          <!-- Hover Overlay -->
          <div class="project-overlay position-absolute d-flex flex-column justify-content-end p-4" style="background: linear-gradient(180deg, rgba(17,17,17,0) 0%, rgba(17,17,17,0.92) 80%);">
            <span class="project-category text-uppercase fw-bold mb-1" style="font-size: 0.75rem; color: var(--gold); letter-spacing: 1px;">{{ $project->category?->name }}</span>
            <h4 class="project-title fw-bold mb-1" style="font-size: 1.35rem; color: #FFFFFF !important; font-family: 'Montserrat', sans-serif;">{{ $project->title }}</h4>
            <p class="project-location mb-3 text-muted small"><i class="fa-solid fa-location-dot me-1"></i>{{ $project->location ?: 'Metropolitan Area' }}</p>
            
            <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-gold btn-sm rounded-pill px-3" style="width: fit-content;">
              View Project <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </article>
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
