@extends('layouts.app')

@section('title', 'Fancy Decorators | Luxury Construction Company')

@section('content')
  <main>
    <x-hero-slider />

    <x-about-section />

    <section id="services" class="py-5 text-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Our Services</span>
          <h2>Complete Luxury Construction Services</h2>
          <p>From custom residential builds to executive commercial fit-outs, our construction services are designed to support premium projects from concept through completion.</p>
        </div>
        <div class="search-box mb-4" role="search" aria-label="Search services">
          <input id="serviceSearch" type="text" class="form-control search-input" placeholder="Search services..." aria-label="Search services" />
        </div>
        <div class="row g-4">
          @forelse($services as $service)
            <div class="col-md-6 col-xl-4">
              <article class="service-card glass-card p-4 h-100" data-service="{{ strtolower($service->title) }} {{ strtolower($service->short_description) }}">
                <div class="service-icon">
                  @if($service->icon)
                    <i class="{{ $service->icon }}"></i>
                  @else
                    <i class="fa-solid fa-briefcase"></i>
                  @endif
                </div>
                <h4><a href="{{ route('services.show', $service->slug) }}" class="text-white text-decoration-none">{{ $service->title }}</a></h4>
                <p>{{ $service->short_description }}</p>
              </article>
            </div>
          @empty
            <div class="col-12 text-center py-4">
              <p class="text-muted">No services available at the moment.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>


    <section id="projects" class="py-5 text-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Featured Projects</span>
          <h2>Exceptional Projects for Distinctive Spaces</h2>
          <p>A curated gallery of premium residential, commercial, interior and exterior work.</p>
        </div>
        <div class="text-center mb-4">
          <div class="btn-group" role="group" aria-label="Project filter">
            <button type="button" class="btn btn-outline-light active filter-btn" aria-pressed="true" data-filter="all">All</button>
            @foreach(\App\Models\ProjectCategory::active()->ordered()->get() as $cat)
              <button type="button" class="btn btn-outline-light filter-btn" aria-pressed="false" data-filter="{{ $cat->slug }}">{{ $cat->name }}</button>
            @endforeach
          </div>
        </div>
        <div class="row g-4 project-grid">
          @forelse($featuredProjects as $project)
            <div class="col-sm-6 col-lg-4 project-item {{ $project->category?->slug }}">
              <a href="{{ route('projects.show', $project->slug) }}" class="text-decoration-none">
                <article class="project-card">
                  @if($project->coverImage)
                    <img src="{{ image_url($project->coverImage) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" />
                  @else
                    <div class="bg-dark p-5 text-center text-muted" style="min-height: 250px;">
                      <i class="fa-solid fa-image fa-3x"></i>
                    </div>
                  @endif
                  <div class="project-info">
                    <h5>{{ $project->title }}</h5>
                    <p>{{ $project->category?->name }}</p>
                  </div>
                </article>
              </a>
            </div>
          @empty
            <div class="col-12 text-center py-4">
              <p class="text-muted">No projects available at the moment.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>

    @include('partials.homepage-client-brands')

    @if($testimonials->isNotEmpty())
      <section id="testimonials" class="py-5 section-bg">
        <div class="container">
          <div class="section-header text-center mb-5">
            <span class="section-label">Testimonials</span>
            <h2>What Our Clients Say</h2>
            <p>Trusted by clients who appreciate luxury, transparency, and exceptional delivery.</p>
          </div>
          <div class="testimonial-slider position-relative">
            @foreach($testimonials as $index => $testimonial)
              <div class="testimonial-item {{ $index === 0 ? 'active' : '' }}">
                <p>"{{ $testimonial->testimonial }}"</p>
                <h5>{{ $testimonial->client_name }}</h5>
                <span>{{ $testimonial->client_designation }} {{ $testimonial->client_company ? '@ ' . $testimonial->client_company : '' }}</span>
              </div>
            @endforeach
            @if($testimonials->count() > 1)
              <div class="testimonial-controls d-flex justify-content-center gap-3 mt-4" aria-label="Testimonial navigation">
                <button id="testPrev" type="button" class="btn btn-gold btn-sm prev-slide" aria-label="Previous testimonial"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                <button id="testNext" type="button" class="btn btn-outline-light btn-sm next-slide" aria-label="Next testimonial"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
              </div>
            @endif
          </div>
        </div>
      </section>
    @endif
    <section id="careers" class="py-5 section-bg text-white">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-6" data-aos="fade-right">
            <span class="section-label">Join Our Team</span>
            <h2>Build Your Legacy With Us</h2>
            <p class="opacity-75 my-4">At Fancy Decorators, we craft sophistication and luxury. We are constantly searching for talented designers, architects, and project managers to join our team.</p>
            <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-circle-check text-primary" style="color: var(--button-background, #d4af5f) !important;"></i>
                  <span>Elite Projects</span>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-circle-check text-primary" style="color: var(--button-background, #d4af5f) !important;"></i>
                  <span>Competitive Salaries</span>
                </div>
              </div>
            </div>
            <a href="{{ route('careers.index') }}" class="btn btn-gold btn-lg rounded-pill px-4" style="background-color: var(--button-background, #d4af5f) !important; border-color: var(--button-background, #d4af5f) !important; color: #fff !important;">Careers Portal</a>
          </div>
          <div class="col-lg-6" data-aos="fade-left">
            <div class="card border-0 glass-card p-4">
              <h4 class="fw-bold mb-4 font-serif text-white">Featured Openings</h4>
              <div class="d-flex flex-column gap-3">
                @forelse($homepageJobs as $job)
                  <div class="p-3 bg-dark rounded d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="fw-bold mb-1 text-white">{{ $job->title }}</h6>
                      <span class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i> {{ $job->location?->city }} • {{ $job->employment_type }}</span>
                    </div>
                    <a href="{{ route('careers.show', $job->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill">Apply</a>
                  </div>
                @empty
                  <p class="text-muted mb-0">No active positions available right now.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="blog" class="py-5 text-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Our Journal</span>
          <h2>Latest Insights & Elegance</h2>
          <p>Read our latest articles on luxury architectural design, home renovation tips, and corporate fit-outs.</p>
        </div>
        <div class="row g-4">
          @forelse($latestPosts as $post)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
              <x-blog-card :post="$post" />
            </div>
          @empty
            <div class="col-12 text-center py-4">
              <div class="glass-card p-5">
                <i class="fa-solid fa-newspaper fa-3x mb-3 text-muted"></i>
                <h5>No articles published yet</h5>
                <p class="text-muted mb-0">Check back soon for insights from our luxury designers.</p>
              </div>
            </div>
          @endforelse
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
          <a href="{{ route('blog.index') }}" class="btn btn-outline-light btn-lg text-uppercase px-4 py-3 fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">View Entire Journal</a>
        </div>
      </div>
    </section>

    <section id="process" class="py-5 text-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Our Process</span>
          <h2>From Vision to Premium Delivery</h2>
          <p>A refined five-step process built for clarity and luxury execution.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-6 col-lg-4 col-xl-2">
            <div class="process-card p-4 h-100">
              <div class="process-step">1</div>
              <h5>Consultation</h5>
              <p>Understand your vision with expert guidance.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-2">
            <div class="process-card p-4 h-100">
              <div class="process-step">2</div>
              <h5>Planning</h5>
              <p>Build a clear roadmap with premium specifications.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-2">
            <div class="process-card p-4 h-100">
              <div class="process-step">3</div>
              <h5>Design</h5>
              <p>Create stunning interiors and architectural details.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-2">
            <div class="process-card p-4 h-100">
              <div class="process-step">4</div>
              <h5>Construction</h5>
              <p>Execute with precision, safety, and premium care.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-2">
            <div class="process-card p-4 h-100">
              <div class="process-step">5</div>
              <h5>Handover</h5>
              <p>Deliver a complete space ready to enjoy.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="faq" class="py-5 section-bg">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">FAQ</span>
          <h2>Frequently Asked Questions</h2>
          <p>Answers to common questions about our luxury construction process.</p>
        </div>
        <div class="accordion" id="faqAccordion">
          @forelse($serviceFaqs as $index => $faq)
            <div class="accordion-item glass-card">
              <h2 class="accordion-header" id="faqHeading{{ $index }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">{{ $faq->question }}</button>
              </h2>
              <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">{{ $faq->answer }}</div>
              </div>
            </div>
          @empty
            <div class="alert alert-light">No FAQs are available at the moment.</div>
          @endforelse
        </div>
      </div>
    </section>

    <section id="contact" class="py-5 text-white">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Contact</span>
          <h2>Start Your Luxury Project</h2>
          <p>Contact Fancy Decorators today to schedule a consultation for sophisticated home builds, commercial construction, or high-end renovation services.</p>
        </div>
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
              <h4>Send a Request</h4>
              <form id="contactForm" class="contact-form" novalidate>
                <div class="mb-3">
                  <label class="visually-hidden" for="contactName">Name</label>
                  <input id="contactName" name="name" type="text" class="form-control" placeholder="Name" autocomplete="name" required />
                </div>
                <div class="mb-3">
                  <label class="visually-hidden" for="contactPhone">Phone</label>
                  <input id="contactPhone" name="phone" type="tel" class="form-control" placeholder="Phone" autocomplete="tel" required />
                </div>
                <div class="mb-3">
                  <label class="visually-hidden" for="contactEmail">Email</label>
                  <input id="contactEmail" name="email" type="email" class="form-control" placeholder="Email" autocomplete="email" required />
                </div>
                <div class="mb-3">
                  <label class="visually-hidden" for="contactService">Service</label>
                  <input id="contactService" name="service" type="text" class="form-control" placeholder="Service" autocomplete="off" required />
                </div>
                <div class="mb-3">
                  <label class="visually-hidden" for="contactMessage">Message</label>
                  <textarea id="contactMessage" name="message" class="form-control" rows="5" placeholder="Message" required></textarea>
                </div>
                <button class="btn btn-gold btn-lg w-100" type="submit">Send Message</button>
                <div id="formMessage" class="form-feedback" role="status" aria-live="polite"></div>
              </form>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="map-card glass-card p-0 overflow-hidden">
              <div class="map-placeholder d-flex align-items-center justify-content-center">
                <div class="map-text text-center">
                  <i class="fa-solid fa-map-location-dot fa-2xl mb-3"></i>
                  <h5>Google Map Placeholder</h5>
                  <p>Imagine your project location here with a premium branded map view.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="quick-actions" aria-label="Quick contact actions">
    <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer" class="action-btn whatsapp" aria-label="Chat on WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
    <a href="tel:+1234567890" class="action-btn call" aria-label="Call us"><i class="fa-solid fa-phone" aria-hidden="true"></i></a>
    <a href="mailto:hello@fancydecorators.com" class="action-btn email" aria-label="Email us"><i class="fa-solid fa-envelope" aria-hidden="true"></i></a>
  </div>

  <div id="galleryModal" class="gallery-modal" role="dialog" aria-modal="true" aria-labelledby="galleryCaption" aria-hidden="true">
    <button type="button" class="close-modal" aria-label="Close gallery">&times;</button>
    <img class="modal-img" src="" alt="Project view" />
    <p id="galleryCaption" class="modal-caption"></p>
  </div>

  <button id="backToTop" class="back-to-top" type="button" aria-label="Scroll back to top"><i class="fa-solid fa-chevron-up" aria-hidden="true"></i></button>
  <div id="scrollProgress" class="scroll-progress" aria-hidden="true"></div>
@endsection
