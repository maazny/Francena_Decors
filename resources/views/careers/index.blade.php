@extends('layouts.app')

@section('title', 'Careers at Francena Decors | Join Our Team')
@section('meta_description', 'Build a premium career with Francena Decors. Discover job openings, team benefits, and see how we construct luxury residential and commercial masterpieces.')
@section('meta_keywords', 'careers, job openings, constructor job, luxury builder hiring, interior designer hiring, engineering jobs')

@section('content')
<main style="overflow-x: hidden;">
  
  <!-- Hero Section -->
  <section class="position-relative py-5 d-flex align-items-center text-center text-white" style="background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 70vh;">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <span class="badge bg-gold px-3 py-2 text-uppercase mb-3 tracking-wider text-white fw-bold" style="letter-spacing: 0.1em;">We Are Hiring</span>
          <h1 class="display-3 fw-bold mb-4 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Build Your Legacy With Us</h1>
          <p class="lead mb-5 text-light opacity-90">Join a team of elite architects, master builders, and premium designers crafting award-winning luxury spaces.</p>
          <a href="{{ route('careers.jobs') }}" class="btn btn-gold btn-lg px-4 py-3 rounded-pill shadow-lg hover-scale">
            <i class="fa-solid fa-search me-2"></i> Explore Open Positions
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Career Statistics -->
  <section class="py-5" style="background-color: rgba(255, 255, 255, 0.02); border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
    <div class="container">
      <div class="row g-4 text-center">
        <div class="col-md-4">
          <div class="p-4">
            <div class="display-4 fw-bold mb-2" style="color: var(--gold) !important;">{{ $stats['total_openings'] }}</div>
            <div class="text-uppercase tracking-wide small opacity-75">Active Openings</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4">
            <div class="display-4 fw-bold mb-2" style="color: var(--gold) !important;">{{ $stats['departments_count'] }}</div>
            <div class="text-uppercase tracking-wide small opacity-75">Departments</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4">
            <div class="display-4 fw-bold mb-2" style="color: var(--gold) !important;">{{ $stats['locations_count'] }}</div>
            <div class="text-uppercase tracking-wide small opacity-75">Global Offices</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Company Culture & Why Join Us -->
  <section class="py-5">
    <div class="container py-4">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <span class="text-uppercase small tracking-wider" style="color: var(--gold);">Our Identity</span>
          <h2 class="h1 fw-bold mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Company Culture & Values</h2>
          <p class="lead opacity-80 mb-4">At Francena Decors, we believe that premium execution requires a creative environment built on trust, design excellence, and continuous development.</p>
          <div class="d-flex align-items-start mb-4">
            <div class="p-3 bg-dark rounded-circle me-3" style="color: var(--gold) !important; background: rgba(206, 154, 95, 0.18) !important;">
              <i class="fa-solid fa-gem fa-xl"></i>
            </div>
            <div>
              <h5 class="fw-semibold">Uncompromised Quality</h5>
              <p class="opacity-75 mb-0">Every detail we craft is intended to stand the test of time, setting a new benchmark for luxury.</p>
            </div>
          </div>
          <div class="d-flex align-items-start mb-4">
            <div class="p-3 bg-dark rounded-circle me-3" style="color: var(--gold) !important; background: rgba(206, 154, 95, 0.18) !important;">
              <i class="fa-solid fa-lightbulb fa-xl"></i>
            </div>
            <div>
              <h5 class="fw-semibold">Creative Freedom</h5>
              <p class="opacity-75 mb-0">We encourage innovative approaches and provide our design teams with tools to test the limits of custom builds.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-6 mt-5">
              <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=400&q=80" alt="Luxury Build Design" class="img-fluid rounded shadow-lg mb-3">
              <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=400&q=80" alt="Collaboration" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-6">
              <img src="https://images.unsplash.com/photo-1600573472591-ee6b68d14c68?auto=format&fit=crop&w=400&q=80" alt="Office Studio" class="img-fluid rounded shadow-lg mb-3">
              <img src="https://images.unsplash.com/photo-1581094288338-2314dddb7ecc?auto=format&fit=crop&w=400&q=80" alt="Engineering" class="img-fluid rounded shadow-lg">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits & Perks -->
  <section class="py-5 bg-dark">
    <div class="container py-4">
      <div class="text-center mb-5">
        <span class="text-uppercase small tracking-wider" style="color: var(--gold);">Premium Rewards</span>
        <h2 class="h1 fw-bold font-serif" style="font-family: 'Playfair Display', serif;">Benefits & Perks</h2>
        <p class="opacity-75 max-w-lg mx-auto">We look after our team with premium perks designed to help you work, grow, and thrive.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 p-4 border-0 glass-card">
            <i class="fa-solid fa-hand-holding-dollar fa-2x mb-3" style="color: var(--gold);"></i>
            <h4 class="h5 fw-semibold mb-2">Competitive Compensation</h4>
            <p class="opacity-75 mb-0">Top-tier industry salaries with performance-based bonuses, commissions, and project incentives.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 p-4 border-0 glass-card">
            <i class="fa-solid fa-heart-pulse fa-2x mb-3" style="color: var(--gold);"></i>
            <h4 class="h5 fw-semibold mb-2">Comprehensive Health Care</h4>
            <p class="opacity-75 mb-0">Full health, vision, and dental covers for you and your dependents with wellness programs.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 p-4 border-0 glass-card">
            <i class="fa-solid fa-graduation-cap fa-2x mb-3" style="color: var(--gold);"></i>
            <h4 class="h5 fw-semibold mb-2">Learning & Growth Stacks</h4>
            <p class="opacity-75 mb-0">Company-sponsored certifications, architectural training courses, and custom builder programs.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured / Latest Openings -->
  <section class="py-5">
    <div class="container py-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
        <div>
          <span class="text-uppercase small tracking-wider" style="color: var(--gold);">Vacancies</span>
          <h2 class="h1 fw-bold font-serif mb-0" style="font-family: 'Playfair Display', serif;">Latest Job Openings</h2>
        </div>
        <a href="{{ route('careers.jobs') }}" class="btn btn-outline-light rounded-pill px-4 mt-3 mt-md-0">
          View All Positions <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
      </div>

      @if($featuredJobs->count() > 0)
        <div class="mb-4"><h3 class="h5 text-uppercase tracking-wider opacity-75 mb-3">Featured Roles</h3></div>
        <div class="row g-4 mb-5">
          @foreach($featuredJobs as $job)
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 border-0 glass-card p-4 position-relative">
                <span class="position-absolute top-0 end-0 bg-gold px-3 py-1 rounded-bottom text-uppercase text-white small fw-bold">Featured</span>
                <span class="small opacity-75 text-uppercase">{{ $job->department?->name }}</span>
                <h4 class="h5 fw-bold my-2"><a href="{{ route('careers.show', $job->slug) }}" class="text-white text-decoration-none hover-link">{{ $job->title }}</a></h4>
                <div class="d-flex align-items-center gap-2 mb-3 text-muted small">
                  <span><i class="fa-solid fa-location-dot"></i> {{ $job->location?->city }}</span>
                  <span>•</span>
                  <span><i class="fa-solid fa-clock"></i> {{ $job->employment_type }}</span>
                </div>
                <p class="opacity-75 small line-clamp-3 mb-4">{{ $job->short_description ?: Str::limit(strip_tags($job->description), 100) }}</p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  @if($job->salary_to)
                    <span class="fw-bold" style="color: var(--gold);">{{ $job->salary_from ? '$'.number_format($job->salary_from) . ' - ' : '' }}${{ number_format($job->salary_to) }}</span>
                  @else
                    <span class="text-muted small">Competitive</span>
                  @endif
                  <a href="{{ route('careers.show', $job->slug) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">Apply Now</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif

      @if($latestJobs->count() > 0)
        <div class="mb-4"><h3 class="h5 text-uppercase tracking-wider opacity-75 mb-3">New Openings</h3></div>
        <div class="row g-4">
          @foreach($latestJobs as $job)
            @if(!$featuredJobs->contains('id', $job->id))
              <div class="col-md-6">
                <div class="card border-0 glass-card p-4 h-100 d-flex flex-column">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <span class="small opacity-75 text-uppercase">{{ $job->department?->name }}</span>
                      <h4 class="h5 fw-bold my-1"><a href="{{ route('careers.show', $job->slug) }}" class="text-white text-decoration-none hover-link">{{ $job->title }}</a></h4>
                    </div>
                    <span class="badge bg-dark border border-secondary text-uppercase text-warning px-2.5 py-1" style="font-size: 0.65rem;">{{ $job->employment_type }}</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 mb-3 text-muted small">
                    <span><i class="fa-solid fa-location-dot"></i> {{ $job->location?->city }}, {{ $job->location?->state }}</span>
                  </div>
                  <p class="opacity-75 small mb-4">{{ $job->short_description ?: Str::limit(strip_tags($job->description), 100) }}</p>
                  <div class="mt-auto d-flex justify-content-between align-items-center">
                    @if($job->salary_to)
                      <span class="fw-bold" style="color: var(--gold);">{{ $job->salary_from ? '$'.number_format($job->salary_from) . ' - ' : '' }}${{ number_format($job->salary_to) }}</span>
                    @else
                      <span class="text-muted small">Competitive</span>
                    @endif
                    <a href="{{ route('careers.show', $job->slug) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">View Details</a>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      @else
        <div class="alert alert-info border-0 text-white rounded p-4" style="background-color: rgba(255, 255, 255, 0.05);">
          No active positions available right now. Feel free to contact us for speculative inquiries.
        </div>
      @endif
    </div>
  </section>

  <!-- Application Process Timeline -->
  <section class="py-5 bg-dark">
    <div class="container py-4">
      <div class="text-center mb-5">
        <span class="text-uppercase small tracking-wider" style="color: var(--gold);">Steps</span>
        <h2 class="h1 fw-bold font-serif" style="font-family: 'Playfair Display', serif;">Application Process</h2>
        <p class="opacity-75">Learn how we review and hire new professionals into our crew.</p>
      </div>
      <div class="row g-4 justify-content-center">
        <div class="col-md-3 text-center">
          <div class="p-4 glass-card h-100">
            <div class="h3 fw-bold mb-3" style="color: var(--gold);">01</div>
            <h5 class="fw-semibold mb-2">Apply Online</h5>
            <p class="opacity-75 small">Fill out details and attach your resume securely.</p>
          </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="p-4 glass-card h-100">
            <div class="h3 fw-bold mb-3" style="color: var(--gold);">02</div>
            <h5 class="fw-semibold mb-2">Portfolio Review</h5>
            <p class="opacity-75 small">Our lead engineers and architects analyze your past builds.</p>
          </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="p-4 glass-card h-100">
            <div class="h3 fw-bold mb-3" style="color: var(--gold);">03</div>
            <h5 class="fw-semibold mb-2">Interviews</h5>
            <p class="opacity-75 small">Discuss technical expectations and experience alignment.</p>
          </div>
        </div>
        <div class="col-md-3 text-center">
          <div class="p-4 glass-card h-100">
            <div class="h3 fw-bold mb-3" style="color: var(--gold);">04</div>
            <h5 class="fw-semibold mb-2">Offer & Onboard</h5>
            <p class="opacity-75 small">Welcome aboard and begin building premium landmarks.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 text-center text-white position-relative" style="background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover;">
    <div class="container py-5">
      <h2 class="display-5 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Ready to Advance Your Career?</h2>
      <p class="lead mb-4 opacity-90 max-w-lg mx-auto">Explore all open vacancies and submit your application online to connect with our HR team today.</p>
      <a href="{{ route('careers.jobs') }}" class="btn btn-gold btn-lg rounded-pill px-5 py-3 shadow hover-scale">
        Find Your Position
      </a>
    </div>
  </section>

</main>
@endsection

@push('scripts')
<style>
  .hover-scale {
    transition: transform 0.2s ease;
  }
  .hover-scale:hover {
    transform: scale(1.05);
  }
  .hover-link {
    transition: color 0.2s ease;
  }
  .hover-link:hover {
    color: var(--gold) !important;
  }
  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
@endpush
