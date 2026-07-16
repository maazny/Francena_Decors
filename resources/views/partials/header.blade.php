@if(($headerSettings->topbar_enabled ?? false) && ($headerTopbar->status ?? false))
  <div class="header-topbar bg-dark py-2 px-4 text-white border-bottom border-secondary d-none d-lg-block">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="topbar-left small">
        @if($headerTopbar->left_text)
          <span class="me-3">{{ $headerTopbar->left_text }}</span>
        @endif
        @if($headerTopbar->opening_hours)
          <span><i class="fa-solid fa-clock me-1 text-primary" style="color: var(--button-background, #d4af5f) !important;"></i> {{ $headerTopbar->opening_hours }}</span>
        @endif
      </div>
      <div class="topbar-right small d-flex gap-3 align-items-center">
        @if($headerTopbar->email)
          <a href="mailto:{{ $headerTopbar->email }}" class="text-white text-decoration-none"><i class="fa-solid fa-envelope me-1 text-primary" style="color: var(--button-background, #d4af5f) !important;"></i> {{ $headerTopbar->email }}</a>
        @endif
        @if($headerTopbar->phone)
          <a href="tel:{{ $headerTopbar->phone }}" class="text-white text-decoration-none"><i class="fa-solid fa-phone me-1 text-primary" style="color: var(--button-background, #d4af5f) !important;"></i> {{ $headerTopbar->phone }}</a>
        @endif
        <div class="social-icons d-flex gap-2 ms-2">
          @if($headerTopbar->facebook)
            <a href="{{ $headerTopbar->facebook }}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a>
          @endif
          @if($headerTopbar->instagram)
            <a href="{{ $headerTopbar->instagram }}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
          @endif
          @if($headerTopbar->linkedin)
            <a href="{{ $headerTopbar->linkedin }}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-linkedin-in"></i></a>
          @endif
          @if($headerTopbar->youtube)
            <a href="{{ $headerTopbar->youtube }}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i></a>
          @endif
          @if($headerTopbar->twitter)
            <a href="{{ $headerTopbar->twitter }}" class="text-white" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
          @endif
        </div>
      </div>
    </div>
  </div>
@endif

<header class="header-nav">
  @include('partials.navbar')
</header>

