@extends('layouts.app')

@section('title', 'Server Error | ' . ($siteSetting->site_name ?? 'Francena Decors'))

@push('head')
  <link href="{{ asset('css/error-pages.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="error-page-premium" role="main" aria-labelledby="error-heading-500">
  {{-- Blueprint Grid Background --}}
  <div class="error-blueprint-grid" aria-hidden="true"></div>

  {{-- Floating Particles --}}
  <div class="error-particles" aria-hidden="true">
    <div class="error-particle"><i class="fa-solid fa-hard-hat"></i></div>
    <div class="error-particle"><i class="fa-solid fa-screwdriver-wrench"></i></div>
    <div class="error-particle"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <div class="error-particle"><i class="fa-solid fa-gear"></i></div>
    <div class="error-particle"><i class="fa-solid fa-hammer"></i></div>
    <div class="error-particle"><i class="fa-solid fa-trowel-bricks"></i></div>
  </div>

  {{-- Main Content --}}
  <div class="error-content-wrapper">

    {{-- Animated Crane Illustration --}}
    <div class="error-illustration" aria-hidden="true">
      <svg viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
        {{-- Ground structure --}}
        <path class="building-line" d="M10 170 L210 170" />
        {{-- Crane Tower --}}
        <path class="building-line" d="M50 170 L50 30 L60 30 L60 170 Z" />
        {{-- Crane Jib --}}
        <path class="building-line" d="M20 40 L180 40" />
        {{-- Counterweight & Cab --}}
        <path class="building-line" d="M20 40 L50 30 L50 50 Z" />
        <path class="building-line" d="M50 30 L180 40 L50 40" />
        {{-- Hook and Wire --}}
        <path class="accent-line" d="M140 40 L140 80" />
        <path class="accent-line" d="M135 80 L145 80 Q145 85 140 85 Q135 85 137 80" />
        {{-- Lifted Block --}}
        <rect class="building-line" x="115" y="90" width="50" height="35" rx="3" />
        <path class="accent-line" d="M125 90 L140 80 L155 90" />
      </svg>
    </div>

    {{-- Error Code --}}
    <div class="error-code-display" aria-hidden="true">500</div>

    {{-- Label --}}
    <span class="error-label error-label--danger">Error 500: Server Error</span>

    {{-- Heading --}}
    <h1 id="error-heading-500" class="error-heading">Blueprint Malfunction</h1>

    {{-- Description --}}
    <p class="error-description">
      Our server encountered an unexpected error on this floor. Rest assured, our site engineering team is on-site resolving the problem. Please try again later.
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
      </div>
    </nav>
  </div>
</section>
@endsection
