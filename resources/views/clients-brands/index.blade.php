@extends('layouts.app')

@section('title', 'Clients & Brands')
@section('content')
<div class="container py-5">
  <div class="row justify-content-center text-center mb-5">
    <div class="col-lg-8">
      <span class="section-label">Clients & Brands</span>
      <h1 class="display-5 fw-semibold">Trusted by leading businesses and homeowners</h1>
      <p class="text-muted">A showcase of the brands and clients who trust our craftsmanship, delivery, and attention to detail.</p>
    </div>
  </div>

  <form method="GET" class="row g-3 mb-4 justify-content-center">
    <div class="col-md-5">
      <input type="text" name="search" class="form-control" placeholder="Search clients or brands" value="{{ $search }}">
    </div>
    <div class="col-md-3">
      <select name="category" class="form-select">
        <option value="">All categories</option>
        @foreach($categories as $item)
          <option value="{{ $item }}" {{ $category === $item ? 'selected' : '' }}>{{ $item }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-dark w-100">Filter</button>
    </div>
  </form>

  <div class="row g-4">
    @forelse($brands as $brand)
      <div class="col-md-6 col-lg-4">
        <article class="card h-100 border-0 shadow-sm">
          <div class="card-body p-4">
            @if($brand->logo)
              <img src="{{ $brand->logo->thumbnail_url ?? $brand->logo->url }}" alt="{{ $brand->name }}" class="img-fluid mb-3" style="max-height: 90px; object-fit: contain;">
            @endif
            <h3 class="h5">{{ $brand->name }}</h3>
            <p class="text-muted small mb-3">{{ Str::limit(strip_tags($brand->description), 140) }}</p>
            @if($brand->category)
              <span class="badge text-bg-light">{{ $brand->category }}</span>
            @endif
            @if($brand->website_url)
              <div class="mt-3">
                <a href="{{ $brand->website_url }}" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm">Visit Website</a>
              </div>
            @endif
          </div>
        </article>
      </div>
    @empty
      <div class="col-12 text-center text-muted py-5">No clients or brands found.</div>
    @endforelse
  </div>

  <div class="mt-4">{{ $brands->links() }}</div>
</div>
@endsection
