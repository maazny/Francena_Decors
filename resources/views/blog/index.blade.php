@extends('layouts.app')

@section('title', 'Journal & Insights | Fancy Decorators')
@section('meta_description', 'Read the latest trends, insights, and design tips from our luxury architecture and construction experts.')

@section('content')
<section class="py-5" style="background-color: var(--background-color, #ffffff); color: var(--text-color, #222222); min-height: 80vh;">
  <div class="container">
    
    <!-- Breadcrumb & Header -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="fa-solid fa-house me-1"></i> Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Journal</li>
      </ol>
    </nav>

    <div class="row mb-5">
      <div class="col-lg-8">
        <h1 class="display-5 fw-extrabold mb-3" style="font-family: 'Playfair Display', serif;">Our Journal</h1>
        <p class="text-muted lead">Discover luxury architectural insights, professional styling tips, and construction guides curated by our design directors.</p>
      </div>
    </div>

    <!-- Active Filters Indicators -->
    @if(request()->anyFilled(['search', 'category', 'tag', 'archive', 'featured']))
      <div class="alert alert-light border border-light-subtle d-flex flex-wrap gap-2 align-items-center mb-4 p-3 rounded">
        <span class="small fw-semibold text-muted"><i class="fa-solid fa-filter me-1"></i> Active Filters:</span>
        
        @if(request()->filled('search'))
          <span class="badge bg-secondary px-3 py-2">Search: "{{ request('search') }}"</span>
        @endif
        @if(request()->filled('category'))
          <span class="badge bg-primary px-3 py-2">Category: {{ request('category') }}</span>
        @endif
        @if(request()->filled('tag'))
          <span class="badge bg-gold px-3 py-2">Tag: #{{ request('tag') }}</span>
        @endif
        @if(request()->filled('archive'))
          <span class="badge bg-info px-3 py-2">Archive: {{ request('archive') }}</span>
        @endif
        @if(request()->boolean('featured'))
          <span class="badge bg-warning px-3 py-2">Featured Articles</span>
        @endif

        <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-danger ms-auto fw-bold">Reset Filters</a>
      </div>
    @endif

    <div class="row g-4">
      <!-- Main Articles Grid -->
      <div class="col-lg-8">
        <div class="row g-4">
          @forelse($posts as $post)
            <div class="col-md-6">
              <x-blog-card :post="$post" />
            </div>
          @empty
            <div class="col-12">
              <div class="glass-card text-center py-5 border rounded">
                <i class="fa-solid fa-folder-open fa-3x mb-3 text-muted"></i>
                <h4 class="h5">No Articles Found</h4>
                <p class="text-muted">No journal articles match your query or filters at the moment.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-gold mt-3">Reset All Filters</a>
              </div>
            </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
          <div class="d-flex justify-content-center mt-5">
            {{ $posts->links('pagination::bootstrap-5') }}
          </div>
        @endif
      </div>

      <!-- Sidebar Widgets -->
      <div class="col-lg-4">
        
        <!-- Search Widget -->
        <div class="card border p-4 shadow-sm mb-4">
          <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Search Articles</h4>
          <form method="GET" action="{{ route('blog.index') }}">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('tag')) <input type="hidden" name="tag" value="{{ request('tag') }}"> @endif
            @if(request('archive')) <input type="hidden" name="archive" value="{{ request('archive') }}"> @endif
            <div class="input-group">
              <input type="text" name="search" class="form-control border-end-0" placeholder="Type keywords..." value="{{ request('search') }}">
              <button class="btn btn-outline-secondary border-start-0" type="submit"><i class="fa-solid fa-magnifying-glass text-muted"></i></button>
            </div>
          </form>
        </div>

        <!-- Categories Widget -->
        <div class="card border p-4 shadow-sm mb-4">
          <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Categories</h4>
          <ul class="list-group list-group-flush">
            @foreach($sidebarCategories as $category)
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                <a href="{{ route('blog.index', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                   class="text-decoration-none text-white-50 {{ request('category') === $category->slug ? 'fw-bold text-warning' : '' }}">
                  {{ $category->name }}
                </a>
                <span class="badge bg-secondary rounded-pill small">{{ $category->posts_count }}</span>
              </li>
            @endforeach
          </ul>
        </div>

        <!-- Featured Articles Widget -->
        @if($featuredPosts->isNotEmpty())
          <div class="card border p-4 shadow-sm mb-4">
            <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Featured Articles</h4>
            <div class="d-flex flex-column gap-3">
              @foreach($featuredPosts as $featured)
                <div class="d-flex gap-3 align-items-center">
                  @if($featured->featuredImage)
                    <img src="{{ image_url($featured->featuredImage) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="Featured cover">
                  @endif
                  <div>
                    <h6 class="small mb-1"><a href="{{ route('blog.show', $featured) }}" class="text-white text-decoration-none fw-semibold">{{ $featured->title }}</a></h6>
                    <span class="small text-muted" style="font-size: 0.75rem;">{{ $featured->published_at ? $featured->published_at->format('M d, Y') : 'Recent' }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Tag Cloud Widget -->
        @if($sidebarTags->isNotEmpty())
          <div class="card border p-4 shadow-sm mb-4">
            <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Popular Tags</h4>
            <div class="d-flex flex-wrap gap-2">
              @foreach($sidebarTags as $tag)
                <a href="{{ route('blog.index', array_merge(request()->query(), ['tag' => $tag->slug])) }}" 
                   class="badge bg-light text-dark border text-decoration-none px-2.5 py-1.5 small {{ request('tag') === $tag->slug ? 'bg-gold border-gold text-white' : '' }}">
                  #{{ $tag->name }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Archives Widget -->
        @if($sidebarArchives->isNotEmpty())
          <div class="card border p-4 shadow-sm mb-4">
            <h4 class="h6 text-uppercase fw-bold text-muted mb-3">Archives</h4>
            <ul class="list-group list-group-flush">
              @foreach($sidebarArchives as $archive)
                @php($archiveVal = sprintf('%04d-%02d', $archive->year, $archive->month))
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                  <a href="{{ route('blog.index', array_merge(request()->query(), ['archive' => $archiveVal])) }}" 
                     class="text-decoration-none text-white-50 {{ request('archive') === $archiveVal ? 'fw-bold text-warning' : '' }}">
                    {{ DateTime::createFromFormat('!m', $archive->month)->format('F') }} {{ $archive->year }}
                  </a>
                  <span class="badge bg-secondary rounded-pill small">{{ $archive->count }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        @endif

      </div>
    </div>
  </div>
</section>
@endsection
