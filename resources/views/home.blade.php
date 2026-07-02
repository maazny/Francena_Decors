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
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="residential construction home luxury">
              <div class="service-icon"><i class="fa-solid fa-house-chimney-user"></i></div>
              <h4>Residential Construction</h4>
              <p>Build elegant homes with premium finishes and lasting quality.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="commercial construction office premium">
              <div class="service-icon"><i class="fa-solid fa-building"></i></div>
              <h4>Commercial Construction</h4>
              <p>Deliver sophisticated commercial properties with strategic planning.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="interior design decor style">
              <div class="service-icon"><i class="fa-solid fa-pencil-ruler"></i></div>
              <h4>Interior Design</h4>
              <p>Create immersive luxury interiors with strong personality.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="exterior design landscape facade">
              <div class="service-icon"><i class="fa-solid fa-tree-city"></i></div>
              <h4>Exterior Design</h4>
              <p>Design elegant exterior landscapes and facades for grand appeal.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="painting finishes color textures">
              <div class="service-icon"><i class="fa-solid fa-palette"></i></div>
              <h4>Painting</h4>
              <p>Finish projects with flawless color applications and texture work.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="false ceiling ceiling design lighting ambiance">
              <div class="service-icon"><i class="fa-solid fa-border-style"></i></div>
              <h4>False Ceiling</h4>
              <p>Clever ceiling designs that enhance light and spatial harmony.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="modular kitchen kitchen design cabinetry appliances">
              <div class="service-icon"><i class="fa-solid fa-utensils"></i></div>
              <h4>Modular Kitchen</h4>
              <p>Custom kitchens built for luxury cooking and refined living.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="renovation remodel upgrade modernize">
              <div class="service-icon"><i class="fa-solid fa-sync-alt"></i></div>
              <h4>Renovation</h4>
              <p>Revitalize existing spaces with modern elegance and functionality.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="waterproofing protection damp proof moisture barrier">
              <div class="service-icon"><i class="fa-solid fa-droplet"></i></div>
              <h4>Waterproofing</h4>
              <p>Protect structures with reliable waterproof systems and detailing.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="electrical wiring lighting outlets safety">
              <div class="service-icon"><i class="fa-solid fa-bolt"></i></div>
              <h4>Electrical Work</h4>
              <p>Expert electrical installations with safety and premium finishes.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="plumbing pipes fixtures bathrooms kitchens">
              <div class="service-icon"><i class="fa-solid fa-faucet"></i></div>
              <h4>Plumbing</h4>
              <p>High-quality plumbing solutions for comfort and durability.</p>
            </article>
          </div>
          <div class="col-md-6 col-xl-4">
            <article class="service-card glass-card p-4 h-100" data-service="tiles flooring hardwood marble porcelain">
              <div class="service-icon"><i class="fa-solid fa-square-full"></i></div>
              <h4>Tiles & Flooring</h4>
              <p>Luxury flooring installations with elegant materials and finish.</p>
            </article>
          </div>
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
            <button type="button" class="btn btn-outline-light filter-btn" aria-pressed="false" data-filter="residential">Residential</button>
            <button type="button" class="btn btn-outline-light filter-btn" aria-pressed="false" data-filter="commercial">Commercial</button>
            <button type="button" class="btn btn-outline-light filter-btn" aria-pressed="false" data-filter="interior">Interior</button>
            <button type="button" class="btn btn-outline-light filter-btn" aria-pressed="false" data-filter="exterior">Exterior</button>
          </div>
        </div>
        <div class="row g-4 project-grid">
          <div class="col-sm-6 col-lg-4 project-item residential">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80" alt="Luxury residential construction project with premium finishes" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Luxury Villa</h5>
                <p>Residential</p>
              </div>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4 project-item commercial">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=900&q=80" alt="High-end commercial building construction project" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Office Complex</h5>
                <p>Commercial</p>
              </div>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4 project-item interior">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=900&q=80" alt="Premium interior design and construction project" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Modern Lounge</h5>
                <p>Interior</p>
              </div>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4 project-item exterior">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=900&q=80" alt="Exterior architecture and facade renovation project" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Facade Renewal</h5>
                <p>Exterior</p>
              </div>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4 project-item residential interior">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=900&q=80" alt="Elegant residential interior construction and renovation" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Elegant Residence</h5>
                <p>Residential / Interior</p>
              </div>
            </article>
          </div>
          <div class="col-sm-6 col-lg-4 project-item commercial exterior">
            <article class="project-card">
              <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80" alt="Luxury commercial exterior construction and landscaping" loading="lazy" decoding="async" />
              <div class="project-info">
                <h5>Signature Plaza</h5>
                <p>Commercial / Exterior</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    @include('partials.homepage-client-brands')

    <section id="testimonials" class="py-5 section-bg">
      <div class="container">
        <div class="section-header text-center mb-5">
          <span class="section-label">Testimonials</span>
          <h2>What Our Clients Say</h2>
          <p>Trusted by clients who appreciate luxury, transparency, and exceptional delivery.</p>
        </div>
        <div class="testimonial-slider position-relative">
          <div class="testimonial-item active">
            <p>"Fancy Decorators delivered our dream villa with spectacular detail, professionalism, and care."</p>
            <h5>Amelia Rodgers</h5>
            <span>Residential Client</span>
          </div>
          <div class="testimonial-item">
            <p>"The team turned our commercial space into a premium environment that impresses every visitor."</p>
            <h5>Marcus Lee</h5>
            <span>Business Owner</span>
          </div>
          <div class="testimonial-item">
            <p>"From planning to handover, every step felt refined and effortless. Highly recommended."</p>
            <h5>Sophia Turner</h5>
            <span>Design Partner</span>
          </div>
          <div class="testimonial-controls d-flex justify-content-center gap-3 mt-4" aria-label="Testimonial navigation">
            <button id="testPrev" type="button" class="btn btn-gold btn-sm prev-slide" aria-label="Previous testimonial"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
            <button id="testNext" type="button" class="btn btn-outline-light btn-sm next-slide" aria-label="Next testimonial"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
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
