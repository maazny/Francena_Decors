@extends('layouts.app')

@section('title', 'Page Not Found | ' . ($siteSetting->site_name ?? 'Francena Decors'))

@push('head')
  <link href="{{ asset('css/error-pages.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="error-page-premium" role="main" aria-labelledby="error-heading-404">
  {{-- Blueprint Grid Background --}}
  <div class="error-blueprint-grid" aria-hidden="true"></div>

  {{-- Floating Construction Particles --}}
  <div class="error-particles" aria-hidden="true">
    <div class="error-particle"><i class="fa-solid fa-hard-hat"></i></div>
    <div class="error-particle"><i class="fa-solid fa-cubes"></i></div>
    <div class="error-particle"><i class="fa-solid fa-ruler-combined"></i></div>
    <div class="error-particle"><i class="fa-solid fa-compass-drafting"></i></div>
    <div class="error-particle"><i class="fa-solid fa-hammer"></i></div>
    <div class="error-particle"><i class="fa-solid fa-screwdriver-wrench"></i></div>
  </div>

  {{-- Main Content --}}
  <div class="error-content-wrapper">

    {{-- Animated Blueprint Building Illustration --}}
    <div class="error-illustration" aria-hidden="true">
      <svg viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
        {{-- Ground line --}}
        <path class="building-line" d="M10 170 L210 170" />
        {{-- Left wall --}}
        <path class="building-line" d="M40 170 L40 60" />
        {{-- Right wall --}}
        <path class="building-line" d="M180 170 L180 60" />
        {{-- Roof peak --}}
        <path class="building-line" d="M30 60 L110 20 L190 60" />
        {{-- Door --}}
        <path class="building-line" d="M90 170 L90 120 L130 120 L130 170" />
        {{-- Left window --}}
        <path class="building-line" d="M55 90 L55 110 L75 110 L75 90 Z" />
        {{-- Right window --}}
        <path class="building-line" d="M145 90 L145 110 L165 110 L165 90 Z" />
        {{-- Accent: question mark above roof --}}
        <path class="accent-line" d="M100 8 Q100 -2 110 -2 Q120 -2 120 8 Q120 14 110 14 M110 20 L110 22" />
      </svg>
    </div>

    {{-- Error Code --}}
    <div class="error-code-display" aria-hidden="true">404</div>

    {{-- Label --}}
    <span class="error-label">Error 404: Page Not Found</span>

    {{-- Heading --}}
    <h1 id="error-heading-404" class="error-heading">This Blueprint Doesn't Exist</h1>

    {{-- Description --}}
    <p class="error-description">
      The page you're looking for may have been moved, renamed, or is currently under construction. Let us help you find your way back to our luxury spaces.
    </p>

    {{-- CTA Buttons --}}
    <div class="error-cta-group">
      <a href="{{ url('/') }}" class="error-btn-primary">
        <i class="fa-solid fa-house-chimney"></i> Back to Home
      </a>
      <a href="{{ route('contact.index') }}" class="error-btn-outline">
        <i class="fa-solid fa-envelope"></i> Contact Support
      </a>
    </div>

    {{-- Quick Navigation Chips --}}
    <nav class="error-quick-nav" aria-label="Quick navigation">
      <p class="error-quick-nav-label">Or explore our popular pages</p>
      <div class="error-nav-chips">
        <a href="{{ route('services.index') }}" class="error-nav-chip">
          <i class="fa-solid fa-briefcase"></i> Services
        </a>
        <a href="{{ route('projects.index') }}" class="error-nav-chip">
          <i class="fa-solid fa-building"></i> Projects
        </a>
        <a href="{{ route('gallery') }}" class="error-nav-chip">
          <i class="fa-solid fa-images"></i> Gallery
        </a>
        <a href="{{ route('blog.index') }}" class="error-nav-chip">
          <i class="fa-solid fa-newspaper"></i> Blog
        </a>
        <a href="{{ route('careers.index') }}" class="error-nav-chip">
          <i class="fa-solid fa-user-tie"></i> Careers
        </a>
      </div>
    </nav>
  </div>
</section>
@endsection
