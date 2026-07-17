@extends('layouts.app')

@section('title', 'Page Expired | ' . ($siteSetting->site_name ?? 'Francena Decors'))

@push('head')
  <link href="{{ asset('css/error-pages.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="error-page-premium" role="main" aria-labelledby="error-heading-419">
  {{-- Blueprint Grid Background --}}
  <div class="error-blueprint-grid" aria-hidden="true"></div>

  {{-- Floating Particles --}}
  <div class="error-particles" aria-hidden="true">
    <div class="error-particle"><i class="fa-solid fa-clock"></i></div>
    <div class="error-particle"><i class="fa-solid fa-hourglass-half"></i></div>
    <div class="error-particle"><i class="fa-solid fa-rotate"></i></div>
    <div class="error-particle"><i class="fa-solid fa-shield-halved"></i></div>
    <div class="error-particle"><i class="fa-solid fa-key"></i></div>
    <div class="error-particle"><i class="fa-solid fa-lock"></i></div>
  </div>

  {{-- Main Content --}}
  <div class="error-content-wrapper">

    {{-- Animated Hourglass Illustration --}}
    <div class="error-illustration" aria-hidden="true">
      <svg viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
        {{-- Hourglass frame --}}
        <path class="building-line" d="M70 40 L150 40" />
        <path class="building-line" d="M70 140 L150 140" />
        <path class="building-line" d="M80 40 L80 60 Q80 90 110 90 Q140 90 140 60 L140 40" />
        <path class="building-line" d="M80 140 L80 120 Q80 90 110 90 Q140 90 140 120 L140 140" />
        {{-- Sand falling --}}
        <path class="accent-line" d="M110 90 L110 135" />
        {{-- Sand pool bottom --}}
        <path class="accent-line" d="M90 135 Q110 125 130 135" />
        {{-- Sand pool top --}}
        <path class="accent-line" d="M95 55 Q110 65 125 55" />
      </svg>
    </div>

    {{-- Error Code --}}
    <div class="error-code-display" aria-hidden="true">419</div>

    {{-- Label --}}
    <span class="error-label">Error 419: Page Expired</span>

    {{-- Heading --}}
    <h1 id="error-heading-419" class="error-heading">Security Session Expired</h1>

    {{-- Description --}}
    <p class="error-description">
      The security token generated for this page has expired due to inactivity. Please refresh the page and submit the form again to complete your request securely.
    </p>

    {{-- CTA Buttons --}}
    <div class="error-cta-group">
      <a href="javascript:window.location.reload();" class="error-btn-primary">
        <i class="fa-solid fa-arrows-rotate"></i> Refresh Page
      </a>
      <a href="{{ url('/') }}" class="error-btn-outline">
        <i class="fa-solid fa-house-chimney"></i> Back to Home
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
      </div>
    </nav>
  </div>
</section>
@endsection
