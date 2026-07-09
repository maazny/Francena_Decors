@extends('layouts.app')

@section('title', 'Fancy Decorators | Luxury Construction Company')

@section('content')
  <main>
    <x-hero-slider />

    <x-about-section />

    <!-- Achievements Statistics Section -->
    <section class="py-5" style="background: #111111; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
      <div class="container">
        <div class="row g-4 text-center justify-content-center">
          
          <!-- Stat 1: 550+ Projects -->
          <div class="col-6 col-lg-3">
            <div class="stat-card p-4">
              <div class="display-4 fw-black m-0 mb-2 counter" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-weight: 900;" data-target="550" data-suffix="+">0</div>
              <p class="text-uppercase tracking-wider text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 1.5px;">Projects Delivered</p>
            </div>
          </div>
          
          <!-- Stat 2: 15+ Years -->
          <div class="col-6 col-lg-3">
            <div class="stat-card p-4">
              <div class="display-4 fw-black m-0 mb-2 counter" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-weight: 900;" data-target="15" data-suffix="+">0</div>
              <p class="text-uppercase tracking-wider text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 1.5px;">Years Experience</p>
            </div>
          </div>
          
          <!-- Stat 3: 80+ Experts -->
          <div class="col-6 col-lg-3">
            <div class="stat-card p-4">
              <div class="display-4 fw-black m-0 mb-2 counter" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-weight: 900;" data-target="80" data-suffix="+">0</div>
              <p class="text-uppercase tracking-wider text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 1.5px;">Expert Craftsmen</p>
            </div>
          </div>
          
          <!-- Stat 4: 100% Quality -->
          <div class="col-6 col-lg-3">
            <div class="stat-card p-4">
              <div class="display-4 fw-black m-0 mb-2 counter" style="color: var(--gold); font-family: 'Montserrat', sans-serif; font-weight: 900;" data-target="100" data-suffix="%">0</div>
              <p class="text-uppercase tracking-wider text-muted mb-0" style="font-size: 0.8rem; letter-spacing: 1.5px;">Quality Guarantee</p>
            </div>
          </div>
          
        </div>
      </div>
    </section>

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
            <div class="col-12 col-md-6 col-lg-4">
              <article class="service-card glass-card p-4 h-100 d-flex flex-column justify-content-between" data-service="{{ strtolower($service->title) }} {{ strtolower($service->short_description) }}">
                <div>
                  <div class="service-icon">
                    @if($service->icon)
                      <i class="{{ $service->icon }}"></i>
                    @else
                      <i class="fa-solid fa-briefcase"></i>
                    @endif
                  </div>
                  <h4 style="margin-bottom: 0.8rem;"><a href="{{ route('services.show', $service->slug) }}" class="text-decoration-none" style="color: #111111 !important;">{{ $service->title }}</a></h4>
                  <p class="mb-4" style="color: var(--white);">{{ $service->short_description }}</p>
                </div>
                <a href="{{ route('services.show', $service->slug) }}" class="service-action-link mt-auto d-inline-flex align-items-center gap-2 text-decoration-none fw-bold" style="color: var(--gold); font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">
                  Learn More <i class="fa-solid fa-arrow-right"></i>
                </a>
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
            <div class="col-12 col-md-6 col-lg-4 project-item {{ $project->category?->slug }}">
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
            <div class="col-12 text-center py-4">
              <p class="text-muted">No projects available at the moment.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>

    <!-- Before & After Interactive Showcase -->
    <section id="transformation" class="py-5 section-bg">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Transformations</span>
          <h2>Before & After Interactive Showcase</h2>
          <p>Drag the slider handle in the center left or right to witness our master structural renovations from skeleton to absolute luxury.</p>
        </div>
        
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="before-after-slider position-relative overflow-hidden rounded shadow-lg" style="height: 480px; user-select: none;">
              <!-- After Image (Base) -->
              <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=1200&q=80" alt="After Transformation" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="z-index: 1;" />
              
              <!-- Before Image (Overlayed & Clipped) -->
              <div class="before-image-container position-absolute top-0 start-0 h-100 overflow-hidden" style="width: 50%; z-index: 2;">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80" alt="Before Transformation" class="h-100 object-fit-cover position-absolute top-0 start-0" style="width: 100%; min-width: 900px;" />
                <div class="before-label position-absolute bg-dark text-white px-3 py-1 rounded small fw-bold" style="top: 20px; left: 20px; z-index: 3; letter-spacing: 1px;">BEFORE</div>
              </div>
              <div class="after-label position-absolute text-dark px-3 py-1 rounded small fw-bold" style="top: 20px; right: 20px; z-index: 3; letter-spacing: 1px; background: var(--gold);">AFTER</div>

              <!-- Drag Handle -->
              <div class="slider-handle position-absolute top-0 h-100 d-flex align-items-center justify-content-center" style="width: 4px; background: #FFFFFF; left: 50%; cursor: ew-resize; z-index: 4; box-shadow: 0 0 10px rgba(0,0,0,0.5);">
                <div class="handle-button rounded-circle bg-white d-flex align-items-center justify-content-center shadow" style="width: 42px; height: 42px; border: 2px solid var(--gold); color: #111111;">
                  <i class="fa-solid fa-arrows-left-right"></i>
                </div>
              </div>
            </div>
          </div>
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
                <div class="row align-items-center g-4 justify-content-center">
                  <!-- Left: Large Client Photo -->
                  <div class="col-12 col-md-4 text-center">
                    <div class="client-avatar-wrapper position-relative d-inline-block rounded-circle overflow-hidden shadow-lg" style="width: 140px; height: 140px; border: 3px solid var(--gold);">
                      @if($testimonial->clientPhoto)
                        <img src="{{ image_url($testimonial->clientPhoto) }}" alt="{{ $testimonial->client_name }}" class="w-100 h-100 object-fit-cover" loading="lazy" />
                      @else
                        <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                          <i class="fa-solid fa-user fa-3x"></i>
                        </div>
                      @endif
                    </div>
                  </div>
                  
                  <!-- Right: Testimonial details -->
                  <div class="col-12 col-md-8">
                    <div class="testimonial-card-content position-relative p-4 rounded" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255,255,255,0.06); min-height: 180px;">
                      <!-- Quote Icon -->
                      <div class="quote-icon-display position-absolute" style="top: -20px; left: 20px; font-size: 2.2rem; color: var(--gold); opacity: 0.25;">
                        <i class="fa-solid fa-quote-left"></i>
                      </div>
                      
                      <!-- Rating stars (★★★★★) -->
                      <div class="rating-stars mb-2" style="color: var(--gold); font-size: 0.95rem;">
                        @for($i = 0; $i < ($testimonial->rating ?: 5); $i++)
                          <i class="fa-solid fa-star"></i>
                        @endfor
                      </div>
                      
                      <p class="lead text-white mb-3" style="line-height: 1.7; font-size: 1.15rem; font-style: italic;">
                        "{{ $testimonial->testimonial }}"
                      </p>
                      
                      <h5 class="fw-bold m-0" style="color: var(--gold); font-size: 1.1rem; font-family: 'Montserrat', sans-serif;">{{ $testimonial->client_name }}</h5>
                      <span class="text-muted small text-uppercase tracking-wider" style="font-size: 0.75rem; letter-spacing: 1px;">
                        {{ $testimonial->client_designation }} {{ $testimonial->client_company ? '• ' . $testimonial->client_company : '' }}
                      </span>
                    </div>
                  </div>
                </div>
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
            <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
              <div>
                <h4 class="mb-4 text-gold" style="font-family: 'Montserrat', sans-serif;">Office Info</h4>
                <ul class="list-unstyled text-white-50 mb-4" style="line-height: 1.8;">
                  <li class="mb-3 d-flex align-items-start gap-3">
                    <i class="fa-solid fa-location-dot mt-1 text-gold fs-5"></i>
                    <div>
                      <strong>Main Headquarters:</strong><br>
                      {{ $siteSetting->address ?: '25 Royal Avenue, Downtown City, State 12345' }}
                    </div>
                  </li>
                  
                  <li class="mb-3 d-flex align-items-center gap-3">
                    <i class="fa-brands fa-whatsapp text-gold fs-5"></i>
                    <div>
                      <strong>WhatsApp:</strong><br>
                      <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-none hover-gold">+1 (234) 567-890</a>
                    </div>
                  </li>
                  
                  <li class="mb-3 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-envelope text-gold fs-5"></i>
                    <div>
                      <strong>Email:</strong><br>
                      <a href="mailto:{{ $siteSetting->company_email ?: 'hello@fancydecorators.com' }}" class="text-white text-decoration-none hover-gold">{{ $siteSetting->company_email ?: 'hello@fancydecorators.com' }}</a>
                    </div>
                  </li>
                  
                  <li class="mb-3 d-flex align-items-start gap-3">
                    <i class="fa-solid fa-clock mt-1 text-gold fs-5"></i>
                    <div>
                      <strong>Working Hours:</strong><br>
                      {{ $siteSetting->office_hours ?: 'Mon - Fri: 9:00 AM - 6:00 PM' }}
                    </div>
                  </li>
                </ul>
              </div>

              <!-- Google Map Embed -->
              <div class="rounded overflow-hidden shadow-lg border border-secondary" style="height: 240px;">
                @if($siteSetting->google_map)
                  {!! $siteSetting->google_map !!}
                @else
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2412634991585!2d-73.98656468459375!3d40.758001379327575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c258a272334701%3A0xcf8b80145241e17d!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1652136932454!5m2!1sen!2sus" class="w-100 h-100 border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                @endif
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
