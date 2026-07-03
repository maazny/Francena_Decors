@extends('layouts.app')

@section('title', $service->seo_title ?: $service->title)
@section('meta_description', $service->seo_description ?: $service->short_description)
@section('meta_keywords', $service->seo_keywords)
@section('og_title', $service->seo_title ?: $service->title)
@section('og_description', $service->seo_description ?: $service->short_description)
@section('og_type', 'website')
@section('og_url', route('services.show', $service->slug))
@section('og_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('twitter_title', $service->seo_title ?: $service->title)
@section('twitter_description', $service->seo_description ?: $service->short_description)
@section('twitter_image', $service->bannerImage ? image_url($service->bannerImage) : ($service->featuredImage ? image_url($service->featuredImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80'))
@section('canonical', route('services.show', $service->slug))

@section('content')
<div class="container py-5">
  <div class="row g-4 mb-5">
    <div class="col-lg-8">
      <img src="{{ $service->bannerImage?->url ?? $service->featuredImage?->url ?? asset('images/default-service.jpg') }}" alt="{{ $service->title }}" class="img-fluid rounded shadow-sm w-100" style="height: 420px; object-fit: cover;">
    </div>
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 p-4">
        <h1 class="h4">{{ $service->title }}</h1>
        <p class="text-muted">{{ $service->short_description }}</p>
        <ul class="list-unstyled mb-0">
          @if($service->category)
            <li><strong>Category:</strong> {{ $service->category->name }}</li>
          @endif
          @if($service->starting_price)
            <li><strong>Starting Price:</strong> {{ $service->price_label }}</li>
          @endif
          @if($service->duration)
            <li><strong>Duration:</strong> {{ $service->duration }}</li>
          @endif
          @if($service->location)
            <li><strong>Location:</strong> {{ $service->location }}</li>
          @endif
        </ul>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="mb-5">
        {!! nl2br(e($service->description)) !!}
      </div>

      @if($service->activeFeatures->isNotEmpty())
        <section class="mb-5">
          <h2 class="h4 mb-3">Key Features</h2>
          <div class="row g-3">
            @foreach($service->activeFeatures as $feature)
              <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3 h-100">
                  <h3 class="h6">{{ $feature->title }}</h3>
                  <p class="mb-0 text-muted">{{ $feature->description }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      @if($service->activeProcesses->isNotEmpty())
        <section class="mb-5">
          <h2 class="h4 mb-3">Our Process</h2>
          <div class="row g-3">
            @foreach($service->activeProcesses as $process)
              <div class="col-md-6">
                <div class="card shadow-sm border-0 p-3 h-100">
                  <h3 class="h6">{{ $process->title }}</h3>
                  <p class="mb-0 text-muted">{{ $process->description }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      @if($service->activeFaqs->isNotEmpty())
        <section id="faq" class="mb-5">
          <h2 class="h4 mb-3">Frequently Asked Questions</h2>
          <div class="accordion" id="serviceFaqAccordion">
            @foreach($service->activeFaqs as $index => $faq)
              <div class="accordion-item glass-card">
                <h2 class="accordion-header" id="serviceFaqHeading{{ $index }}">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#serviceFaqCollapse{{ $index }}">
                    {{ $faq->question }}
                  </button>
                </h2>
                <div id="serviceFaqCollapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#serviceFaqAccordion">
                  <div class="accordion-body">{{ $faq->answer }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </section>
      @endif
    </div>

    <aside class="col-lg-4">
      <div class="card shadow-sm border-0 p-4 mb-4">
        <h2 class="h5">Service Details</h2>
        <ul class="list-unstyled mb-0">
          @if($service->category)
            <li><strong>Category:</strong> {{ $service->category->name }}</li>
          @endif
          @if($service->starting_price)
            <li><strong>Starting Price:</strong> {{ $service->price_label }}</li>
          @endif
          @if($service->duration)
            <li><strong>Duration:</strong> {{ $service->duration }}</li>
          @endif
          @if($service->location)
            <li><strong>Location:</strong> {{ $service->location }}</li>
          @endif
        </ul>
      </div>

      @if($relatedServices->isNotEmpty())
        <div class="card shadow-sm border-0 p-4">
          <h2 class="h5 mb-3">Related Services</h2>
          <ul class="list-unstyled mb-0">
            @foreach($relatedServices as $related)
              <li class="mb-3">
                <a href="{{ route('services.show', $related->slug) }}" class="text-decoration-none">{{ $related->title }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      @endif
    </aside>
  </div>
</div>
@endsection
