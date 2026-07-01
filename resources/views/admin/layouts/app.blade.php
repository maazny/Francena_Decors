<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Panel') | Fancy Decorators</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
  <style>
    .admin-sidebar {
      min-height: 100vh;
      border-right: 1px solid rgba(255, 255, 255, .08);
    }

    .admin-sidebar .nav-link {
      color: rgba(255, 255, 255, .85);
    }

    .admin-sidebar .nav-link.active,
    .admin-sidebar .nav-link:hover {
      color: #ffffff;
      background-color: rgba(255, 255, 255, .08);
    }

    .admin-sidebar .sidebar-header {
      font-size: 1rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      opacity: .75;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container-fluid">
    <div class="row g-0">
      <aside class="col-auto admin-sidebar bg-dark text-white p-3">
        <div class="mb-5">
          <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center gap-2">
            <i class="fa-solid fa-hard-hat fa-lg"></i>
            <span class="fs-5 fw-semibold">Admin Panel</span>
          </a>
        </div>
        <div class="mb-4 sidebar-header">Settings</div>
        <div class="nav nav-pills flex-column gap-2">
          @if(Route::has('admin.theme.settings.edit'))
            <a href="{{ route('admin.theme.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.theme.settings.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-paintbrush fa-fw me-2"></i>
              Theme Settings
            </a>
          @endif
          <a href="{{ route('admin.site-settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.site-settings.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-gear me-2"></i>
            Site Settings
          </a>
          <a href="{{ route('admin.media.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.media.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-photo-film fa-fw me-2"></i>
            Media Library
          </a>
          <a href="{{ route('admin.project-categories.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.project-categories.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-folder-tree fa-fw me-2"></i>
            Project Categories
          </a>
          <a href="{{ route('admin.projects.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.projects.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-diagram-project fa-fw me-2"></i>
            Projects
          </a>
          <div class="mt-4 sidebar-header">Appearance</div>
          <a href="{{ route('admin.header.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.header.settings.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-header fa-fw me-2"></i>
            Header
          </a>
          <a href="{{ route('admin.footer.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.footer.settings.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-window-maximize fa-fw me-2"></i>
            Footer
          </a>
          <a href="{{ route('admin.hero-sliders.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.hero-sliders.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-images fa-fw me-2"></i>
            Hero Slider
          </a>
          <a href="{{ route('admin.about-sections.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.about-sections.*') || Route::is('admin.company-values.*') || Route::is('admin.company-timelines.*') || Route::is('admin.why-choose-us.*') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-circle-info fa-fw me-2"></i>
            About CMS
          </a>
          <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : 'text-white' }}">
            <i class="fa-solid fa-chart-line me-2"></i>
            Dashboard
          </a>
        </div>
      </aside>
      <main class="col py-4 px-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h1 class="h4 mb-1">@yield('page-title')</h1>
            <p class="text-muted mb-0">@yield('page-description')</p>
          </div>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">
              <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
              Logout
            </button>
          </form>
        </div>

        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
