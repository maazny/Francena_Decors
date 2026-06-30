@php
  $aboutData = about_cms();
  $about = $aboutData['section'];
  $values = $aboutData['values'];
  $timelines = $aboutData['timelines'];
  $whyChooseUs = $aboutData['whyChooseUs'];
@endphp

@if($about->exists && $about->status)
  <section id="about" class="py-5 section-bg about-cms-section">
    <div class="container">
      <div class="section-header text-center mb-5">
        <span class="section-label">About Us</span>
        @if($about->meta_title)
          <h2>{{ $about->meta_title }}</h2>
        @endif
        @if($about->meta_description)
          <p>{{ $about->meta_description }}</p>
        @endif
      </div>

      <div class="row gy-4 align-items-stretch">
        <div class="col-lg-7">
          <div class="glass-card p-4 h-100">
            @if($about->company_story)
              <h3>Our Story</h3>
              <p>{{ $about->company_story }}</p>
            @endif

            <div class="row mt-4">
              @if($about->mission)
                <div class="col-md-6 mb-3">
                  <h5>Mission</h5>
                  <p>{{ $about->mission }}</p>
                </div>
              @endif
              @if($about->vision)
                <div class="col-md-6 mb-3">
                  <h5>Vision</h5>
                  <p>{{ $about->vision }}</p>
                </div>
              @endif
            </div>

            @if($about->companyVideo)
              <div class="ratio ratio-16x9 mt-4 rounded overflow-hidden">
                <video controls preload="metadata">
                  <source src="{{ media_url($about->companyVideo) }}" type="{{ $about->companyVideo->mime_type }}">
                </video>
              </div>
            @endif

            @if($about->brochureFile)
              <a href="{{ media_url($about->brochureFile) }}" class="btn btn-gold mt-4" target="_blank" rel="noopener">
                <i class="fa-solid fa-file-arrow-down me-2"></i>
                Download Brochure
              </a>
            @endif
          </div>
        </div>

        <div class="col-lg-5">
          <div class="glass-card p-4 h-100">
            @if($about->chairmanImage)
              <img src="{{ image_url($about->chairmanImage) }}" alt="{{ $about->chairman_name ?: 'Chairman' }}" class="about-chairman-image rounded mb-4" loading="lazy" decoding="async">
            @endif
            @if($about->chairman_message)
              <blockquote class="mb-4">{{ $about->chairman_message }}</blockquote>
            @endif
            @if($about->chairman_name)
              <h5 class="mb-1">{{ $about->chairman_name }}</h5>
            @endif
            @if($about->chairman_designation)
              <p class="text-muted mb-0">{{ $about->chairman_designation }}</p>
            @endif
          </div>
        </div>
      </div>

      <div class="row g-3 counters mt-4">
        @foreach([
          ['label' => 'Years Experience', 'value' => $about->experience_years],
          ['label' => 'Projects Completed', 'value' => $about->completed_projects],
          ['label' => 'Happy Clients', 'value' => $about->happy_clients],
          ['label' => 'Team Members', 'value' => $about->team_members],
        ] as $stat)
          <div class="col-6 col-lg-3">
            <div class="counter-card p-4 text-center">
              <span class="counter" data-target="{{ $stat['value'] }}">0</span>
              <p>{{ $stat['label'] }}</p>
            </div>
          </div>
        @endforeach
      </div>

      @if($values->isNotEmpty())
        <div class="section-header text-center my-5">
          <span class="section-label">Core Values</span>
          <h2>What Guides Our Work</h2>
        </div>
        <div class="row g-4">
          @foreach($values as $value)
            <div class="col-md-6 col-lg-4">
              <div class="feature-box p-4 h-100">
                @if($value->icon)<i class="{{ $value->icon }}"></i>@endif
                <h5>{{ $value->title }}</h5>
                @if($value->description)<p>{{ $value->description }}</p>@endif
              </div>
            </div>
          @endforeach
        </div>
      @endif

      @if($timelines->isNotEmpty())
        <div class="section-header text-center my-5">
          <span class="section-label">Timeline</span>
          <h2>Our Journey</h2>
        </div>
        <div class="about-timeline">
          @foreach($timelines as $timeline)
            <article class="about-timeline-item">
              @if($timeline->image)
                <img src="{{ image_url($timeline->image) }}" alt="{{ $timeline->title }}" loading="lazy" decoding="async">
              @endif
              <div>
                <span>{{ $timeline->year }}</span>
                <h5>{{ $timeline->title }}</h5>
                @if($timeline->description)<p>{{ $timeline->description }}</p>@endif
              </div>
            </article>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  @if($whyChooseUs->isNotEmpty())
    <section id="why" class="py-5 section-bg">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Why Choose Us</span>
          <h2>Trusted Premium Construction Partner</h2>
        </div>
        <div class="row g-4">
          @foreach($whyChooseUs as $item)
            <div class="col-md-6 col-lg-4">
              <div class="feature-box p-4 h-100">
                @if($item->icon)<i class="{{ $item->icon }}"></i>@endif
                <h5>{{ $item->title }}</h5>
                @if($item->description)<p>{{ $item->description }}</p>@endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  @endif
@endif
