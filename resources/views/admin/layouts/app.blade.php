<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Admin Panel') | {{ $siteSetting->site_name ?? 'Francena Decors' }}</title>
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
<body class="bg-light admin-body">
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
            @can('view_theme_settings')
              <a href="{{ route('admin.theme.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.theme.settings.*') ? 'active' : 'text-white' }}">
                <i class="fa-solid fa-paintbrush fa-fw me-2"></i>
                Theme Settings
              </a>
            @endcan
          @endif
          @can('view_site_settings')
            <a href="{{ route('admin.site-settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.site-settings.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-gear me-2"></i>
              Site Settings
            </a>
          @endcan
          @can('view_media_library')
            <a href="{{ route('admin.media.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.media.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-photo-film fa-fw me-2"></i>
              Media Library
            </a>
          @endcan
          @can('view_projects')
            <a href="{{ route('admin.project-categories.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.project-categories.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-folder-tree fa-fw me-2"></i>
              Project Categories
            </a>
            <a href="{{ route('admin.projects.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.projects.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-diagram-project fa-fw me-2"></i>
              Projects
            </a>
          @endcan
          @can('view_clients')
            <a href="{{ route('admin.client-brands.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.client-brands.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-handshake fa-fw me-2"></i>
              Clients & Brands
            </a>
          @endcan

          @if(Gate::check('view_header') || Gate::check('view_footer') || Gate::check('view_hero') || Gate::check('view_services'))
            <div class="mt-4 sidebar-header">Appearance</div>
          @endif

          @can('view_header')
            <a href="{{ route('admin.header.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.header.settings.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-header fa-fw me-2"></i>
              Header
            </a>
          @endcan
          @can('view_footer')
            <a href="{{ route('admin.footer.settings.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.footer.settings.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-window-maximize fa-fw me-2"></i>
              Footer
            </a>
          @endcan
          @can('view_hero')
            <a href="{{ route('admin.hero-sliders.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.hero-sliders.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-images fa-fw me-2"></i>
              Hero Slider
            </a>
          @endcan
          @can('view_services')
            <a href="{{ route('admin.service-categories.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.service-categories.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-tags fa-fw me-2"></i>
              Service Categories
            </a>
            <a href="{{ route('admin.services.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.services.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-briefcase fa-fw me-2"></i>
              Services
            </a>
          @endcan

          @can('view_blog')
            <div class="mt-4 sidebar-header">Blog CMS</div>
            <a href="{{ route('admin.blog-categories.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.blog-categories.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-list fa-fw me-2"></i>
              Blog Categories
            </a>
            <a href="{{ route('admin.blog-tags.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.blog-tags.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-tags fa-fw me-2"></i>
              Blog Tags
            </a>
            <a href="{{ route('admin.blog-posts.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.blog-posts.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-file-pen fa-fw me-2"></i>
              Blog Posts
            </a>
          @endcan

          @can('view_careers')
            <div class="mt-4 sidebar-header">Careers CMS</div>
            <a href="{{ route('admin.careers.departments.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.careers.departments.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-building fa-fw me-2"></i>
              Job Departments
            </a>
            <a href="{{ route('admin.careers.categories.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.careers.categories.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-list-check fa-fw me-2"></i>
              Job Categories
            </a>
            <a href="{{ route('admin.careers.locations.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.careers.locations.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-location-dot fa-fw me-2"></i>
              Job Locations
            </a>
            <a href="{{ route('admin.careers.jobs.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.careers.jobs.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-briefcase fa-fw me-2"></i>
              Job Openings
            </a>
            <a href="{{ route('admin.careers.applications.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.careers.applications.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-user-tie fa-fw me-2"></i>
              Job Applications
            </a>
          @endcan

          @can('view_newsletter')
            <div class="mt-4 sidebar-header">Newsletter CMS</div>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.newsletter.campaigns.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-paper-plane fa-fw me-2"></i>
              Campaigns
            </a>
            <a href="{{ route('admin.newsletter.templates.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.newsletter.templates.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-envelope-open-text fa-fw me-2"></i>
              Templates
            </a>
            <a href="{{ route('admin.newsletter.groups.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.newsletter.groups.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-users-rectangle fa-fw me-2"></i>
              Groups / Segments
            </a>
            <a href="{{ route('admin.newsletter.subscribers.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.newsletter.subscribers.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-users fa-fw me-2"></i>
              Subscribers
            </a>
          @endcan

          @can('view_about')
            <a href="{{ route('admin.about-sections.edit') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.about-sections.*') || Route::is('admin.company-values.*') || Route::is('admin.company-timelines.*') || Route::is('admin.why-choose-us.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-circle-info fa-fw me-2"></i>
              About CMS
            </a>
          @endcan

          @can('view_roles')
            <div class="mt-4 sidebar-header">RBAC Security</div>
            <a href="{{ route('admin.rbac.dashboard') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.rbac.dashboard') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-shield-halved fa-fw me-2"></i>
              Control Center
            </a>
            <a href="{{ route('admin.roles.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.roles.*') && !Route::is('admin.rbac.dashboard') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-user-lock fa-fw me-2"></i>
              Role Tiers
            </a>
            <a href="{{ route('admin.users-roles.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.users-roles.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-user-shield fa-fw me-2"></i>
              Staff Assignments
            </a>
          @endcan

          @can('view_activity_logs')
            <div class="mt-4 sidebar-header">System</div>
            <a href="{{ route('admin.activity-logs.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.activity-logs.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-clock-rotate-left fa-fw me-2"></i>
              Activity Logs
            </a>
          @endcan

          @can('backup.view')
            <a href="{{ route('admin.backups.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.backups.*') || Route::is('admin.backup-schedules.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-database fa-fw me-2"></i>
              Backup Management
            </a>
          @endcan

          @can('analytics.view')
            <div class="mt-4 sidebar-header">Analytics & Reports</div>
            <a href="{{ route('admin.analytics.dashboard') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.analytics.dashboard') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-gauge fa-fw me-2"></i>
              Dashboard
            </a>
            <a href="{{ route('admin.reports.index') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.reports.*') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-file-invoice fa-fw me-2"></i>
              Reports
            </a>
            <a href="{{ route('admin.analytics.snapshots') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.analytics.snapshots') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-camera fa-fw me-2"></i>
              Snapshots
            </a>
            <a href="{{ route('admin.analytics.performance') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.analytics.performance') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-gauge-high fa-fw me-2"></i>
              Performance
            </a>
            <a href="{{ route('admin.analytics.health') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.analytics.health') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-heart-pulse fa-fw me-2"></i>
              System Health
            </a>
          @endcan

          @can('view_dashboard')
            <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ Route::is('admin.dashboard') ? 'active' : 'text-white' }}">
              <i class="fa-solid fa-chart-line me-2"></i>
              Dashboard
            </a>
          @endcan
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
  <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      
      document.querySelectorAll('.rich-editor').forEach((textarea) => {
        if (textarea._ckEditor) return;
        ClassicEditor.create(textarea).then(editor => { textarea._ckEditor = editor; }).catch(err => console.error(err));
      });

      document.querySelectorAll('input[type="hidden"][id$="_id"]').forEach((input) => {
        const preview = document.getElementById(input.id + '_preview');
        if (! preview) return;
        if (preview.nextElementSibling && preview.nextElementSibling.classList && preview.nextElementSibling.classList.contains('js-media-clear')) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-sm btn-outline-danger mt-2 js-media-clear';
        btn.textContent = 'Clear';
        btn.addEventListener('click', () => {
          input.value = '';
          if (preview.tagName === 'IMG') { preview.src = ''; preview.classList.add('d-none'); } else { preview.textContent = ''; }
        });
        preview.insertAdjacentElement('afterend', btn);
      });

      document.querySelectorAll('[data-ajax-submit]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
          event.preventDefault();
          const submitButton = form.querySelector('button[type="submit"]');
          const url = form.action;
          const method = form.method.toUpperCase() || 'POST';
          const formData = new FormData(form);
          const response = await fetch(url, {
            method,
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
            },
            body: formData,
          });

          if (! response.ok) {
            const text = await response.text();
            console.error('AJAX submit failed', response.status, text);
            return;
          }

          const json = await response.json();
          const target = document.getElementById(form.dataset.ajaxTarget);
          if (target && json.html) {
            target.innerHTML = json.html;
          }

          form.reset();
          form.querySelectorAll('img[id$="_preview"]').forEach((img) => {
            img.src = '';
            img.classList.add('d-none');
          });

          if (submitButton) {
            submitButton.blur();
          }
        });
      });

      document.querySelectorAll('[data-ajax-delete]').forEach((button) => {
        button.addEventListener('click', async (event) => {
          event.preventDefault();
          const url = button.dataset.action;
          const row = button.closest('[data-nested-item]');
          const response = await fetch(url, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
            },
          });
          if (! response.ok) {
            console.error('AJAX delete failed', response.status);
            return;
          }
          if (row) row.remove();
        });
      });
    });
  </script>
  @yield('scripts')
  @stack('scripts')
</body>
</html>
