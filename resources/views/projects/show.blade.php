@extends('layouts.app')

@section('title', $project->seo_title ?: $project->title)
@section('meta_description', $project->seo_description ?: $project->short_description)
@section('meta_keywords', $project->seo_keywords)
@section('og_title', $project->seo_title ?: $project->title)
@section('og_description', $project->seo_description ?: $project->short_description)
@section('og_type', 'website')
@section('og_url', route('projects.show', $project))
@section('og_image', $project->coverImage ? image_url($project->coverImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('twitter_title', $project->seo_title ?: $project->title)
@section('twitter_description', $project->seo_description ?: $project->short_description)
@section('twitter_image', $project->coverImage ? image_url($project->coverImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('canonical', route('projects.show', $project))

@extends('layouts.app')

@section('title', ($project->seo_title ?: $project->title) . ' | Fancy Decorators')
@section('meta_description', $project->seo_description ?: $project->short_description)
@section('meta_keywords', $project->seo_keywords)
@section('og_title', $project->seo_title ?: $project->title)
@section('og_description', $project->seo_description ?: $project->short_description)
@section('og_type', 'website')
@section('og_url', route('projects.show', $project))
@section('og_image', $project->coverImage ? image_url($project->coverImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('twitter_title', $project->seo_title ?: $project->title)
@section('twitter_description', $project->seo_description ?: $project->short_description)
@section('twitter_image', $project->coverImage ? image_url($project->coverImage) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('canonical', route('projects.show', $project))

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.5), rgba(5, 4, 7, 0.95)), url('{{ $project->coverImage?->url ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1950&q=80' }}') no-repeat center center/cover; min-height: 45vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    @if($project->category)
      <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">{{ $project->category->name }}</span>
    @endif
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $project->title }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-white-50 text-decoration-none">Projects</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $project->title }}</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8">
        
        <!-- Project Showcase Image -->
        <div class="mb-5">
          <div class="rounded-4 overflow-hidden mb-4 shadow-lg border border-secondary" style="height: 450px;">
            <img src="{{ $project->coverImage?->url ?? asset('images/default-project.jpg') }}" alt="{{ $project->title }}" class="w-100 h-100 object-fit-cover">
          </div>
          
          <h2 class="h3 fw-bold text-white mb-3 font-serif" style="font-family: 'Playfair Display', serif;">About the Project</h2>
          <p class="lead text-white-50 mb-4" style="font-size: 1.15rem;">{{ $project->short_description }}</p>
          <div class="text-muted leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
            {!! nl2br(e($project->description)) !!}
          </div>
        </div>

      </div>

      <!-- Sidebar Specifications Card -->
      <aside class="col-lg-4">
        
        <div class="card border-0 glass-card p-4">
          <h4 class="h5 fw-bold font-serif mb-4 pb-2 border-bottom border-secondary text-primary" style="color: var(--gold); font-family: 'Playfair Display', serif;">Project Specifications</h4>
          
          <ul class="list-unstyled mb-4" style="line-height: 2.2;">
            @if($project->category)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Category:</span>
                <span class="fw-semibold text-white">{{ $project->category->name }}</span>
              </li>
            @endif
            @if($project->location)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-location-dot text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Location:</span>
                <span class="fw-semibold text-white">{{ $project->location }}</span>
              </li>
            @endif
            @if($project->client_company)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-building text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Client:</span>
                <span class="fw-semibold text-white">{{ $project->client_company }}</span>
              </li>
            @endif
            @if($project->end_date)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-calendar text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Completed:</span>
                <span class="fw-semibold text-white">{{ \Illuminate\Support\Carbon::parse($project->end_date)->format('F Y') }}</span>
              </li>
            @endif
            @if($project->budget)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-receipt text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Budget:</span>
                <span class="fw-semibold text-white">{{ $project->budget }}</span>
              </li>
            @endif
            @if($project->project_area)
              <li class="mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-ruler-combined text-gold" style="color: var(--gold);"></i>
                <span class="text-white-50 small me-2">Area:</span>
                <span class="fw-semibold text-white">{{ $project->project_area }}</span>
              </li>
            @endif
          </ul>

          <a href="{{ route('contact.index') }}" class="btn btn-gold w-100 rounded-pill py-2.5">
            Inquire About Similar Build
          </a>
        </div>

      </aside>
    </div>
  </div>
</section>
@endsection

