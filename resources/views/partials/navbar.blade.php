<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3" aria-label="Primary navigation" style="{{ ($headerSettings->transparent_header ?? false) ? 'background-color: transparent !important;' : '' }}">
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
  <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navMenu">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#about' : '/#about' }}">About</a></li>
      
      <!-- Services Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Services
        </a>
        <ul class="dropdown-menu bg-dark border-secondary" aria-labelledby="servicesDropdown">
          <li><a class="dropdown-item text-white" href="{{ route('services.index') }}">All Services</a></li>
          <li><hr class="dropdown-divider border-secondary"></li>
          @foreach(\App\Models\ServiceCategory::active()->ordered()->get() as $cat)
            <li><a class="dropdown-item text-white" href="{{ route('services.category', $cat->slug) }}">{{ $cat->name }}</a></li>
          @endforeach
        </ul>
      </li>

      <!-- Projects Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}" id="projectsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Projects
        </a>
        <ul class="dropdown-menu bg-dark border-secondary" aria-labelledby="projectsDropdown">
          <li><a class="dropdown-item text-white" href="{{ route('projects.index') }}">All Projects</a></li>
          <li><hr class="dropdown-divider border-secondary"></li>
          @foreach(\App\Models\ProjectCategory::active()->ordered()->get() as $cat)
            <li><a class="dropdown-item text-white" href="{{ route('projects.category', $cat->slug) }}">{{ $cat->name }}</a></li>
          @endforeach
        </ul>
      </li>

      <li class="nav-item"><a class="nav-link {{ request()->is('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('careers.*') ? 'active' : '' }}" href="{{ route('careers.index') }}">Careers</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}">Testimonials</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
      
      <!-- Search Enabled Form -->
      @if($headerSettings->search_enabled ?? false)
        <li class="nav-item ms-3">
          <form class="d-flex" action="{{ route('blog.index') }}" method="GET" role="search">
            <input class="form-control form-control-sm me-2 search-bar-input" type="search" name="search" placeholder="Search blog..." aria-label="Search">
            <button class="btn btn-outline-light btn-sm" type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
        </li>
      @endif

      <!-- Call to Action button -->
      @if(($headerSettings->cta_button_enabled ?? false) && ($headerSettings->cta_button_text ?? false))
        <li class="nav-item ms-3">
          <a class="btn btn-gold btn-sm rounded-pill px-3" href="{{ $headerSettings->cta_button_url ?: '#' }}" target="{{ $headerSettings->cta_button_target ?: '_self' }}">
            {{ $headerSettings->cta_button_text }}
          </a>
        </li>
      @endif

      <li class="nav-item ms-3">
        <button id="themeToggle" type="button" class="btn btn-outline-light btn-sm" aria-label="Toggle color theme"><i class="fa-solid fa-moon"></i></button>
      </li>
    </ul>
  </div>
</nav>

<!-- Full Screen Mobile Menu Overlay -->
<div id="fullscreenMenu" class="fullscreen-menu-overlay d-none" role="dialog" aria-modal="true">
  <button type="button" class="close-menu-btn" aria-label="Close menu">&times;</button>
  <div class="fullscreen-menu-content d-flex flex-column align-items-center justify-content-center text-center">
    <ul class="list-unstyled mb-5">
      <li class="menu-item"><a href="{{ url('/') }}" class="menu-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
      <li class="menu-item"><a href="{{ request()->is('/') ? '#about' : '/#about' }}" class="menu-link">About</a></li>
      <li class="menu-item"><a href="{{ route('services.index') }}" class="menu-link {{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a></li>
      <li class="menu-item"><a href="{{ route('projects.index') }}" class="menu-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">Projects</a></li>
      <li class="menu-item"><a href="{{ route('gallery') }}" class="menu-link {{ request()->is('gallery') ? 'active' : '' }}">Gallery</a></li>
      <li class="menu-item"><a href="{{ route('blog.index') }}" class="menu-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
      <li class="menu-item"><a href="{{ route('careers.index') }}" class="menu-link {{ request()->routeIs('careers.*') ? 'active' : '' }}">Careers</a></li>
      <li class="menu-item"><a href="{{ route('contact.index') }}" class="menu-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">Contact</a></li>
    </ul>
    
    <div class="menu-socials d-flex gap-4 justify-content-center mt-3">
      <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
      <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
    </div>
  </div>
</div>

