@props(['brands' => collect()])

@if($brands->isNotEmpty())
  <div class="row g-4">
    @foreach($brands as $brand)
      <div class="col-md-6 col-lg-4">
        <article class="card h-100 border-0 shadow-sm">
          <div class="card-body p-4">
            @if($brand->logo)
              <img src="{{ $brand->logo->thumbnail_url ?? $brand->logo->url }}" alt="{{ $brand->name }}" class="img-fluid mb-3" style="max-height: 90px; object-fit: contain;">
            @endif
            <h3 class="h5">{{ $brand->name }}</h3>
            <p class="text-muted small mb-3">{{ Str::limit(strip_tags($brand->description), 120) }}</p>
            @if($brand->category)
              <span class="badge text-bg-light">{{ $brand->category }}</span>
            @endif
          </div>
        </article>
      </div>
    @endforeach
  </div>
@endif
