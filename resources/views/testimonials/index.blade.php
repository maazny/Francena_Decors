@extends('layouts.app')

@section('title', 'Client Testimonials | Francena Decors')

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.6), rgba(5, 4, 7, 0.95)), url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover; min-height: 35vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">Client Voices</span>
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">Testimonials</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">Testimonials</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5" style="min-height: 60vh;">
  <div class="container">
    
    <!-- Filter Search Form -->
    <div class="card border-0 glass-card p-4 mb-5 shadow-lg">
      <form method="GET" class="row g-3">
        <div class="col-md-3">
          <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Search testimonials..." value="{{ $search }}">
        </div>
        <div class="col-md-3">
          <select name="category" class="form-select bg-dark text-white border-secondary">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <select name="rating" class="form-select bg-dark text-white border-secondary">
            <option value="">All Ratings</option>
            <option value="5" @selected($selectedRating === '5')>5 Stars</option>
            <option value="4" @selected($selectedRating === '4')>4 Stars</option>
            <option value="3" @selected($selectedRating === '3')>3 Stars</option>
            <option value="2" @selected($selectedRating === '2')>2 Stars</option>
            <option value="1" @selected($selectedRating === '1')>1 Star</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="text" name="location" class="form-control bg-dark text-white border-secondary" placeholder="Location..." value="{{ $selectedLocation }}">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-gold w-100 py-2">
            <i class="fas fa-search me-2"></i>Filter
          </button>
        </div>
      </form>
    </div>

    <!-- Testimonials Grid -->
    @if ($testimonials->count())
      <div class="row g-4">
        @foreach ($testimonials as $testimonial)
          <div class="col-md-6 col-lg-4">
            <div class="card h-100 glass-card border-0 p-4 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center mb-4">
                  <div class="client-avatar-wrapper rounded-circle overflow-hidden shadow-lg me-3" style="width: 60px; height: 60px; border: 2px solid var(--gold);">
                    @if ($testimonial->clientPhoto)
                      <img src="{{ image_url($testimonial->clientPhoto) }}" alt="{{ $testimonial->client_name }}" class="w-100 h-100 object-fit-cover">
                    @else
                      <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                        <i class="fa-solid fa-user fa-xl"></i>
                      </div>
                    @endif
                  </div>
                  <div>
                    <h6 class="mb-0 text-white fw-bold">{{ $testimonial->client_name }}</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">
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
                    </small>
                  </div>
                </div>

                <!-- Rating -->
                <div class="rating-stars mb-3" style="color: var(--gold); font-size: 0.85rem;">
                  @for ($i = 0; $i < ($testimonial->rating ?: 5); $i++)
                    <i class="fas fa-star"></i>
                  @endfor
                  @for ($i = ($testimonial->rating ?: 5); $i < 5; $i++)
                    <i class="far fa-star"></i>
                  @endfor
                </div>

                @if ($testimonial->title)
                  <h5 class="fw-bold text-white mb-2 font-serif" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">"{{ $testimonial->title }}"</h5>
                @endif

                <p class="text-muted small mb-4" style="line-height: 1.6; font-style: italic;">
                  "{{ Str::limit($testimonial->testimonial, 160) }}"
                </p>
              </div>

              <div class="d-flex flex-wrap gap-2 mb-3">
                @if ($testimonial->hasVideo())
                  <span class="badge bg-danger text-uppercase" style="font-size: 0.65rem;">
                    <i class="fas fa-video me-1"></i> Video
                  </span>
                @endif
                @if ($testimonial->category)
                  <span class="badge bg-gold text-uppercase text-white fw-bold" style="font-size: 0.65rem;">
                    {{ $testimonial->category->name }}
                  </span>
                @endif
                @if ($testimonial->location)
                  <span class="badge bg-dark border border-secondary text-uppercase text-muted" style="font-size: 0.65rem;">
                    <i class="fas fa-map-marker-alt me-1"></i> {{ $testimonial->location }}
                  </span>
                @endif
              </div>

              <div class="mt-auto pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                <a href="{{ route('testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                  Read More <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      @if($testimonials->hasPages())
        <div class="d-flex justify-content-center mt-5">
          {{ $testimonials->links('pagination::bootstrap-5') }}
        </div>
      @endif
    @else
      <div class="text-center py-5 glass-card p-5">
        <i class="fas fa-info-circle display-4 mb-3 text-muted"></i>
        <h5 class="text-white">No Testimonials Found</h5>
        <p class="text-muted mb-0">Try adjusting your search filters.</p>
      </div>
    @endif

  </div>
</section>
@endsection

