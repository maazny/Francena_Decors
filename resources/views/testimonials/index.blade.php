@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="page-header py-5 bg-light">
    <div class="container">
        <h1 class="mb-2">Client Testimonials</h1>
        <p class="text-muted">Hear from our satisfied clients about their experiences</p>
    </div>
</div>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search testimonials..."
                        value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">-- All Categories --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-select">
                        <option value="">-- All Ratings --</option>
                        <option value="5" @selected($selectedRating === '5')>5 Stars</option>
                        <option value="4" @selected($selectedRating === '4')>4 Stars</option>
                        <option value="3" @selected($selectedRating === '3')>3 Stars</option>
                        <option value="2" @selected($selectedRating === '2')>2 Stars</option>
                        <option value="1" @selected($selectedRating === '1')>1 Star</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="location" class="form-control" placeholder="Location..."
                        value="{{ $selectedLocation }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($testimonials->count())
        <div class="row">
            @foreach ($testimonials as $testimonial)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if ($testimonial->clientPhoto)
                                    <img src="{{ $testimonial->clientPhoto->thumbnail_url }}"
                                        alt="{{ $testimonial->client_name }}" class="rounded-circle me-3"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary me-3"
                                        style="width: 50px; height: 50px;"></div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $testimonial->client_name }}</h6>
                                    <small class="text-muted">
                                        @if ($testimonial->client_designation)
                                            {{ $testimonial->client_designation }}
                                            @if ($testimonial->client_company)
                                                @ {{ $testimonial->client_company }}
                                            @endif
                                        @elseif($testimonial->client_company)
                                            {{ $testimonial->client_company }}
                                        @else
                                            Client
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="text-warning">
                                    @for ($i = 0; $i < $testimonial->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @for ($i = $testimonial->rating; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </span>
                            </div>

                            @if ($testimonial->title)
                                <h6 class="card-title">{{ $testimonial->title }}</h6>
                            @endif

                            <p class="card-text">
                                {{ Str::limit($testimonial->testimonial, 150) }}
                            </p>

                            <div class="d-flex gap-2 flex-wrap">
                                @if ($testimonial->hasVideo())
                                    <span class="badge bg-danger">
                                        <i class="fas fa-video"></i> Video
                                    </span>
                                @endif
                                @if ($testimonial->category)
                                    <span class="badge bg-info">{{ $testimonial->category->name }}</span>
                                @endif
                                @if ($testimonial->location)
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-map-marker-alt"></i> {{ $testimonial->location }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <a href="{{ route('testimonials.show', $testimonial) }}" class="btn btn-sm btn-outline-primary">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $testimonials->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle display-4 mb-3"></i>
            <h5>No testimonials found</h5>
            <p class="mb-0">Try adjusting your search filters</p>
        </div>
    @endif
</div>
@endsection
