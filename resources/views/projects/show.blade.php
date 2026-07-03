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

@section('content')
<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-8">
      <img src="{{ $project->coverImage?->url ?? asset('images/default-project.jpg') }}" alt="{{ $project->title }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 460px; object-fit: cover;">
      <h1 class="display-6 fw-bold mt-4">{{ $project->title }}</h1>
      <p class="text-muted">{{ $project->short_description }}</p>
      <div class="mt-4">
        {!! nl2br(e($project->description)) !!}
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h2 class="h5">Project Details</h2>
          <ul class="list-unstyled mb-0">
            @if($project->category)
              <li><strong>Category:</strong> {{ $project->category->name }}</li>
            @endif
            @if($project->location)
              <li><strong>Location:</strong> {{ $project->location }}</li>
            @endif
            @if($project->client_company)
              <li><strong>Client:</strong> {{ $project->client_company }}</li>
            @endif
            @if($project->end_date)
              <li><strong>Completed:</strong> {{ \Illuminate\Support\Carbon::parse($project->end_date)->format('F Y') }}</li>
            @endif
            @if($project->budget)
              <li><strong>Budget:</strong> {{ $project->budget }}</li>
            @endif
            @if($project->project_area)
              <li><strong>Area:</strong> {{ $project->project_area }}</li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
