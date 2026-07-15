@props(['post'])

<article class="card h-100 overflow-hidden d-flex flex-column border-0 shadow-sm transition-hover">
  <div class="position-relative overflow-hidden" style="height: 240px;">
    @if($post->featuredImage)
      <img src="{{ image_url($post->featuredImage) }}" 
           alt="{{ $post->title }}" 
           class="card-img-top w-100 h-100 object-fit-cover" 
           loading="lazy" 
           decoding="async">
    @else
      <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=800&q=80" 
           alt="Francena Decors journal card" 
           class="card-img-top w-100 h-100 object-fit-cover" 
           loading="lazy" 
           decoding="async">
    @endif
    
    @if($post->category)
      <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" 
         class="badge bg-gold text-uppercase px-3 py-2 position-absolute top-0 start-0 m-3 text-decoration-none shadow-sm fs-8 text-white fw-bold">
        {{ $post->category->name }}
      </a>
    @endif
  </div>

  <div class="card-body p-4 d-flex flex-column flex-grow-1">
    <div class="d-flex align-items-center gap-3 text-muted small mb-3">
      <span><i class="fa-solid fa-calendar-days text-primary me-1"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recent' }}</span>
      <span><i class="fa-solid fa-clock text-primary me-1"></i> {{ $post->reading_time }} min read</span>
    </div>
    
    <h3 class="h5 fw-bold mb-2 card-title-hover" style="font-family: 'Playfair Display', serif;">
      <a href="{{ route('blog.show', $post) }}" class="text-white text-decoration-none line-clamp-2">
        {{ $post->title }}
      </a>
    </h3>
    
    <p class="text-muted small flex-grow-1 mb-4 line-clamp-3">
      {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}
    </p>

    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light-subtle">
      <div class="d-flex align-items-center gap-2">
        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
          <i class="fa-solid fa-user"></i>
        </div>
        <span class="small text-muted fw-semibold">{{ $post->author?->name ?? 'Admin' }}</span>
      </div>
      <a href="{{ route('blog.show', $post) }}" class="btn btn-sm btn-outline-light text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Read More</a>
    </div>
  </div>
</article>

<style>
  .transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
  }
  .card-title-hover a:hover {
    color: var(--button-background, #d4af5f) !important;
  }
  .fs-8 {
    font-size: 0.75rem;
  }
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
