@extends('layouts.app')

@section('title', $project->title)
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
