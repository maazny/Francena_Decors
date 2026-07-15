@extends('layouts.app')

@section('title', ($member->seo_title ?: $member->full_name . ' - ' . $member->designation) . ' | Francena Decors')
@section('meta_description', $member->seo_description ?: $member->short_bio)
@section('meta_keywords', $member->seo_keywords)
@section('og_title', $member->seo_title ?: $member->full_name)
@section('og_description', $member->seo_description ?: $member->short_bio)
@section('og_type', 'profile')
@section('og_url', route('team.show', $member))
@section('og_image', $member->profilePhoto ? image_url($member->profilePhoto) : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1900&q=80')
@section('twitter_title', $member->seo_title ?: $member->full_name)
@section('twitter_description', $member->seo_description ?: $member->short_bio)
@section('twitter_image', $member->profilePhoto ? image_url($member->profilePhoto) : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1900&q=80')
@section('canonical', route('team.show', $member))

@section('content')
<!-- Page Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(180deg, rgba(8, 7, 10, 0.5), rgba(5, 4, 7, 0.95)), url('{{ $member->coverPhoto?->url ?? 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1950&q=80' }}') no-repeat center center/cover; min-height: 45vh; display: flex; align-items: center;">
  <div class="container text-center py-4">
    @if($member->department)
      <span class="text-uppercase tracking-wider small text-warning mb-2 d-block">{{ $member->department->name }}</span>
    @endif
    <h1 class="display-4 fw-bold mb-3 font-serif" style="font-family: 'Playfair Display', serif; color: var(--gold);">{{ $member->full_name }}</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('team.index') }}" class="text-white-50 text-decoration-none">Team</a></li>
        <li class="breadcrumb-item active text-warning" aria-current="page">{{ $member->full_name }}</li>
      </ol>
    </nav>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      
      <!-- Left Profile Sidebar -->
      <div class="col-lg-4 col-xl-3">
        <div class="card border-0 glass-card p-4 text-center">
          <div class="client-avatar-wrapper rounded-circle overflow-hidden shadow-lg mx-auto mb-4 border border-secondary" style="width: 160px; height: 160px; border-width: 3px !important; border-color: var(--gold) !important;">
            @if($member->profilePhoto)
              <img src="{{ image_url($member->profilePhoto) }}" alt="{{ $member->full_name }}" class="w-100 h-100 object-fit-cover">
            @else
              <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                <i class="fa-solid fa-user fa-4x"></i>
              </div>
            @endif
          </div>
          
          <h4 class="h5 fw-bold text-white mb-1 font-serif" style="font-family: 'Playfair Display', serif;">{{ $member->full_name }}</h4>
          <p class="text-muted small mb-3">{{ $member->designation }}</p>
          
          <ul class="list-unstyled text-start mb-4 border-top border-bottom border-secondary py-3 small" style="line-height: 2;">
            @if($member->email)
              <li class="mb-2">
                <i class="fa-solid fa-envelope text-gold me-2" style="color: var(--gold);"></i>
                <a href="mailto:{{ $member->email }}" class="text-white-50 text-decoration-none hover-gold">{{ $member->email }}</a>
              </li>
            @endif
            @if($member->phone)
              <li class="mb-2">
                <i class="fa-solid fa-phone text-gold me-2" style="color: var(--gold);"></i>
                <span class="text-white-50">{{ $member->phone }}</span>
              </li>
            @endif
            @if($member->experience_years)
              <li class="mb-2">
                <i class="fa-solid fa-briefcase text-gold me-2" style="color: var(--gold);"></i>
                <span class="text-white-50">Experience: {{ $member->experience_years }} Years</span>
              </li>
            @endif
            @if($member->qualification)
              <li class="mb-2">
                <i class="fa-solid fa-graduation-cap text-gold me-2" style="color: var(--gold);"></i>
                <span class="text-white-50">{{ $member->qualification }}</span>
              </li>
            @endif
            @if($member->specialization)
              <li class="mb-2">
                <i class="fa-solid fa-circle-nodes text-gold me-2" style="color: var(--gold);"></i>
                <span class="text-white-50">{{ $member->specialization }}</span>
              </li>
            @endif
          </ul>

          <!-- Social Links -->
          @if($member->socialLinks->isNotEmpty())
            <div class="d-flex justify-content-center gap-3">
              @foreach($member->socialLinks as $link)
                @php
                  $icon = 'fa-link';
                  if (Str::contains(strtolower($link->platform), 'facebook')) $icon = 'fa-facebook-f';
                  elseif (Str::contains(strtolower($link->platform), 'twitter') || Str::contains(strtolower($link->platform), 'x')) $icon = 'fa-x-twitter';
                  elseif (Str::contains(strtolower($link->platform), 'linkedin')) $icon = 'fa-linkedin-in';
                  elseif (Str::contains(strtolower($link->platform), 'instagram')) $icon = 'fa-instagram';
                  elseif (Str::contains(strtolower($link->platform), 'github')) $icon = 'fa-github';
                @endphp
                <a href="{{ $link->url }}" target="_blank" class="text-white-50 hover-gold" style="font-size: 1.1rem;" aria-label="{{ $link->platform }}">
                  <i class="fab {{ $icon }}"></i>
                </a>
              @endforeach
            </div>
          @endif

        </div>
      </div>

      <!-- Right Profile Content -->
      <div class="col-lg-8 col-xl-9">
        
        <!-- Bio Card -->
        <div class="card border-0 glass-card p-4 p-md-5 mb-4">
          <h2 class="h3 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">About Me</h2>
          
          @if($member->short_bio)
            <p class="lead text-white-50 mb-4" style="font-size: 1.15rem; font-style: italic; line-height: 1.6;">
              "{{ $member->short_bio }}"
            </p>
          @endif
          
          <div class="text-muted leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
            {!! nl2br(e($member->full_bio)) !!}
          </div>
        </div>

        <!-- Skills Progress Card -->
        @if($member->skills->isNotEmpty())
          <div class="card border-0 glass-card p-4 p-md-5 mb-4">
            <h3 class="h4 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Skills & Expertise</h3>
            <div class="row g-4">
              @foreach($member->skills as $skill)
                <div class="col-md-6">
                  <div class="d-flex justify-content-between mb-1.5 small">
                    <span class="text-white-50 fw-semibold">{{ $skill->skill_name }}</span>
                    <span class="text-warning fw-bold">{{ $skill->skill_percentage }}%</span>
                  </div>
                  <div class="progress bg-dark" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $skill->skill_percentage }}%; background-color: var(--gold);" aria-valuenow="{{ $skill->skill_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Certifications & Education -->
        @if($member->certifications->isNotEmpty())
          <div class="card border-0 glass-card p-4 p-md-5">
            <h3 class="h4 fw-bold text-white mb-4 font-serif" style="font-family: 'Playfair Display', serif;">Certifications & Credentials</h3>
            <div class="row g-4">
              @foreach($member->certifications as $cert)
                <div class="col-md-6">
                  <div class="d-flex align-items-start gap-3">
                    <div class="p-2.5 bg-dark rounded text-warning" style="background: rgba(206, 154, 95, 0.18) !important;">
                      <i class="fa-solid fa-award fa-lg"></i>
                    </div>
                    <div>
                      <h5 class="text-white mb-1 h6 fw-bold">{{ $cert->title }}</h5>
                      <p class="text-muted small mb-0">{{ $cert->organization }} @if($cert->issue_date) • {{ $cert->issue_date->format('M Y') }} @endif</p>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </div>

    </div>
  </div>
</section>
@endsection

