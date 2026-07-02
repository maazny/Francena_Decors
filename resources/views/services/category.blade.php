@extends('layouts.app')

@section('title', $category->name . ' Services')
@section('content')
<div class="container py-5">
  <div class="row align-items-center mb-5">
    <div class="col-lg-8">
      <h1 class="display-6 fw-bold">{{ $category->name }}</h1>
      <p class="text-muted">{{ $category->description }}</p>
    </div>
    <div class="col-lg-4 text-lg-end">
      <a href="{{ route('services.index') }}" class="btn btn-outline-dark">All Services</a>
    </div>
  </div>

  <div class="row g-4">
    @forelse($category->activeServices as $service)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="{{ $service->featuredImage?->url ?? asset('images/default-service.jpg') }}" class="card-img-top" alt="{{ $service->title }}" style="height: 220px; object-fit: cover;">
          <div class="card-body">
            <h3 class="h5">{{ $service->title }}</h3>
            <p class="text-muted small">{{ Str::limit($service->short_description, 120) }}</p>
            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-dark btn-sm">View Service</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-light">No services are published in this category yet.</div>
      </div>
    @endforelse
  </div>
</div>
@endsection
