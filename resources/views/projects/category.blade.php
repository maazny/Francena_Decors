@extends('layouts.app')

@section('title', $projectCategory->name . ' Projects | Francena Decors')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.6), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 35vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Project Category</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $projectCategory->name }}</h1>
    <p class="text-white-50 lead fs-6 max-w-sm mx-auto mb-0" style="max-width: 600px;">{{ $projectCategory->description }}</p>
    <nav aria-label="breadcrumb" class="mt-3">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-white-50 text-decoration-none">Projects</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $projectCategory->name }}</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5" style="min-height: 60vh;">
  <div class="container">
    
    <!-- Category Filter toolbar -->
    <div class="text-center mb-5">
      <div class="btn-group flex-wrap justify-content-center gap-2 mb-4" role="group" aria-label="Project filter">
        <a href="{{ route('projects.index') }}" class="btn btn-outline-light rounded-pill px-4">All Projects</a>
        @foreach(\App\Models\ProjectCategory::active()->ordered()->get() as $cat)
          <a href="{{ route('projects.category', $cat->slug) }}" class="btn btn-outline-light rounded-pill px-4 @if($projectCategory->id === $cat->id) active @endif">{{ $cat->name }}</a>
        @endforeach
      </div>
    </div>

    <!-- Projects Grid -->
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
              <span class="project-category text-uppercase fw-bold mb-1" style="font-size: 0.75rem; color: var(--gold); letter-spacing: 1px;">{{ $projectCategory->name }}</span>
              <h4 class="project-title fw-bold mb-1" style="font-size: 1.35rem; color: #FFFFFF !important; font-family: 'Montserrat', sans-serif;">{{ $project->title }}</h4>
              <p class="project-location mb-3 text-muted small"><i class="fa-solid fa-location-dot me-1"></i>{{ $project->location ?: 'Metropolitan Area' }}</p>
              
              <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-gold btn-sm rounded-pill px-3" style="width: fit-content;">
                View Project <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="glass-card p-5">
            <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted"></i>
            <h5 class="text-white">No Projects Available</h5>
            <p class="text-muted">No projects are available in this category yet.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $projects->links('pagination::bootstrap-5') }}
      </div>
    @endif

  </div>
</section>
@endsection

