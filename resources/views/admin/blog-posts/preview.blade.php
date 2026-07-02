@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title)
@section('meta_description', $post->seo_description ?: $post->excerpt)
@section('meta_keywords', $post->seo_keywords)

@section('content')
<article class="py-5" style="background-color: var(--background-color, #ffffff); color: var(--text-color, #222222);">
  <div class="container">
    <!-- Back to Admin Bar -->
    <div class="alert alert-dark d-flex justify-content-between align-items-center mb-5 border-0 rounded shadow-sm">
      <div class="fw-semibold"><i class="fa-solid fa-eye me-2 text-warning"></i> Preview Mode — Draft / Admin View</div>
      <a href="{{ route('admin.blog-posts.edit', $post) }}" class="btn btn-warning btn-sm fw-bold">Back to Editor</a>
    </div>

    <!-- Post Header -->
    <header class="mb-4">
      <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-primary text-uppercase px-3 py-2 fs-7">{{ $post->category?->name ?? 'Uncategorized' }}</span>
        <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i> {{ $post->reading_time }} min read</span>
      </div>
      <h1 class="display-4 fw-extrabold mb-3" style="font-family: 'Playfair Display', serif;">{{ $post->title }}</h1>
      <div class="d-flex align-items-center gap-3 text-muted small border-bottom pb-4 mb-4">
        <div><i class="fa-solid fa-user me-1 text-primary"></i> By <strong>{{ $post->author?->name ?? 'N/A' }}</strong></div>
        <div><i class="fa-solid fa-calendar-days me-1"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Immediately' }}</div>
      </div>
    </header>

    <!-- Post Banner Image -->
    @if($post->bannerImage || $post->featuredImage)
      <div class="mb-5 rounded overflow-hidden shadow-lg">
        <img src="{{ $post->bannerImage ? image_url($post->bannerImage) : image_url($post->featuredImage) }}" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;" alt="Blog banner">
      </div>
    @endif

    <div class="row g-5">
      <!-- Main Content -->
      <div class="col-lg-8">
        @if($post->excerpt)
          <div class="lead fw-normal text-muted mb-4 border-start border-4 border-primary ps-3" style="font-size: 1.2rem; font-style: italic;">
            {{ $post->excerpt }}
          </div>
        @endif

        <div class="blog-content mb-5" style="line-height: 1.8; font-size: 1.1rem;">
          {!! $post->content !!}
        </div>

        <!-- Tags List -->
        @if($post->tags->isNotEmpty())
          <div class="mb-5 pt-3 border-top">
            <h5 class="h6 text-uppercase fw-bold text-muted mb-3">Tags</h5>
            <div class="d-flex flex-wrap gap-2">
              @foreach($post->tags as $tag)
                <span class="badge bg-light text-dark border px-3 py-2"><i class="fa-solid fa-hashtag text-primary me-1"></i>{{ $tag->name }}</span>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Image Gallery -->
        @if($post->galleries->isNotEmpty())
          <div class="mb-5 pt-4 border-top">
            <h3 class="h4 mb-4" style="font-family: 'Playfair Display', serif;">Post Gallery</h3>
            <div class="row g-3">
              @foreach($post->galleries as $gallery)
                @if($gallery->media)
                  <div class="col-md-4 col-sm-6">
                    <div class="card border rounded overflow-hidden shadow-sm h-100">
                      <img src="{{ image_url($gallery->media) }}" class="card-img-top" style="height: 160px; object-fit: cover;" alt="Gallery image">
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
        <div class="pt-4 border-top">
          @if(request()->get('allow_comments', 1))
            <div class="card bg-light border p-4 text-center rounded">
              <i class="fa-solid fa-comments fa-2x text-muted mb-2"></i>
              <h5 class="h6 mb-2">Comments Section Enabled</h5>
              <p class="text-muted small mb-0">Comments and discussion are open for this article.</p>
            </div>
          @else
            <div class="card bg-light border p-4 text-center rounded">
              <i class="fa-solid fa-comment-slash fa-2x text-muted mb-2"></i>
              <h5 class="h6 mb-2">Comments Disabled</h5>
              <p class="text-muted small mb-0">Comments are disabled for this article.</p>
            </div>
          @endif
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <div class="card border rounded p-4 shadow-sm mb-4">
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
      </div>
    </div>
  </div>
</article>
@endsection
