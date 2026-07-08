<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3" aria-label="Primary navigation">
  <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
    @if($headerLogo->desktop_logo && $headerLogo->desktopLogo)
      <img src="{{ image_url($headerLogo->desktopLogo) }}" alt="{{ $siteSetting->site_name ?? 'Fancy Decorators' }}" style="max-height: 40px;" />
    @elseif($siteSetting->logo)
      <img src="{{ $siteSetting->logo_url }}" alt="{{ $siteSetting->site_name ?? 'Fancy Decorators' }}" style="max-height: 40px;" />
    @else
      <div class="brand-mark">FD</div>
      <div class="brand-text ms-3">
        <span class="brand-title">{{ $siteSetting->site_name ?? 'Fancy Decorators' }}</span>
        <p>{{ $siteSetting->tagline ?? 'Luxury Construction' }}</p>
      </div>
    @endif
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navMenu">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#about' : '/#about' }}">About</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Services</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">Projects</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('careers.*') ? 'active' : '' }}" href="{{ route('careers.index') }}">Careers</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}">Testimonials</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
      <li class="nav-item ms-3">
        <button id="themeToggle" type="button" class="btn btn-outline-light btn-sm" aria-label="Toggle color theme"><i class="fa-solid fa-moon"></i></button>
      </li>
    </ul>
  </div>
</nav>
