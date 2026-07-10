@extends('layouts.app')

@section('title', $testimonial->seo_title ?? $testimonial->client_name . ' Testimonial')
@section('meta_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('meta_keywords', $testimonial->seo_keywords)
@section('og_title', $testimonial->seo_title ?: $testimonial->client_name . ' Testimonial')
@section('og_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('og_type', 'website')
@section('og_url', route('testimonials.show', $testimonial))
@section('og_image', $testimonial->clientPhoto ? image_url($testimonial->clientPhoto) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('twitter_title', $testimonial->seo_title ?: $testimonial->client_name . ' Testimonial')
@section('twitter_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('twitter_image', $testimonial->clientPhoto ? image_url($testimonial->clientPhoto) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('canonical', route('testimonials.show', $testimonial))

@extends('layouts.app')

@section('title', ($testimonial->seo_title ?? $testimonial->client_name . ' Testimonial') . ' | Fancy Decorators')
@section('meta_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('meta_keywords', $testimonial->seo_keywords)
@section('og_title', $testimonial->seo_title ?: $testimonial->client_name . ' Testimonial')
@section('og_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('og_type', 'website')
@section('og_url', route('testimonials.show', $testimonial))
@section('og_image', $testimonial->clientPhoto ? image_url($testimonial->clientPhoto) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('twitter_title', $testimonial->seo_title ?: $testimonial->client_name . ' Testimonial')
@section('twitter_description', $testimonial->seo_description ?: $testimonial->testimonial)
@section('twitter_image', $testimonial->clientPhoto ? image_url($testimonial->clientPhoto) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('canonical', route('testimonials.show', $testimonial))

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.5), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 45vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Client Voice Detail</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $testimonial->client_name }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('testimonials.index') }}" class="text-white-50 text-decoration-none">Testimonials</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $testimonial->client_name }}</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8">
        
        <div class="card border-0 glass-card p-4 p-md-5 mb-4">
          
          <!-- Client Header Profile -->
          <div class="d-flex align-items-center mb-4 pb-4 border-bottom border-secondary flex-wrap gap-3">
            <div class="client-avatar-wrapper rounded-circle overflow-hidden shadow-lg me-3" style="width: 80px; height: 80px; border: 2px solid var(--gold);">
              @if ($testimonial->clientPhoto)
                <img src="{{ image_url($testimonial->clientPhoto) }}" alt="{{ $testimonial->client_name }}" class="w-100 h-100 object-fit-cover">
              @else
                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                  <i class="fa-solid fa-user fa-2xl"></i>
                </div>
              @endif
            </div>
            <div>
              <h3 class="h4 fw-bold text-white mb-1 font-serif" style="font-family: 'Playfair Display', serif;">{{ $testimonial->client_name }}</h3>
              <p class="mb-0 text-muted small">
                @if ($testimonial->client_designation)
                  {{ $testimonial->client_designation }}
                  @if ($testimonial->client_company)
                    • {{ $testimonial->client_company }}
                  @endif
                @elseif($testimonial->client_company)
                  {{ $testimonial->client_company }}
                @else
                  Client
                @endif
              </p>
              @if ($testimonial->location)
                <p class="mb-0 text-muted small mt-1">
                  <i class="fas fa-map-marker-alt me-1.5 text-gold"></i>{{ $testimonial->location }}
                </p>
              @endif
            </div>
            
            @if ($testimonial->clientLogo)
              <div class="ms-md-auto bg-dark p-2 rounded border border-secondary" style="height: 48px;">
                <img src="{{ image_url($testimonial->clientLogo) }}" alt="{{ $testimonial->client_company }}" class="h-100" style="object-fit: contain;">
              </div>
            @endif
          </div>

          <!-- Star Rating -->
          <div class="mb-4">
            <div class="text-warning mb-2" style="font-size: 1.15rem;">
              @for ($i = 0; $i < ($testimonial->rating ?: 5); $i++)
                <i class="fas fa-star"></i>
              @endfor
              @for ($i = ($testimonial->rating ?: 5); $i < 5; $i++)
                <i class="far fa-star"></i>
              @endfor
            </div>
          </div>

          <!-- Testimonial Content -->
          @if ($testimonial->title)
            <h4 class="mb-3 text-white font-serif" style="font-family: 'Playfair Display', serif; font-size: 1.25rem;">"{{ $testimonial->title }}"</h4>
          @endif
          <div class="testimonial-content mb-4" style="line-height: 1.8; font-size: 1.1rem; font-style: italic; color: rgba(255,255,255,0.85);">
            <p>"{{ $testimonial->testimonial }}"</p>
          </div>

          <!-- Video Testimonial Player -->
          @if ($testimonial->hasVideo())
            <div class="mb-4 pb-4 border-bottom border-secondary">
              <h5 class="mb-3 text-white font-serif" style="font-family: 'Playfair Display', serif;">Video Testimonial</h5>
              
              @if ($testimonial->youtube_url)
                @php
                  $youtubeId = '';
                  if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $testimonial->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                  } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $testimonial->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                  }
                @endphp
                @if ($youtubeId)
                  <div class="ratio ratio-16x9 rounded overflow-hidden shadow-lg border border-secondary">
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="Video Testimonial" allowfullscreen></iframe>
                  </div>
                @else
                  <a href="{{ $testimonial->youtube_url }}" target="_blank" class="btn btn-gold px-4 py-2.5">
                    <i class="fab fa-youtube me-2"></i> Watch Video on YouTube
                  </a>
                @endif
              @elseif($testimonial->video_url)
                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-lg border border-secondary">
                  <video controls class="w-100 h-100">
                    <source src="{{ $testimonial->video_url }}" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>
                </div>
              @endif
            </div>
          @endif

          <!-- Related Project -->
          @if ($testimonial->project)
            <div class="d-flex align-items-center gap-3 pt-3">
              <span class="small text-muted text-uppercase tracking-wider">Transformation:</span>
              <a href="{{ route('projects.show', $testimonial->project) }}" class="btn btn-gold btn-sm rounded-pill px-4">
                <i class="fas fa-folder me-1.5"></i> {{ $testimonial->project->title }}
              </a>
            </div>
          @endif

        </div>

      </div>

      <!-- Sidebar -->
      <aside class="col-lg-4">
        
        <!-- Category details box -->
        @if ($testimonial->category)
          <div class="card border-0 glass-card p-4 mb-4">
            <h4 class="h5 fw-bold font-serif mb-3 pb-2 border-bottom border-secondary text-primary" style="color: var(--gold); font-family: 'Playfair Display', serif;">Category</h4>
            <a href="{{ route('testimonials.category', $testimonial->category->slug) }}" class="btn btn-outline-light rounded-pill px-4 w-100 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
              {{ $testimonial->category->name }}
            </a>
          </div>
        @endif

        <!-- Related Testimonials Sidebar -->
        @if ($relatedTestimonials->count())
          <div class="card border-0 glass-card p-4">
            <h4 class="h5 fw-bold font-serif mb-4 pb-2 border-bottom border-secondary" style="font-family: 'Playfair Display', serif;">Related Voices</h4>
            <div class="d-flex flex-column gap-4">
              @foreach ($relatedTestimonials as $related)
                <div class="pb-3 border-bottom border-secondary last-border-none">
                  <div class="d-flex gap-2 mb-2 align-items-center">
                    <div class="rounded-circle overflow-hidden shadow" style="width: 40px; height: 40px; border: 1.5px solid var(--gold);">
                      @if ($related->clientPhoto)
                        <img src="{{ image_url($related->clientPhoto) }}" alt="{{ $related->client_name }}" class="w-100 h-100 object-fit-cover">
                      @else
                        <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                          <i class="fa-solid fa-user small"></i>
                        </div>
                      @endif
                    </div>
                    <div>
                      <h6 class="mb-0 small fw-bold">
                        <a href="{{ route('testimonials.show', $related) }}" class="text-white text-decoration-none hover-gold">
                          {{ $related->client_name }}
                        </a>
                      </h6>
                      <small class="text-muted" style="font-size: 0.7rem;">{{ $related->client_company ?? 'Client' }}</small>
                    </div>
                  </div>
                  <div class="text-warning mb-2" style="font-size: 0.75rem;">
                    @for ($i = 0; $i < ($related->rating ?: 5); $i++)
                      <i class="fas fa-star"></i>
                    @endfor
                  </div>
                  <p class="small text-muted mb-0" style="line-height: 1.5; font-style: italic;">
                    "{{ Str::limit($related->testimonial, 90) }}"
                  </p>
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

