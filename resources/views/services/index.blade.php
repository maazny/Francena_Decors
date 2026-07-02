@extends('layouts.app')

@section('title', 'Our Services')
@section('content')
<div class="container py-5">
  <div class="row align-items-center mb-5">
    <div class="col-lg-8">
      <h1 class="display-6 fw-bold">Our Services</h1>
      <p class="text-muted">Explore the premium construction, renovation, and design services we offer for luxury homes, commercial spaces, and interiors.</p>
    </div>
    <div class="col-lg-4 text-lg-end">
      <a href="{{ route('home') }}" class="btn btn-outline-dark">Back to Home</a>
    </div>
  </div>

  <div class="row gy-4">
    @forelse($services as $service)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="{{ $service->featuredImage?->url ?? asset('images/default-service.jpg') }}" class="card-img-top" alt="{{ $service->title }}" style="height: 220px; object-fit: cover;">
          <div class="card-body">
            <div class="mb-3">
              @if($service->category)
                <span class="badge bg-light text-dark">{{ $service->category->name }}</span>
              @endif
            </div>
            <h3 class="h5">{{ $service->title }}</h3>
            <p class="text-muted small">{{ Str::limit($service->short_description, 120) }}</p>
            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-dark btn-sm">View Service</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-light">No services found yet.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-4">{{ $services->links() }}</div>
</div>
@endsection
