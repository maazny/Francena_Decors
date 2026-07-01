@if ($testimonials->count())
    <section class="testimonials-slider py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8">
                    <h2 class="mb-1">What Our Clients Say</h2>
                    <p class="text-muted">Real testimonials from our satisfied customers</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('testimonials.index') }}" class="btn btn-outline-primary">
                        View All Testimonials <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div id="testimonialSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($testimonials as $index => $testimonial)
                        <div class="carousel-item @if ($index === 0) active @endif">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="card border-0 bg-white">
                                        <div class="card-body p-4">
                                            <div class="text-warning mb-3">
                                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @for ($i = $testimonial->rating; $i < 5; $i++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            </div>

                                            @if ($testimonial->title)
                                                <h4 class="mb-3">{{ $testimonial->title }}</h4>
                                            @endif

                                            <p class="mb-4 lead">{{ $testimonial->testimonial }}</p>

                                            <div class="d-flex align-items-center">
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
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    @if ($testimonial->clientLogo)
                                        <img src="{{ $testimonial->clientLogo->url }}"
                                            alt="{{ $testimonial->client_company }}"
                                            style="max-height: 150px; object-fit: contain;">
                                    @elseif($testimonial->clientPhoto)
                                        <img src="{{ $testimonial->clientPhoto->url }}"
                                            alt="{{ $testimonial->client_name }}" class="rounded"
                                            style="max-height: 200px; max-width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded p-5 text-muted">
                                            <i class="fas fa-image fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($testimonials->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlider"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialSlider"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @endif
            </div>
        </div>
    </section>
@endif
