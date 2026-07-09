@extends('layouts.app')

@section('title', 'Gallery Portfolio | Fancy Decorators')

@section('content')
<div class="container py-5">
  <div class="section-header text-center mb-5">
    <span class="section-label">Gallery Portfolio</span>
    <h1 class="display-6 fw-bold text-white">Visual Showcase</h1>
    <p class="text-muted">Explore a masonry collection of our premium construction, bespoke interior finishes, and master transformations.</p>
  </div>

  <div class="gallery-masonry">
    @forelse($projects as $project)
      @if($project->coverImage)
        <div class="gallery-item">
          <img src="{{ image_url($project->coverImage) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" />
          <div class="gallery-info p-3">
            <span class="text-uppercase fw-bold text-gold" style="font-size: 0.7rem; letter-spacing: 1px;">{{ $project->category?->name ?? 'Project' }}</span>
            <h5 class="fw-bold m-0 mt-1 text-white" style="font-size: 1rem; font-family: 'Montserrat', sans-serif;">{{ $project->title }}</h5>
            @if($project->location)
              <p class="text-muted small m-0 mt-1"><i class="fa-solid fa-location-dot me-1"></i>{{ $project->location }}</p>
            @endif
          </div>
        </div>
      @endif
    @empty
      <div class="col-12 text-center py-4">
        <p class="text-muted">No showcase images available at the moment.</p>
      </div>
    @endforelse
  </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal d-none" role="dialog" aria-modal="true">
  <button type="button" class="lightbox-close" aria-label="Close lightbox">&times;</button>
  <button type="button" class="lightbox-prev" aria-label="Previous image">&#10094;</button>
  <button type="button" class="lightbox-next" aria-label="Next image">&#10095;</button>
  <div class="lightbox-content">
    <img id="lightboxImg" src="" alt="Enlarged view" />
    <p id="lightboxCaption" class="lightbox-caption"></p>
  </div>
</div>
@endsection
