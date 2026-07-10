@extends('layouts.app')

@section('title', $service->seo_title ?: $service->title)
@section('meta_description', $service->seo_description ?: $service->short_description)
@section('meta_keywords', $service->seo_keywords)
@section('og_title', $service->seo_title ?: $service->title)
@section('og_description', $service->seo_description ?: $service->short_description)
@section('og_type', 'website')
@section('og_url', route('services.show', $service->slug))
@section('og_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('twitter_title', $service->seo_title ?: $service->title)
@section('twitter_description', $service->seo_description ?: $service->short_description)
@section('twitter_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('canonical', route('services.show', $service->slug))

@extends('layouts.app')

@section('title', ($service->seo_title ?: $service->title) . ' | Fancy Decorators')
@section('meta_description', $service->seo_description ?: $service->short_description)
@section('meta_keywords', $service->seo_keywords)
@section('og_title', $service->seo_title ?: $service->title)
@section('og_description', $service->seo_description ?: $service->short_description)
@section('og_type', 'website')
@section('og_url', route('services.show', $service->slug))
@section('og_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('twitter_title', $service->seo_title ?: $service->title)
@section('twitter_description', $service->seo_description ?: $service->short_description)
@section('twitter_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('canonical', route('services.show', $service->slug))

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.5), rgba(5, 4, 7, 0.95)), url('{{ $service->bannerImage?->url ?? $service->featuredImage?->url ?? 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80' }}') no-repeat center center/cover; min-height: 45vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    @if($service->category)
      <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">{{ $service->category->name }}</span>
    @endif
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $service->title }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-white-50 text-decoration-none">Services</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $service->title }}</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8">
        
        <!-- Service Image & Content -->
        <div class="mb-5">
          <div class="rounded-4 overflow-hidden mb-4 shadow-lg border border-secondary" style="height: 420px;">
            <img src="{{ $service->bannerImage?->url ?? $service->featuredImage?->url ?? asset('images/default-service.jpg') }}" alt="{{ $service->title }}" class="w-100 h-100 object-fit-cover">
          </div>
          
          <h2 class="h3 fw-bold text-white mb-3 font-serif" style="font-family: 'Playfair Display', serif;">Overview</h2>
          <div class="text-muted leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
            {!! nl2br(e($service->description)) !!}
          </div>
        </div>

        <!-- Key Features -->
        @if($service->activeFeatures->isNotEmpty())
          <section class="mb-5">
            <h2 class="h3 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Key Features</h2>
            <div class="row g-4">
              @foreach($service->activeFeatures as $feature)
                <div class="col-md-6">
                  <div class="card h-100 p-4 border-0 glass-card">
                    <div class="d-flex align-items-center mb-3">
                      <div class="p-2 bg-dark rounded text-primary me-3" style="color: var(--gold) !important; background: rgba(206, 154, 95, 0.18) !important;">
                        <i class="fa-solid fa-circle-check fa-lg"></i>
                      </div>
                      <h4 class="h6 fw-bold mb-0 text-white">{{ $feature->title }}</h4>
                    </div>
                    <p class="mb-0 text-muted small" style="line-height: 1.6;">{{ $feature->description }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        @endif

        <!-- Our Process -->
        @if($service->activeProcesses->isNotEmpty())
          <section class="mb-5">
            <h2 class="h3 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Our Process</h2>
            <div class="row g-4">
              @foreach($service->activeProcesses as $index => $process)
                <div class="col-md-6">
                  <div class="process-card p-4 h-100">
                    <div class="process-step">{{ $index + 1 }}</div>
                    <h5 class="fw-bold text-white mb-2">{{ $process->title }}</h5>
                    <p class="mb-0 text-muted small" style="line-height: 1.6;">{{ $process->description }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        @endif

        <!-- FAQs -->
        @if($service->activeFaqs->isNotEmpty())
          <section id="faq" class="mb-5">
            <h2 class="h3 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Frequently Asked Questions</h2>
            <div class="accordion" id="serviceFaqAccordion">
              @foreach($service->activeFaqs as $index => $faq)
                <div class="accordion-item glass-card mb-3 border-0">
                  <h2 class="accordion-header" id="serviceFaqHeading{{ $index }}">
                    <button class="accordion-button collapsed text-white" type="button" data-bs-toggle="collapse" data-bs-target="#serviceFaqCollapse{{ $index }}" style="background: transparent; box-shadow: none;">
                      {{ $faq->question }}
                    </button>
                  </h2>
                  <div id="serviceFaqCollapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#serviceFaqAccordion">
                    <div class="accordion-body text-muted small" style="line-height: 1.6; border-top: 1px solid rgba(255,255,255,0.06);">
                      {{ $faq->answer }}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        @endif

      </div>

      <!-- Sidebar -->
      <aside class="col-lg-4">
        
        <!-- Specs Info Card -->
        <div class="card border-0 glass-card p-4 mb-4">
          <h4 class="h5 fw-bold font-serif mb-4 pb-2 border-bottom border-secondary text-primary" style="color: var(--gold); font-family: 'Playfair Display', serif;">Service Details</h4>
          
          <ul class="list-unstyled mb-4" style="line-height: 2;">
            @if($service->category)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Category:</span>
                <span class="fw-semibold text-white">{{ $service->category->name }}</span>
              </li>
            @endif
            @if($service->starting_price)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-tags text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Starting Price:</span>
                <span class="fw-bold text-warning">{{ $service->price_label }}</span>
              </li>
            @endif
            @if($service->duration)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-hourglass-half text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Duration:</span>
                <span class="fw-semibold text-white">{{ $service->duration }}</span>
              </li>
            @endif
            @if($service->location)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-location-dot text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Location:</span>
                <span class="fw-semibold text-white">{{ $service->location }}</span>
              </li>
            @endif
          </ul>

          <a href="{{ route('contact.index') }}" class="btn btn-gold w-100 rounded-pill py-2.5">
            Book Consultation
          </a>
        </div>

        <!-- Related Services -->
        @if($relatedServices->isNotEmpty())
          <div class="card border-0 glass-card p-4">
            <h4 class="h5 fw-bold font-serif mb-3 pb-2 border-bottom border-secondary" style="font-family: 'Playfair Display', serif;">Related Services</h4>
            <div class="d-flex flex-column gap-3">
              @foreach($relatedServices as $related)
                <div class="d-flex gap-3 align-items-center">
                  @if($related->featuredImage)
                    <img src="{{ image_url($related->featuredImage) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="Related cover">
                  @else
                    <div class="bg-secondary rounded text-center d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px;">
                      <i class="fa-solid fa-briefcase"></i>
                    </div>
                  @endif
                  <div>
                    <h6 class="small mb-1">
                      <a href="{{ route('services.show', $related->slug) }}" class="text-white text-decoration-none fw-semibold hover-gold">
                        {{ $related->title }}
                      </a>
                    </h6>
                    @if($related->category)
                      <span class="small text-muted" style="font-size: 0.75rem;">{{ $related->category->name }}</span>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </aside>
    </div>
  </div>
</section>
@endsection

