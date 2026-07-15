@extends('layouts.app')

@section('title', 'Our Projects | Francena Decors')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.6), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 35vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Crafting Elegance</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Our Projects</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">Projects</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5" style="min-height: 60vh;">
  <div class="container">
    
    <!-- Category Filter toolbar -->
    <div class="text-center mb-5">
      <div class="btn-group flex-wrap justify-content-center gap-2 mb-4" role="group" aria-label="Project filter">
        <a href="{{ route('projects.index') }}" class="btn btn-outline-light rounded-pill px-4 active">All Projects</a>
        @foreach(\App\Models\ProjectCategory::active()->ordered()->get() as $cat)
          <a href="{{ route('projects.category', $cat->slug) }}" class="btn btn-outline-light rounded-pill px-4">{{ $cat->name }}</a>
        @endforeach
      </div>
    </div>

    <!-- Featured Project Showcase -->
    @if($featuredProject)
      <div class="card border-0 glass-card overflow-hidden mb-5 p-0">
        <div class="row g-0">
          <div class="col-lg-6" style="height: 400px;">
            <img src="{{ $featuredProject->coverImage?->url ?? asset('images/default-project.jpg') }}" alt="{{ $featuredProject->title }}" class="w-100 h-100 object-fit-cover">
          </div>
          <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center">
            <span class="badge bg-gold align-self-start mb-3 text-white fw-bold">Featured Masterpiece</span>
            <h2 class="h3 fw-bold text-white mb-3 font-serif" style="font-family: 'Playfair Display', serif;">{{ $featuredProject->title }}</h2>
            <p class="text-muted small mb-4" style="line-height: 1.6;">{{ $featuredProject->short_description }}</p>
            
            <div class="d-flex align-items-center gap-3 text-muted small mb-4">
              @if($featuredProject->location)
                <span><i class="fa-solid fa-location-dot me-1.5 text-gold"></i>{{ $featuredProject->location }}</span>
              @endif
              @if($featuredProject->category)
                <span><i class="fa-solid fa-folder me-1.5 text-gold"></i>{{ $featuredProject->category->name }}</span>
              @endif
            </div>
            
            <a href="{{ route('projects.show', $featuredProject->slug) }}" class="btn btn-gold rounded-pill px-4 py-2.5" style="width: fit-content;">
              Explore Project <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    @endif

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
        <div class="col-12 text-center py-5">
          <div class="glass-card p-5">
            <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted"></i>
            <h5 class="text-white">No Projects Available</h5>
            <p class="text-muted">No projects have been published yet.</p>
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

