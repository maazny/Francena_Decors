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
        <span class="section-label text-uppercase" style="color: var(--gold) !important; letter-spacing: 2.5px; font-weight: 600;">WELCOME TO FANCY DECORATORS</span>
        @if($about->meta_title)
          <h2 class="display-5 fw-bold mb-3" style="max-width: 800px; margin: 0 auto; line-height: 1.25; font-family: 'Montserrat', sans-serif;">{{ $about->meta_title }}</h2>
        @endif
        @if($about->meta_description)
          <p class="lead text-muted mx-auto" style="max-width: 750px; font-size: 1.1rem; line-height: 1.7;">{{ $about->meta_description }}</p>
        @endif
      </div>

      <div class="row gy-4 align-items-stretch">
        <!-- Left Column: Large Image & Floating Experience Badge -->
        <div class="col-lg-5">
          <div class="position-relative h-100 rounded overflow-hidden shadow-lg" style="min-height: 480px;">
            @if($about->chairmanImage)
              <img src="{{ image_url($about->chairmanImage) }}" alt="{{ $about->chairman_name ?: 'Chairman' }}" class="w-100 h-100 object-fit-cover" style="object-position: center; min-height: 480px;" loading="lazy" decoding="async">
            @else
              <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-muted">No Image</div>
            @endif

            <!-- Floating Experience Card -->
            <div class="floating-experience-card position-absolute bg-gold text-dark p-4 rounded shadow-lg d-flex align-items-center gap-3" style="bottom: 30px; left: 30px; z-index: 10; background: var(--gold); border-left: 5px solid #111111;">
              <div class="display-4 fw-black m-0" style="font-family: 'Montserrat', sans-serif; font-weight: 900; line-height: 1;">15+</div>
              <div class="text-uppercase fw-bold m-0" style="font-size: 0.75rem; letter-spacing: 1px; line-height: 1.2;">
                Years<br>Experience
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Content, Achievements, Timeline, CEO Signature -->
        <div class="col-lg-7">
          <div class="glass-card p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
            <!-- Story Content -->
            <div class="story-content mb-4">
              <h3 class="mb-3" style="color: var(--gold); border-bottom: 2px solid var(--gold); padding-bottom: 10px; display: inline-block;">Our Story</h3>
              <p class="lead mb-4" style="line-height: 1.8; color: var(--white);">{{ $about->company_story }}</p>
              
              <div class="row g-3 mt-2">
                @if($about->mission)
                  <div class="col-md-6">
                    <h5 class="fw-bold mb-2" style="color: #111111 !important;"><i class="fa-solid fa-bullseye me-2" style="color: var(--gold);"></i>Mission</h5>
                    <p style="color: var(--white); font-size: 0.95rem;">{{ $about->mission }}</p>
                  </div>
                @endif
                @if($about->vision)
                  <div class="col-md-6">
                    <h5 class="fw-bold mb-2" style="color: #111111 !important;"><i class="fa-solid fa-eye me-2" style="color: var(--gold);"></i>Vision</h5>
                    <p style="color: var(--white); font-size: 0.95rem;">{{ $about->vision }}</p>
                  </div>
                @endif
              </div>
            </div>

            <!-- Achievements (Stats Counters) -->
            <div class="achievements-section border-top border-bottom py-4 my-4" style="border-color: var(--border) !important;">
              <div class="row g-3">
                <div class="col-4 text-center">
                  <div class="counter-card-simple">
                    <div class="h2 fw-bold text-primary m-0 counter" style="color: #111111 !important;" data-target="{{ $about->completed_projects }}" data-suffix="+">0</div>
                    <p class="text-muted text-uppercase mb-0 mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Projects Completed</p>
                  </div>
                </div>
                <div class="col-4 text-center border-start border-end" style="border-color: var(--border) !important;">
                  <div class="counter-card-simple">
                    <div class="h2 fw-bold text-primary m-0 counter" style="color: #111111 !important;" data-target="{{ $about->team_members }}" data-suffix="+">0</div>
                    <p class="text-muted text-uppercase mb-0 mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Team Members</p>
                  </div>
                </div>
                <div class="col-4 text-center">
                  <div class="counter-card-simple">
                    <div class="h2 fw-bold text-primary m-0 counter" style="color: #111111 !important;" data-target="{{ $about->happy_clients }}" data-suffix="%">0</div>
                    <p class="text-muted text-uppercase mb-0 mt-1" style="font-size: 0.7rem; letter-spacing: 1px;">Happy Clients</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Timeline Journey -->
            @if($timelines->isNotEmpty())
              <div class="timeline-section mb-4">
                <h5 class="fw-bold mb-3 text-uppercase tracking-wider" style="font-size: 0.8rem; color: var(--gold);">Our Journey Timeline</h5>
                <div class="about-timeline-compact">
                  @foreach($timelines as $timeline)
                    <div class="timeline-compact-item d-flex gap-3 mb-3">
                      <span class="badge text-dark py-1 px-2 fw-bold" style="background: var(--gold); font-size: 0.8rem; height: fit-content; border-radius: 4px;">{{ $timeline->year }}</span>
                      <div>
                        <h6 class="fw-bold m-0" style="color: #111111;">{{ $timeline->title }}</h6>
                        @if($timeline->description)<p class="text-muted mb-0 small" style="line-height: 1.4;">{{ $timeline->description }}</p>@endif
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- CEO/Chairman Message & Signature -->
            <div class="ceo-signature-block d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--border) !important;">
              <div>
                @if($about->chairman_message)
                  <p class="fst-italic text-muted mb-2" style="font-size: 0.95rem;">"{{ $about->chairman_message }}"</p>
                @endif
                @if($about->chairman_name)
                  <h5 class="fw-bold m-0" style="color: #111111; font-size: 1rem;">{{ $about->chairman_name }}</h5>
                @endif
                @if($about->chairman_designation)
                  <p class="text-muted m-0 small">{{ $about->chairman_designation }}</p>
                @endif
              </div>
              <div class="signature-display font-signature text-gold" style="font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1.8rem; letter-spacing: -1px; color: var(--gold);">
                @if($about->chairman_name)
                  {{ $about->chairman_name }}
                @else
                  Chairman
                @endif
              </div>
            </div>

          </div>
        </div>
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
