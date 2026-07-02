<nav class="navbar navbar-expand-lg navbar-dark px-4 py-3" aria-label="Primary navigation">
  <a class="navbar-brand d-flex align-items-center" href="#home">
    <div class="brand-mark">FD</div>
    <div class="brand-text ms-3">
      <span class="brand-title">Fancy Decorators</span>
      <p>Luxury Construction</p>
    </div>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navMenu">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ request()->is('/') ? '#home' : '/#home' }}">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#about' : '/#about' }}">About</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#services' : '/#services' }}">Services</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#projects' : '/#projects' }}">Projects</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}">Blog</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#testimonials' : '/#testimonials' }}">Testimonials</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ request()->is('/') ? '#contact' : '/#contact' }}">Contact</a></li>
      <li class="nav-item ms-3">
        <button id="themeToggle" type="button" class="btn btn-outline-light btn-sm" aria-label="Toggle color theme"><i class="fa-solid fa-moon"></i></button>
      </li>
    </ul>
  </div>
</nav>
