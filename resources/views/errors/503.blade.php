@extends('layouts.app')

@section('title', 'Service Unavailable | ' . ($siteSetting->site_name ?? 'Francena Decors'))

@push('head')
  <link href="{{ asset('css/error-pages.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="error-page-premium" role="main" aria-labelledby="error-heading-503">
  {{-- Blueprint Grid Background --}}
  <div class="error-blueprint-grid" aria-hidden="true"></div>

  {{-- Floating Particles --}}
  <div class="error-particles" aria-hidden="true">
    <div class="error-particle"><i class="fa-solid fa-compass-drafting"></i></div>
    <div class="error-particle"><i class="fa-solid fa-ruler-combined"></i></div>
    <div class="error-particle"><i class="fa-solid fa-helmet-safety"></i></div>
    <div class="error-particle"><i class="fa-solid fa-trowel"></i></div>
    <div class="error-particle"><i class="fa-solid fa-cubes"></i></div>
    <div class="error-particle"><i class="fa-solid fa-brush"></i></div>
  </div>

  {{-- Main Content --}}
  <div class="error-content-wrapper">

    {{-- Animated House Construction Illustration --}}
    <div class="error-illustration" aria-hidden="true">
      <svg viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
        {{-- House Frame under construction --}}
        <path class="building-line" d="M30 140 L190 140" />
        <path class="building-line" d="M50 140 L50 80 L170 80 L170 140" />
        <path class="building-line" d="M40 80 L110 30 L180 80" />
        {{-- Scaffold lines --}}
        <path class="accent-line" d="M20 140 L20 20 L200 20 L200 140" />
        <path class="accent-line" d="M20 80 L200 80" />
        <path class="accent-line" d="M80 20 L80 140" />
        <path class="accent-line" d="M140 20 L140 140" />
      </svg>
    </div>

    {{-- Error Code --}}
    <div class="error-code-display" aria-hidden="true">503</div>

    {{-- Label --}}
    <span class="error-label">Error 503: Service Unavailable</span>

    {{-- Heading --}}
    <h1 id="error-heading-503" class="error-heading">Scheduled Site Construction</h1>

    {{-- Description --}}
    <p class="error-description">
      We are currently performing scheduled maintenance to upgrade our digital spaces. Please visit us again shortly to explore our newly crafted layouts.
    </p>

    {{-- CTA Buttons --}}
    <div class="error-cta-group">
      <a href="javascript:window.location.reload();" class="error-btn-primary">
        <i class="fa-solid fa-arrows-rotate"></i> Check Status
      </a>
      <a href="{{ route('contact.index') }}" class="error-btn-outline">
        <i class="fa-solid fa-envelope"></i> Contact Support
      </a>
    </div>
  </div>
</section>
@endsection
