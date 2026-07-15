@extends('layouts.app')

@section('title', 'Our Services | Francena Decors')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.6), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 35vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Premium Construction</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Our Services</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">Services</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5" style="min-height: 60vh;">
  <div class="container">
    
    <!-- Filter and Search controls -->
    <div class="text-center mb-5">
      <div class="btn-group flex-wrap justify-content-center gap-2 mb-4" role="group" aria-label="Services filter">
        <a href="{{ route('services.index') }}" class="btn btn-outline-light rounded-pill px-4 active">All Services</a>
        @foreach($categories as $cat)
          <a href="{{ route('services.category', $cat->slug) }}" class="btn btn-outline-light rounded-pill px-4">{{ $cat->name }}</a>
        @endforeach
      </div>
      
      <div class="search-box mx-auto" role="search" aria-label="Search services" style="max-width: 480px;">
        <input id="serviceSearch" type="text" class="form-control search-input" placeholder="Search services..." aria-label="Search services" />
      </div>
    </div>

    <!-- Services Grid -->
    <div class="row g-4">
      @forelse($services as $service)
        <div class="col-12 col-md-6 col-lg-4">
          <article class="service-card glass-card p-4 h-100 d-flex flex-column justify-content-between" data-service="{{ strtolower($service->title) }} {{ strtolower($service->short_description) }}">
            <div>
              <div class="position-relative overflow-hidden rounded-3 mb-4" style="height: 220px;">
                @if($service->featuredImage)
                  <img src="{{ image_url($service->featuredImage) }}" alt="{{ $service->title }}" class="w-100 h-100 object-fit-cover" loading="lazy" />
                @else
                  <div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center text-muted">
                    <i class="fa-solid fa-image fa-3x"></i>
                  </div>
                @endif
                @if($service->category)
                  <span class="badge bg-gold position-absolute top-0 start-0 m-3 text-white fw-bold">{{ $service->category->name }}</span>
                @endif
              </div>
              
              <h3 class="h5 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
                <a href="{{ route('services.show', $service->slug) }}" class="text-decoration-none hover-gold text-white">
                  {{ $service->title }}
                </a>
              </h3>
              <p class="mb-4 text-muted small" style="line-height: 1.6;">{{ $service->short_description }}</p>
            </div>
            
            <a href="{{ route('services.show', $service->slug) }}" class="service-action-link mt-auto d-inline-flex align-items-center gap-2 text-decoration-none fw-bold" style="color: var(--gold); font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">
              Learn More <i class="fa-solid fa-arrow-right"></i>
            </a>
          </article>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="glass-card p-5">
            <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted"></i>
            <h5 class="text-white">No Services Available</h5>
            <p class="text-muted">Check back later or contact us for inquiries.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($services->hasPages())
      <div class="d-flex justify-content-center mt-5">
        {{ $services->links('pagination::bootstrap-5') }}
      </div>
    @endif

  </div>
</section>
@endsection

