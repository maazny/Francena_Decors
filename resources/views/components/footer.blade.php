@php
  $footer = footer_setting();
  $theme = theme_setting();
  $isActive = $footer->exists && $footer->status;
  $columns = $footer->relationLoaded('columns') ? $footer->columns->where('status', true) : collect();
  $socialLinks = $footer->relationLoaded('socialLinks') ? $footer->socialLinks->where('status', true) : collect();
  $businessHours = $footer->relationLoaded('businessHours') ? $footer->businessHours->where('status', true) : collect();
  $widgets = $footer->relationLoaded('widgets') ? $footer->widgets->where('status', true) : collect();
  $logoUrl = $footer->relationLoaded('logo') ? image_url($footer->logo) : null;
  $backgroundUrl = $footer->relationLoaded('background') ? image_url($footer->background) : null;
  $backgroundColor = $footer->background_color ?: $theme->footer_background;
  $textColor = $footer->text_color ?: $theme->footer_text_color;
  $headingColor = $footer->heading_color ?: $footer->text_color ?: $theme->footer_text_color;
  $linkColor = $footer->link_color ?: $footer->text_color ?: $theme->footer_text_color;
@endphp

@if($isActive)
  <footer
    class="footer-section footer-cms footer-layout-{{ $footer->layout ?: 'four_columns' }}"
    style="
      --footer-cms-bg: {{ $backgroundColor ?: '#111111' }};
      --footer-cms-text: {{ $textColor ?: '#ffffff' }};
      --footer-cms-heading: {{ $headingColor ?: '#ffffff' }};
      --footer-cms-link: {{ $linkColor ?: '#ffffff' }};
      --footer-cms-bottom-bg: {{ $footer->bottom_background_color ?: 'rgba(0, 0, 0, 0.18)' }};
      @if($backgroundUrl) background-image: linear-gradient(rgba(0,0,0,.72), rgba(0,0,0,.78)), url('{{ $backgroundUrl }}'); @endif
    "
  >
    <div class="container py-5">
      @if($footer->newsletter_enabled)
        <div class="footer-newsletter mb-5">
          <div>
            @if($footer->newsletter_title)
              <h2 class="h4 mb-2">{{ $footer->newsletter_title }}</h2>
            @endif
            @if($footer->newsletter_description)
              <p class="mb-0">{{ $footer->newsletter_description }}</p>
            @endif
          </div>
          <form class="footer-newsletter-form" action="#" method="GET">
            <label class="visually-hidden" for="footer_newsletter_email">Email address</label>
            <input id="footer_newsletter_email" type="email" class="form-control" placeholder="{{ $footer->newsletter_placeholder ?: 'Email address' }}">
            <button type="submit" class="btn btn-primary">{{ $footer->newsletter_button_text ?: 'Subscribe' }}</button>
          </form>
        </div>
      @endif

      <div class="row gy-4">
        <div class="col-md-6 col-lg-3">
          @if($footer->show_logo && $logoUrl)
            <a href="{{ url('/') }}" class="footer-logo d-inline-flex mb-3">
              <img src="{{ $logoUrl }}" alt="{{ config('app.name') }} footer logo">
            </a>
          @endif

          @if($footer->show_description && $footer->company_description)
            <p class="footer-description mb-3">{{ $footer->company_description }}</p>
          @endif

          @if($footer->show_social_links && $socialLinks->isNotEmpty())
            <div class="social-icons d-flex flex-wrap gap-3 mt-3">
              @foreach($socialLinks as $socialLink)
                <a href="{{ $socialLink->url }}" target="_blank" rel="noopener" aria-label="{{ $socialLink->platform }}">
                  <i class="{{ $socialLink->icon ?: 'fa-solid fa-link' }}"></i>
                </a>
              @endforeach
            </div>
          @endif
        </div>

        @if($footer->show_columns)
          @foreach($columns as $column)
            <div class="col-md-6 col-lg-3">
              <h5>{{ $column->title }}</h5>
              <ul class="footer-list">
                @foreach($column->links->where('status', true) as $link)
                  <li>
                    <a href="{{ $link->url ?: '#' }}" target="{{ $link->target }}" @if($link->target === '_blank') rel="noopener" @endif>
                      @if($link->icon)
                        <i class="{{ $link->icon }} me-2"></i>
                      @endif
                      {{ $link->label }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          @endforeach
        @endif

        @if($footer->show_contact)
          <div class="col-md-6 col-lg-3">
            @if($footer->contact_heading)
              <h5>{{ $footer->contact_heading }}</h5>
            @endif
            <div class="footer-list">
              @if($footer->contact_address)
                <p><i class="fa-solid fa-location-dot me-2"></i>{{ $footer->contact_address }}</p>
              @endif
              @if($footer->contact_phone)
                <p><i class="fa-solid fa-phone me-2"></i><a href="tel:{{ preg_replace('/\s+/', '', $footer->contact_phone) }}">{{ $footer->contact_phone }}</a></p>
              @endif
              @if($footer->contact_email)
                <p><i class="fa-solid fa-envelope me-2"></i><a href="mailto:{{ $footer->contact_email }}">{{ $footer->contact_email }}</a></p>
              @endif
            </div>
          </div>
        @endif

        @if($footer->show_business_hours && $businessHours->isNotEmpty())
          <div class="col-md-6 col-lg-3">
            @if($footer->business_hours_heading)
              <h5>{{ $footer->business_hours_heading }}</h5>
            @endif
            <ul class="footer-list">
              @foreach($businessHours as $businessHour)
                <li class="d-flex justify-content-between gap-3">
                  <span>{{ $businessHour->day_label }}</span>
                  <span>{{ $businessHour->time_label }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      @if($footer->show_widgets && $widgets->isNotEmpty())
        <div class="footer-widgets row gy-3 mt-4">
          @foreach($widgets as $widget)
            <div class="col-md-6 col-lg-4">
              <div class="footer-widget">
                @if($widget->icon)
                  <i class="{{ $widget->icon }}"></i>
                @endif
                <div>
                  <h5>{{ $widget->title }}</h5>
                  @if($widget->content)
                    <p class="mb-0">{{ $widget->content }}</p>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    @if($footer->bottom_bar_enabled)
      <div class="footer-bottom-bar py-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
          @if($footer->copyright_text)
            <div class="footer-copy">{{ $footer->copyright_text }}</div>
          @endif
          @if($footer->bottom_bar_text)
            <div class="footer-copy">{{ $footer->bottom_bar_text }}</div>
          @endif
        </div>
      </div>
    @endif
  </footer>
@endif
