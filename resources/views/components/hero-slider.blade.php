@php
  $slides = hero_slides();
  $firstSlide = $slides->first();

  $aboutData = about_cms();
  $aboutSection = $aboutData['section'] ?? null;
  $completedProjects = $aboutSection?->completed_projects ?? 500;
  $experienceYears = $aboutSection?->experience_years ?? 15;
  $happyClients = $aboutSection?->happy_clients ?? 98;
@endphp

@if($firstSlide)
  @push('head')
    @php
      $firstDesktopImage = image_url($firstSlide->desktopImage);
      $firstMobileImage = image_url($firstSlide->mobileImage) ?: $firstDesktopImage;
    @endphp
    @if($firstDesktopImage)
      <link rel="preload" href="{{ $firstDesktopImage }}" as="image" fetchpriority="high" media="(min-width: 768px)">
    @endif
    @if($firstMobileImage)
      <link rel="preload" href="{{ $firstMobileImage }}" as="image" fetchpriority="high" media="(max-width: 767px)">
    @endif
    <style>
      .hero-stats-bar {
        transition: all 0.3s ease;
      }
      .stats-glass-container {
        background: rgba(17, 17, 17, 0.45) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35) !important;
        max-width: 95%;
        border-radius: 50px;
      }
      
      /* Mobile styling: viewport under 768px */
      @media (max-width: 767.98px) {
        .hero-cms-slide h1 {
          font-size: clamp(2.2rem, 6.5vw, 3rem) !important;
          line-height: 1.15 !important;
          display: -webkit-box;
          -webkit-line-clamp: 3;
          -webkit-box-orient: vertical;
          overflow: hidden;
          text-overflow: ellipsis;
        }
        .hero-cms-slide .lead {
          font-size: 0.95rem !important;
          margin-top: 1rem !important;
        }
        .hero-buttons {
          display: flex;
          flex-direction: column;
          gap: 0.85rem;
          width: 100%;
          max-width: 290px;
          margin-left: auto;
          margin-right: auto;
        }
        .hero-buttons .btn {
          width: 100% !important;
          margin: 0 !important;
          padding: 0.9rem 1.75rem !important;
          font-size: 0.95rem !important;
        }
        .stats-glass-container {
          display: grid !important;
          grid-template-columns: repeat(2, 1fr) !important;
          gap: 1.25rem 1rem !important;
          border-radius: 24px !important;
          padding: 1.5rem 1.25rem !important;
          max-width: 100% !important;
          width: 100% !important;
        }
        .stats-glass-container .vr {
          display: none !important;
        }
        .hero-stats-bar {
          padding-bottom: 2rem !important;
        }
        .stat-item {
          display: flex;
          flex-direction: column !important;
          align-items: center !important;
          justify-content: center !important;
        }
        .stat-item span.text-uppercase {
          margin-left: 0 !important;
          margin-top: 0.25rem !important;
          font-size: 0.68rem !important;
        }
      }

      /* Tablet styling: viewport between 768px and 1024px */
      @media (min-width: 768px) and (max-width: 1024px) {
        .hero-cms-slide h1 {
          font-size: clamp(2.8rem, 5vw, 3.8rem) !important;
        }
        .hero-cms-slide .lead {
          font-size: 1.05rem !important;
        }
        .hero-buttons .btn {
          padding: 0.85rem 1.75rem !important;
        }
        .hero-buttons {
          display: flex;
          justify-content: center;
          gap: 1rem;
        }
      }
    </style>
  @endpush
@endif

@if($slides->isNotEmpty())
  <section id="home" class="hero-section hero-cms-section text-white">
    <div class="hero-shapes">
      <span class="floating-shape shape-1"></span>
      <span class="floating-shape shape-2"></span>
      <span class="floating-shape shape-3"></span>
      <span class="floating-shape shape-4"></span>
    </div>
    <canvas id="particleCanvas"></canvas>

    <div
      id="heroBootstrapCarousel"
      class="carousel slide carousel-fade h-100"
      @if($slides->count() > 1) data-bs-ride="carousel" data-bs-interval="8500" @endif
    >
      <div class="carousel-inner h-100">
        @foreach($slides as $slide)
          @php
            $desktopImage = image_url($slide->desktopImage);
            $mobileImage = image_url($slide->mobileImage) ?: $desktopImage;
            $videoUrl = media_url($slide->backgroundVideo);
            $positionClass = match ($slide->content_position) {
                'left' => 'justify-content-start',
                'right' => 'justify-content-end',
                default => 'justify-content-center',
            };
            $alignmentClass = match ($slide->text_alignment) {
                'start' => 'text-start',
                'end' => 'text-end',
                default => 'text-center',
            };
          @endphp

          <div
            class="carousel-item hero-cms-slide h-100 {{ $loop->first ? 'active' : '' }}"
            style="--hero-desktop-image: url('{{ $desktopImage }}'); --hero-mobile-image: url('{{ $mobileImage }}'); --hero-overlay: {{ $slide->overlay_rgba }};"
          >
            <!-- Background Image fallback with slow zoom -->
            <div class="hero-slide-bg-image"></div>

            @if($videoUrl)
              <video class="hero-background-video" autoplay muted loop playsinline preload="metadata">
                <source src="{{ $videoUrl }}" type="{{ $slide->backgroundVideo->mime_type }}">
              </video>
            @endif

            <div class="hero-cms-overlay"></div>
            <div class="container position-relative z-2 h-100">
              <div class="row h-100 align-items-center {{ $positionClass }}">
                <div class="col-lg-8 col-xl-7 {{ $alignmentClass }}">
                  <div
                    class="hero-cms-content {{ $slide->enable_animation ? 'hero-animate-'.$slide->animation_type : '' }}"
                    style="--hero-animation-duration: {{ $slide->animation_duration }}ms;"
                    @if($slide->enable_animation) data-aos="{{ $slide->animation_type }}" data-aos-duration="{{ $slide->animation_duration }}" @endif
                  >
                    @if($slide->badge_text)
                      <p class="eyebrow hero-cms-badge" style="--hero-badge-color: {{ $slide->badge_color ?: 'var(--gold)' }};">{{ $slide->badge_text }}</p>
                    @endif

                    @if($slide->subtitle)
                      <p class="hero-subtitle mb-3">{{ $slide->subtitle }}</p>
                    @endif

                    <h1 class="display-4 fw-bold">{!! nl2br(e($slide->title)) !!}</h1>

                    @if($slide->description)
                      <p class="lead mt-4">{{ $slide->description }}</p>
                    @endif

                    @if(($slide->button_one_text && $slide->button_one_url) || ($slide->button_two_text && $slide->button_two_url))
                      <div class="hero-buttons mt-5">
                        @if($slide->button_one_text && $slide->button_one_url)
                          <a href="{{ $slide->button_one_url }}" target="{{ $slide->button_one_target }}" class="btn btn-gold btn-lg" @if($slide->button_one_target === '_blank') rel="noopener" @endif>
                            {{ $slide->button_one_text }}
                          </a>
                        @endif

                        @if($slide->button_two_text && $slide->button_two_url)
                          <a href="{{ $slide->button_two_url }}" target="{{ $slide->button_two_target }}" class="btn btn-outline-light btn-lg" @if($slide->button_two_target === '_blank') rel="noopener" @endif>
                            {{ $slide->button_two_text }}
                          </a>
                        @endif
                      </div>
                    @endif

                    <!-- Trust Rating & Stats Badges -->
                    <div class="hero-trust-bar mt-4 d-flex flex-wrap align-items-center gap-3 gap-md-4 {{ $slide->text_alignment === 'start' ? 'justify-content-start' : ($slide->text_alignment === 'end' ? 'justify-content-end' : 'justify-content-center') }}" style="font-size: 0.85rem; letter-spacing: 1px; color: rgba(255, 255, 255, 0.85);">
                      
                      <!-- Rating -->
                      <div class="d-flex align-items-center gap-2">
                        <span class="stars text-warning d-inline-flex gap-0.5" style="color: var(--gold) !important; font-size: 0.9rem;">
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                          <i class="fa-solid fa-star"></i>
                        </span>
                        <span class="fw-semibold">4.9 Customer Rating</span>
                      </div>

                      <div class="d-none d-sm-block text-white-50 opacity-25">|</div>

                      <!-- Projects Count -->
                      <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check" style="color: var(--gold) !important;"></i>
                        <span class="fw-semibold">{{ $completedProjects }}+ Completed Projects</span>
                      </div>

                      <div class="d-none d-sm-block text-white-50 opacity-25">|</div>

                      <!-- Experience -->
                      <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-award" style="color: var(--gold) !important;"></i>
                        <span class="fw-semibold">{{ $experienceYears }}+ Years Experience</span>
                      </div>

                    </div>

                    <!-- Small Badges -->
                    <div class="hero-badges-row mt-4 d-flex flex-wrap gap-2 {{ $slide->text_alignment === 'start' ? 'justify-content-start' : ($slide->text_alignment === 'end' ? 'justify-content-end' : 'justify-content-center') }}">
                      <span class="badge rounded-pill px-3 py-2 border text-uppercase tracking-wider font-monospace" style="font-size: 0.65rem; background: rgba(212, 175, 95, 0.08); border-color: rgba(212, 175, 95, 0.25); color: var(--gold) !important;">Expert Craftsmanship</span>
                      <span class="badge rounded-pill px-3 py-2 border text-uppercase tracking-wider font-monospace" style="font-size: 0.65rem; background: rgba(212, 175, 95, 0.08); border-color: rgba(212, 175, 95, 0.25); color: var(--gold) !important;">Licensed & Insured</span>
                      <span class="badge rounded-pill px-3 py-2 border text-uppercase tracking-wider font-monospace" style="font-size: 0.65rem; background: rgba(212, 175, 95, 0.08); border-color: rgba(212, 175, 95, 0.25); color: var(--gold) !important;">On-Time Delivery</span>
                      <span class="badge rounded-pill px-3 py-2 border text-uppercase tracking-wider font-monospace" style="font-size: 0.65rem; background: rgba(212, 175, 95, 0.08); border-color: rgba(212, 175, 95, 0.25); color: var(--gold) !important;">Premium Quality</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      @if($slides->count() > 1)
        <div class="slider-controls" aria-label="Hero slider navigation">
          <button class="slider-btn" type="button" data-bs-target="#heroBootstrapCarousel" data-bs-slide="prev" aria-label="Previous slide">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
          </button>
          <button class="slider-btn" type="button" data-bs-target="#heroBootstrapCarousel" data-bs-slide="next" aria-label="Next slide">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
          </button>
        </div>
      @endif
    </div>

    <!-- Floating Stats Bar at the bottom of Hero -->
    @php
      $aboutData = about_cms();
      $aboutSection = $aboutData['section'] ?? null;
      $completedProjects = $aboutSection?->completed_projects ?? 500;
      $experienceYears = $aboutSection?->experience_years ?? 15;
      $teamMembers = $aboutSection?->team_members ?? 120;
      $happyClients = $aboutSection?->happy_clients ?? 98;
    @endphp

    <!-- Scroll Down indicator -->
    <a href="#about" class="hero-scroll-down position-absolute start-50 translate-middle-x z-3 text-decoration-none d-flex flex-column align-items-center" 
       style="bottom: 95px; color: var(--white); cursor: pointer; transition: all 0.3s ease;">
      <span class="fs-4 bounce-animation" style="color: var(--gold); line-height: 1; margin-bottom: 2px;">↓</span>
      <span class="text-uppercase tracking-wider font-monospace" style="font-size: 0.62rem; opacity: 0.75; font-weight: 500;">Scroll Down</span>
    </a>

    <div class="hero-stats-bar position-absolute bottom-0 start-50 translate-middle-x z-3 w-100 pb-4">
      <div class="container d-flex justify-content-center px-3">
        <div class="stats-glass-container d-grid d-md-flex align-items-center justify-content-center gap-3 gap-md-4 py-3 px-4 px-md-5 rounded-pill shadow-lg"
             style="background: rgba(17, 17, 17, 0.45); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08) !important;">
          
          <!-- Projects -->
          <div class="stat-item text-center d-flex align-items-center justify-content-center">
            <span class="fw-bold text-uppercase counter" data-target="{{ $completedProjects }}" data-suffix="+" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-size: 1.5rem;">0+</span>
            <span class="text-uppercase text-muted-custom ms-2" style="font-size: 0.75rem; letter-spacing: 1.5px; color: rgba(255, 255, 255, 0.55); font-weight: 500;">Projects</span>
          </div>

          <div class="vr bg-secondary opacity-25 d-none d-md-block" style="height: 24px; width: 1px;"></div>

          <!-- Years -->
          <div class="stat-item text-center d-flex align-items-center justify-content-center">
            <span class="fw-bold text-uppercase counter" data-target="{{ $experienceYears }}" data-suffix="+" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-size: 1.5rem;">0+</span>
            <span class="text-uppercase text-muted-custom ms-2" style="font-size: 0.75rem; letter-spacing: 1.5px; color: rgba(255, 255, 255, 0.55); font-weight: 500;">Years</span>
          </div>

          <div class="vr bg-secondary opacity-25 d-none d-md-block" style="height: 24px; width: 1px;"></div>

          <!-- Team -->
          <div class="stat-item text-center d-flex align-items-center justify-content-center">
            <span class="fw-bold text-uppercase counter" data-target="{{ $teamMembers }}" data-suffix="+" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-size: 1.5rem;">0+</span>
            <span class="text-uppercase text-muted-custom ms-2" style="font-size: 0.75rem; letter-spacing: 1.5px; color: rgba(255, 255, 255, 0.55); font-weight: 500;">Team</span>
          </div>

          <div class="vr bg-secondary opacity-25 d-none d-md-block" style="height: 24px; width: 1px;"></div>

          <!-- Happy -->
          <div class="stat-item text-center d-flex align-items-center justify-content-center">
            <span class="fw-bold text-uppercase counter" data-target="{{ $happyClients }}" data-suffix="%" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-size: 1.5rem;">0%</span>
            <span class="text-uppercase text-muted-custom ms-2" style="font-size: 0.75rem; letter-spacing: 1.5px; color: rgba(255, 255, 255, 0.55); font-weight: 500;">Satisfaction</span>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
