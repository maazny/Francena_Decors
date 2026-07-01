@extends('layouts.app')

@section('title', $testimonial->seo_title ?? $testimonial->client_name . ' Testimonial')

@section('content')
<div class="page-header py-5 bg-light">
    <div class="container">
        <h1 class="mb-2">{{ $testimonial->client_name }}'s Testimonial</h1>
        <p class="text-muted">{{ $testimonial->client_company ?? 'Client' }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        @if ($testimonial->clientPhoto)
                            <img src="{{ $testimonial->clientPhoto->url }}"
                                alt="{{ $testimonial->client_name }}" class="rounded-circle me-4"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary me-4"
                                style="width: 80px; height: 80px;"></div>
                        @endif
                        <div>
                            <h4 class="mb-0">{{ $testimonial->client_name }}</h4>
                            @if ($testimonial->client_designation)
                                <p class="mb-1 text-muted">{{ $testimonial->client_designation }}</p>
                            @endif
                            @if ($testimonial->client_company)
                                <p class="mb-2">
                                    @if ($testimonial->clientLogo)
                                        <img src="{{ $testimonial->clientLogo->thumbnail_url }}"
                                            alt="{{ $testimonial->client_company }}"
                                            style="height: 30px; object-fit: contain; margin-right: 5px;">
                                    @endif
                                    <strong>{{ $testimonial->client_company }}</strong>
                                </p>
                            @endif
                            @if ($testimonial->location)
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $testimonial->location }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="text-warning mb-3">
                            @for ($i = 0; $i < $testimonial->rating; $i++)
                                <i class="fas fa-star fa-lg"></i>
                            @endfor
                            @for ($i = $testimonial->rating; $i < 5; $i++)
                                <i class="far fa-star fa-lg"></i>
                            @endfor
                        </div>
                    </div>

                    @if ($testimonial->title)
                        <h3 class="mb-3">{{ $testimonial->title }}</h3>
                    @endif

                    <div class="testimonial-content mb-4">
                        <p class="lead">{{ $testimonial->testimonial }}</p>
                    </div>

                    @if ($testimonial->hasVideo())
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="mb-3">Video Testimonial</h5>
                            @if ($testimonial->youtube_url)
                                <div class="ratio ratio-16x9">
                                    @php
                                        $youtubeId = '';
                                        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $testimonial->youtube_url, $matches)) {
                                            $youtubeId = $matches[1];
                                        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $testimonial->youtube_url, $matches)) {
                                            $youtubeId = $matches[1];
                                        }
                                    @endphp
                                    @if ($youtubeId)
                                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                            title="Video Testimonial" allowfullscreen></iframe>
                                    @else
                                        <a href="{{ $testimonial->youtube_url }}" target="_blank"
                                            class="btn btn-lg btn-danger">
                                            <i class="fab fa-youtube"></i> Watch Video on YouTube
                                        </a>
                                    @endif
                                </div>
                            @elseif($testimonial->video_url)
                                <video width="100%" height="auto" controls class="rounded">
                                    <source src="{{ $testimonial->video_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @endif

                    @if ($testimonial->project)
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="mb-3">Related Project</h5>
                            <a href="{{ route('projects.show', $testimonial->project) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-folder"></i> {{ $testimonial->project->title }}
                            </a>
                        </div>
                    @endif

                    @if ($testimonial->category)
                        <div class="mb-4 pb-4 border-bottom">
                            <h5 class="mb-3">Category</h5>
                            <a href="{{ route('testimonials.category', $testimonial->category) }}"
                                class="badge bg-info" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                {{ $testimonial->category->name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @if ($relatedTestimonials->count())
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Related Testimonials</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($relatedTestimonials as $related)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex gap-2 mb-2">
                                    @if ($related->clientPhoto)
                                        <img src="{{ $related->clientPhoto->thumbnail_url }}"
                                            alt="{{ $related->client_name }}" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary"
                                            style="width: 40px; height: 40px;"></div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="{{ route('testimonials.show', $related) }}">
                                                {{ $related->client_name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">{{ $related->client_company ?? 'Client' }}</small>
                                    </div>
                                </div>
                                <div class="text-warning small mb-2">
                                    @for ($i = 0; $i < $related->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @for ($i = $related->rating; $i < 5; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                </div>
                                <p class="small mb-0 text-muted">{{ Str::limit($related->testimonial, 100) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
