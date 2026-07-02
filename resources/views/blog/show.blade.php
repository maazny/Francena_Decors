@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title)
@section('meta_description', $post->seo_description ?: $post->excerpt)
@section('meta_keywords', $post->seo_keywords)

@section('content')
<article class="py-5" style="background-color: var(--background-color, #ffffff); color: var(--text-color, #222222);">
  <div class="container">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa-solid fa-house me-1"></i> Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">Journal</a></li>
        <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;">{{ $post->title }}</li>
      </ol>
    </nav>

    <!-- Header Details -->
    <header class="mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        @if($post->category)
          <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="badge bg-primary text-uppercase px-3 py-2 text-decoration-none text-white fw-bold fs-7">
            {{ $post->category->name }}
          </a>
        @endif
        <span class="text-muted small"><i class="fa-solid fa-clock me-1 text-primary"></i> {{ $post->reading_time }} min read</span>
      </div>
      <h1 class="display-4 fw-extrabold mb-3" style="font-family: 'Playfair Display', serif;">{{ $post->title }}</h1>
      <div class="d-flex align-items-center gap-3 text-muted small border-bottom pb-4 mb-4">
        <div><i class="fa-solid fa-user me-1 text-primary"></i> By <strong>{{ $post->author?->name ?? 'N/A' }}</strong></div>
        <div><i class="fa-solid fa-calendar-days me-1"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Immediately' }}</div>
      </div>
    </header>

    <!-- Banner Media -->
    @if($post->bannerImage || $post->featuredImage)
      <div class="mb-5 rounded overflow-hidden shadow-lg">
        <img src="{{ $post->bannerImage ? image_url($post->bannerImage) : image_url($post->featuredImage) }}" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;" alt="Article banner" loading="lazy">
      </div>
    @endif

    <div class="row g-5">
      <!-- Main Reader Column -->
      <div class="col-lg-8">
        
        <!-- Excerpt -->
        @if($post->excerpt)
          <div class="lead fw-normal text-muted mb-4 border-start border-4 border-primary ps-3" style="font-size: 1.2rem; font-style: italic;">
            {{ $post->excerpt }}
          </div>
        @endif

        <!-- Body content -->
        <div class="blog-content mb-5" style="line-height: 1.8; font-size: 1.1rem;">
          {!! $post->content !!}
        </div>

        <!-- Tags Cloud -->
        @if($post->tags->isNotEmpty())
          <div class="mb-5 pt-3 border-top border-light-subtle">
            <h5 class="h6 text-uppercase fw-bold text-muted mb-3">Article Tags</h5>
            <div class="d-flex flex-wrap gap-2">
              @foreach($post->tags as $tag)
                <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="badge bg-light text-dark border text-decoration-none px-3 py-2">
                  <i class="fa-solid fa-hashtag text-primary me-1"></i>{{ $tag->name }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Image Gallery Grid -->
        @if($post->galleries->isNotEmpty())
          <div class="mb-5 pt-4 border-top border-light-subtle">
            <h3 class="h4 mb-4" style="font-family: 'Playfair Display', serif;">Image Gallery</h3>
            <div class="row g-3">
              @foreach($post->galleries as $gallery)
                @if($gallery->media)
                  <div class="col-md-4 col-sm-6">
                    <div class="card border rounded overflow-hidden shadow-sm h-100">
                      <img src="{{ image_url($gallery->media) }}" class="card-img-top" style="height: 160px; object-fit: cover;" alt="Gallery image" loading="lazy">
                      @if($gallery->caption)
                        <div class="card-body p-2 bg-light">
                          <p class="card-text small text-muted text-center mb-0">{{ $gallery->caption }}</p>
                        </div>
                      @endif
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        @endif

        <!-- Comments Section Indicator -->
        <div class="pt-4 border-top border-light-subtle">
          @if(request()->get('allow_comments', 1))
            <div class="card bg-transparent border p-4 text-center rounded">
              <i class="fa-solid fa-comments fa-2x text-muted mb-2"></i>
              <h5 class="h6 mb-2">Comments Are Open</h5>
              <p class="text-muted small mb-0">Join the discussion by contacting our team or dropping by our main offices.</p>
            </div>
          @else
            <div class="card bg-transparent border p-4 text-center rounded">
              <i class="fa-solid fa-comment-slash fa-2x text-muted mb-2"></i>
              <h5 class="h6 mb-2">Comments Disabled</h5>
              <p class="text-muted small mb-0">Comments are disabled for this article.</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Sidebar widgets -->
      <div class="col-lg-4">
        
        <!-- Author Widget -->
        <div class="card border p-4 shadow-sm mb-4">
          <h4 class="h5 mb-3" style="font-family: 'Playfair Display', serif;">About the Author</h4>
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
              <i class="fa-solid fa-user fa-lg"></i>
            </div>
            <div>
              <h5 class="h6 mb-1">{{ $post->author?->name ?? 'Administrator' }}</h5>
              <span class="text-muted small">Fancy Decorators Contributor</span>
            </div>
          </div>
          <p class="small text-muted mb-0">Author and design consultant at Fancy Decorators, specializing in high-end architecture and renovations.</p>
        </div>

        <!-- Latest Posts Widget -->
        <div class="card border p-4 shadow-sm mb-4">
          <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Latest Posts</h4>
          <div class="d-flex flex-column gap-3">
            @foreach($latestPosts as $lPost)
              <div class="d-flex gap-3 align-items-center">
                @if($lPost->featuredImage)
                  <img src="{{ image_url($lPost->featuredImage) }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" alt="Cover image" loading="lazy">
                @endif
                <div>
                  <h6 class="small mb-1"><a href="{{ route('blog.show', $lPost) }}" class="text-white text-decoration-none fw-semibold">{{ $lPost->title }}</a></h6>
                  <span class="small text-muted" style="font-size: 0.75rem;">{{ $lPost->published_at ? $lPost->published_at->format('M d, Y') : 'Recent' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Categories Widget -->
        <div class="card border p-4 shadow-sm mb-4">
          <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Categories</h4>
          <ul class="list-group list-group-flush">
            @foreach($sidebarCategories as $category)
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="text-decoration-none text-white-50">
                  {{ $category->name }}
                </a>
                <span class="badge bg-secondary rounded-pill small">{{ $category->posts_count }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    <!-- Related Posts section at bottom -->
    @if($relatedPosts->isNotEmpty())
      <div class="mt-5 pt-5 border-top border-light-subtle">
        <h3 class="h4 mb-4 text-center text-md-start" style="font-family: 'Playfair Display', serif;">Related Articles</h3>
        <div class="row g-4">
          @foreach($relatedPosts as $rPost)
            <div class="col-md-4">
              <x-blog-card :post="$rPost" />
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</article>
@endsection
