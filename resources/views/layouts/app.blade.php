<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="@yield('meta_description', $seo->meta_description ?? '')" />
  <meta name="keywords" content="@yield('meta_keywords', $seo->meta_keywords ?? '')" />
  <meta name="robots" content="@yield('robots', $seo->robots ?? 'index, follow')" />
  <title>@yield('title', $seo->title ?? 'Fancy Decorators | Luxury Construction')</title>
  <meta name="theme-color" content="{{ $seo->theme_color ?? '#d4af5f' }}" />
  <meta property="og:title" content="@yield('og_title', $seo->og_title ?? '')" />
  <meta property="og:description" content="@yield('og_description', $seo->og_description ?? '')" />
  <meta property="og:type" content="@yield('og_type', $seo->og_type ?? 'website')" />
  <meta property="og:url" content="@yield('og_url', $seo->canonical_url ?? request()->url())" />
  <meta property="og:image" content="@yield('og_image', $seo->og_image ?? '')" />
  <meta property="og:site_name" content="{{ $seo->site_name ?? 'Fancy Decorators' }}" />
  <meta name="twitter:card" content="{{ $seo->twitter_card ?? 'summary_large_image' }}" />
  <meta name="twitter:title" content="@yield('twitter_title', $seo->og_title ?? '')" />
  <meta name="twitter:description" content="@yield('twitter_description', $seo->og_description ?? '')" />
  <meta name="twitter:image" content="@yield('twitter_image', $seo->og_image ?? '')" />
  <meta name="twitter:site" content="@{{ $seo->site_name ?? 'FancyDecorators' }}" />
  <link rel="canonical" href="@yield('canonical', $seo->canonical_url ?? request()->url())" />
  @yield('schema')
  {!! $seo->structured_data ?? '' !!}
  {!! $seo->custom_head_scripts ?? '' !!}
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "HomeAndConstructionBusiness",
    "name": "Fancy Decorators",
    "url": "https://www.fancydecorators.com/",
    "logo": "https://www.fancydecorators.com/logo.png",
    "image": "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80",
    "description": "Premium luxury construction, high-end renovations, and custom interior design services for residential and commercial clients.",
    "telephone": "+1 234 567 890",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "25 Royal Avenue",
      "addressLocality": "Downtown City",
      "addressRegion": "State",
      "postalCode": "12345",
      "addressCountry": "USA"
    },
    "areaServed": {
      "@@type": "City",
      "name": "Metropolitan Area"
    },
    "openingHoursSpecification": [
      {
        "@@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
        "opens": "08:00",
        "closes": "18:00"
      }
    ],
    "sameAs": [
      "https://www.facebook.com/FancyDecorators",
      "https://www.instagram.com/FancyDecorators",
      "https://www.linkedin.com/company/FancyDecorators"
    ]
  }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
  <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
  <style>
    :root {
      {!! theme_css_variables() !!}
    }

    body {
      font-family: var(--font-family, Inter, system-ui, sans-serif);
      font-size: var(--base-font-size, 16px);
      background-color: var(--background-color, #ffffff);
      color: var(--text-color, #222222);
    }

    .btn-primary {
      background-color: var(--button-background, #0d6efd) !important;
      border-color: var(--button-background, #0d6efd) !important;
      color: var(--button-text-color, #ffffff) !important;
    }

    .btn-primary:hover {
      background-color: var(--button-hover-background, #0b5ed7) !important;
      color: var(--button-hover-text, #ffffff) !important;
    }

    a {
      color: var(--link-color, #0d6efd);
    }

    a:hover {
      color: var(--link-hover-color, #0a58ca);
    }

    .navbar, .header-nav {
      background-color: var(--navbar-background, #000000) !important;
      color: var(--navbar-text-color, #ffffff) !important;
    }

    .footer-copy,
    .footer-list a,
    .footer-list li,
    .footer-list p {
      color: var(--footer-text-color, #ffffff) !important;
    }

    .footer-section {
      background-color: var(--footer-background, #111111) !important;
    }

    .glass-card,
    .card,
    .project-card,
    .testimonial-item,
    .process-card,
    .feature-box,
    .contact-form,
    .accordion-item {
      background-color: var(--card-background, rgba(255, 255, 255, 0.08)) !important;
      border-color: var(--card-border-color, rgba(255, 255, 255, 0.08)) !important;
      box-shadow: var(--box-shadow, 0 20px 50px rgba(0,0,0,.08));
      border-radius: var(--border-radius, 16px) !important;
    }

    input,
    textarea,
    select,
    .form-control {
      background-color: var(--input-background, #ffffff) !important;
      border-color: var(--input-border-color, #ced4da) !important;
    }

    .section-bg {
      background-color: var(--surface-color, #0a0a0a) !important;
    }
  </style>
  @stack('head')
</head>
<body>
  <div id="preloader">
    <div class="spinner">
      <div></div><div></div><div></div><div></div>
    </div>
  </div>

  @include('partials.header')

  @yield('content')

  <x-footer />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  @if(theme_setting()->custom_js)
    <script>
      {!! theme_setting()->safeCustomJs() !!}
    </script>
  @endif
  {!! $seo->custom_footer_scripts ?? '' !!}
  @stack('scripts')
</body>
</html>
