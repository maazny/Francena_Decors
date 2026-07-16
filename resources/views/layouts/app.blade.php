<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="@yield('meta_description', $seo->meta_description ?? '')" />
  <meta name="keywords" content="@yield('meta_keywords', $seo->meta_keywords ?? '')" />
  <meta name="robots" content="@yield('robots', $seo->robots ?? 'index, follow')" />
  <title>@yield('title', $seo->title ?? ($siteSetting->site_name ?? 'Francena Decors') . ' | Luxury Construction')</title>
  <meta name="theme-color" content="{{ $seo->theme_color ?? '#d4af5f' }}" />
  <meta property="og:title" content="@yield('og_title', $seo->og_title ?? '')" />
  <meta property="og:description" content="@yield('og_description', $seo->og_description ?? '')" />
  <meta property="og:type" content="@yield('og_type', $seo->og_type ?? 'website')" />
  <meta property="og:url" content="@yield('og_url', $seo->canonical_url ?? request()->url())" />
  <meta property="og:image" content="@yield('og_image', $seo->og_image ?? '')" />
  <meta property="og:site_name" content="{{ $seo->site_name ?? ($siteSetting->site_name ?? 'Francena Decors') }}" />
  <meta name="twitter:card" content="{{ $seo->twitter_card ?? 'summary_large_image' }}" />
  <meta name="twitter:title" content="@yield('twitter_title', $seo->og_title ?? '')" />
  <meta name="twitter:description" content="@yield('twitter_description', $seo->og_description ?? '')" />
  <meta name="twitter:image" content="@yield('twitter_image', $seo->og_image ?? '')" />
  <meta name="twitter:site" content="{{ '@' . str_replace(' ', '', $seo->site_name ?? ($siteSetting->site_name ?? 'Francena Decors')) }}" />
  <link rel="canonical" href="@yield('canonical', $seo->canonical_url ?? request()->url())" />
  @yield('schema')
  {!! $seo->structured_data ?? '' !!}
  {!! $seo->custom_head_scripts ?? '' !!}
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@@type": "HomeAndConstructionBusiness",
    "name": "{{ $siteSetting->company_name ?? 'Francena Decors' }}",
    "url": "{{ url('/') }}",
    "logo": "{{ $siteSetting->logo_url ?? asset('logo.png') }}",
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
      "https://www.facebook.com/FrancenaDecors",
      "https://www.instagram.com/FrancenaDecors",
      "https://www.linkedin.com/company/FrancenaDecors"
    ]
  }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Noto+Sans+Tamil:wght@400;500;600;700&display=swap" rel="stylesheet" />
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

    /* Header nav handled by glassmorphism CSS — do not override here */

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

    .dark-theme {
      --background-color: #0C0A0F !important;
      --surface-color: #141218 !important;
      --text-color: #EEEEEE !important;
      --heading-color: #FFFFFF !important;
      --card-background: #1e1e24 !important;
      --card-border-color: rgba(255, 255, 255, 0.08) !important;
      --border: rgba(255, 255, 255, 0.08) !important;
      --white: #EEEEEE !important;
    }
  </style>
  @stack('head')
</head>
<body>
  <div id="pageProgressBar" class="page-progress-bar"></div>
  <div id="preloader">
    <div class="spinner">
      <div></div><div></div><div></div><div></div>
    </div>
  </div>

  @include('partials.header')

  @yield('content')

  <x-footer />

  <!-- Premium Floating UI Elements -->
  <div id="customCursor" class="custom-cursor"></div>
  <div id="customCursorDot" class="custom-cursor-dot"></div>
  <a href="{{ route('contact.index') }}" class="sticky-quote-btn" aria-label="Get a Quote"><i class="fa-solid fa-file-signature me-2"></i><span>Get a Quote</span></a>
  <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer" class="floating-whatsapp" aria-label="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
  <button id="backToTop" class="back-to-top" type="button" aria-label="Scroll back to top"><i class="fa-solid fa-chevron-up" aria-hidden="true"></i></button>
  <div id="scrollProgress" class="scroll-progress" aria-hidden="true"></div>

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
