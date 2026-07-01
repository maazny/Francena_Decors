@if ($testimonials->count())
    <section class="featured-testimonials py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h2 class="mb-1">Featured Testimonials</h2>
                    <p class="text-muted">Highlighted client testimonials</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('testimonials.index') }}" class="btn btn-outline-primary">
                        All Testimonials <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="row">
                @foreach ($testimonials as $testimonial)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
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
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $testimonial->client_name }}</h6>
                                        <small class="text-muted d-block">
                                            @if ($testimonial->client_designation)
                                                {{ $testimonial->client_designation }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="text-warning mb-3">
                                    @for ($i = 0; $i < $testimonial->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @for ($i = $testimonial->rating; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </div>

                                @if ($testimonial->title)
                                    <h5 class="card-title">{{ $testimonial->title }}</h5>
                                @endif

                                <p class="card-text mb-0">
                                    {{ Str::limit($testimonial->testimonial, 120) }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <a href="{{ route('testimonials.show', $testimonial) }}"
                                    class="text-decoration-none small">
                                    Read Full Testimonial <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
