@extends('layouts.app')

@section('title', 'Access Denied | ' . ($siteSetting->site_name ?? 'Francena Decors'))

@push('head')
  <link href="{{ asset('css/error-pages.css') }}" rel="stylesheet" />
@endpush

@section('content')
<section class="error-page-premium" role="main" aria-labelledby="error-heading-403">
  {{-- Blueprint Grid Background --}}
  <div class="error-blueprint-grid" aria-hidden="true"></div>

  {{-- Caution Tape Corner --}}
  <div class="error-caution-tape" aria-hidden="true"></div>

  {{-- Floating Particles --}}
  <div class="error-particles" aria-hidden="true">
    <div class="error-particle"><i class="fa-solid fa-lock"></i></div>
    <div class="error-particle"><i class="fa-solid fa-shield-halved"></i></div>
    <div class="error-particle"><i class="fa-solid fa-key"></i></div>
    <div class="error-particle"><i class="fa-solid fa-user-lock"></i></div>
    <div class="error-particle"><i class="fa-solid fa-ban"></i></div>
    <div class="error-particle"><i class="fa-solid fa-fingerprint"></i></div>
  </div>

  {{-- Main Content --}}
  <div class="error-content-wrapper">

    {{-- Animated Shield / Lock Illustration --}}
    <div class="error-illustration" aria-hidden="true">
      <svg viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg">
        <g class="shield-glow">
          {{-- Shield shape --}}
          <path class="shield-body"
            d="M110 15 L170 45 L170 95 Q170 140 110 170 Q50 140 50 95 L50 45 Z" />
          {{-- Shield fill (subtle) --}}
          <path class="shield-fill"
            d="M110 15 L170 45 L170 95 Q170 140 110 170 Q50 140 50 95 L50 45 Z" />
        </g>
        {{-- Lock body --}}
        <rect class="lock-line" x="90" y="90" width="40" height="35" rx="4" />
        {{-- Lock shackle --}}
        <path class="lock-line" d="M97 90 L97 78 Q97 65 110 65 Q123 65 123 78 L123 90" />
        {{-- Lock fill (keyhole) --}}
        <circle class="lock-fill" cx="110" cy="103" r="5" />
        <path class="lock-fill" d="M107 106 L110 118 L113 106" />
      </svg>
    </div>

    {{-- Error Code --}}
    <div class="error-code-display" aria-hidden="true">403</div>

    {{-- Label --}}
    <span class="error-label error-label--danger">Access Restricted</span>

    {{-- Heading --}}
    <h1 id="error-heading-403" class="error-heading">Authorized Personnel Only</h1>

    {{-- Description --}}
    <p class="error-description">
      This area is restricted to authorized team members. If you believe you should have access, please contact our administration or submit an access request.
    </p>

    {{-- CTA Buttons --}}
    <div class="error-cta-group">
      <a href="{{ url('/') }}" class="error-btn-primary">
        <i class="fa-solid fa-house-chimney"></i> Back to Home
      </a>
      <a href="{{ route('contact.index') }}" class="error-btn-outline">
        <i class="fa-solid fa-paper-plane"></i> Request Access
      </a>
    </div>

    {{-- Quick Navigation Chips --}}
    <nav class="error-quick-nav" aria-label="Quick navigation">
      <p class="error-quick-nav-label">Or explore our public pages</p>
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
