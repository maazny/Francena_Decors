@php
  $slides = hero_slides();
  $firstSlide = $slides->first();
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

                    <h1 class="display-4 fw-bold">{{ $slide->title }}</h1>

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
  </section>
@endif
