@php
  $selectedDesktop = $heroSlider->desktopImage;
  $selectedMobile = $heroSlider->mobileImage;
  $selectedVideo = $heroSlider->backgroundVideo;
@endphp

<div class="card shadow-sm">
  <div class="card-body p-4">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="hero-slider-form" method="POST" action="{{ $action }}">
      @csrf
      @if($method !== 'POST')
        @method($method)
      @endif

      <div class="row g-4">
        <div class="col-xl-7">
          <div class="card border-light shadow-sm p-4 mb-4">
            <h2 class="h6 mb-3">Content</h2>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label" for="title">Title</label>
                <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $heroSlider->title) }}" required maxlength="191" data-preview-field="title">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="subtitle">Subtitle</label>
                <input id="subtitle" name="subtitle" type="text" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $heroSlider->subtitle) }}" maxlength="191" data-preview-field="subtitle">
                @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="badge_text">Badge Text</label>
                <input id="badge_text" name="badge_text" type="text" class="form-control @error('badge_text') is-invalid @enderror" value="{{ old('badge_text', $heroSlider->badge_text) }}" maxlength="100" data-preview-field="badge">
                @error('badge_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4" maxlength="1000" data-preview-field="description">{{ old('description', $heroSlider->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h2 class="h6 mb-3">Media Library</h2>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Desktop Image</label>
                <input id="desktop_image_id" name="desktop_image_id" type="hidden" value="{{ old('desktop_image_id', $heroSlider->desktop_image_id) }}">
                <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#desktopImageModal">Select Image</button>
                @error('desktop_image_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <img id="desktop_image_id_preview" src="{{ $selectedDesktop ? thumbnail_url($selectedDesktop) : '' }}" alt="Desktop image preview" class="img-fluid rounded border mt-3 {{ $selectedDesktop ? '' : 'd-none' }}" style="height: 110px; width: 100%; object-fit: cover;">
              </div>
              <div class="col-md-4">
                <label class="form-label">Mobile Image</label>
                <input id="mobile_image_id" name="mobile_image_id" type="hidden" value="{{ old('mobile_image_id', $heroSlider->mobile_image_id) }}">
                <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#mobileImageModal">Select Image</button>
                @error('mobile_image_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <img id="mobile_image_id_preview" src="{{ $selectedMobile ? thumbnail_url($selectedMobile) : '' }}" alt="Mobile image preview" class="img-fluid rounded border mt-3 {{ $selectedMobile ? '' : 'd-none' }}" style="height: 110px; width: 100%; object-fit: cover;">
              </div>
              <div class="col-md-4">
                <label class="form-label">Background Video</label>
                <input id="background_video_id" name="background_video_id" type="hidden" value="{{ old('background_video_id', $heroSlider->background_video_id) }}">
                <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#backgroundVideoModal">Select Video</button>
                @error('background_video_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <div id="background_video_id_preview" class="small text-muted mt-3">{{ $selectedVideo ? $selectedVideo->original_name : 'No video selected' }}</div>
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h2 class="h6 mb-3">Buttons</h2>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="button_one_text">Button One Text</label>
                <input id="button_one_text" name="button_one_text" type="text" class="form-control @error('button_one_text') is-invalid @enderror" value="{{ old('button_one_text', $heroSlider->button_one_text) }}" maxlength="100">
                @error('button_one_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="button_one_url">Button One URL</label>
                <input id="button_one_url" name="button_one_url" type="text" class="form-control @error('button_one_url') is-invalid @enderror" value="{{ old('button_one_url', $heroSlider->button_one_url) }}" maxlength="500">
                @error('button_one_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="button_one_target">Button One Target</label>
                <select id="button_one_target" name="button_one_target" class="form-select">
                  <option value="_self" {{ old('button_one_target', $heroSlider->button_one_target) === '_self' ? 'selected' : '' }}>Same Window</option>
                  <option value="_blank" {{ old('button_one_target', $heroSlider->button_one_target) === '_blank' ? 'selected' : '' }}>New Window</option>
                </select>
              </div>
              <div class="col-md-6"></div>
              <div class="col-md-6">
                <label class="form-label" for="button_two_text">Button Two Text</label>
                <input id="button_two_text" name="button_two_text" type="text" class="form-control @error('button_two_text') is-invalid @enderror" value="{{ old('button_two_text', $heroSlider->button_two_text) }}" maxlength="100">
                @error('button_two_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="button_two_url">Button Two URL</label>
                <input id="button_two_url" name="button_two_url" type="text" class="form-control @error('button_two_url') is-invalid @enderror" value="{{ old('button_two_url', $heroSlider->button_two_url) }}" maxlength="500">
                @error('button_two_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="button_two_target">Button Two Target</label>
                <select id="button_two_target" name="button_two_target" class="form-select">
                  <option value="_self" {{ old('button_two_target', $heroSlider->button_two_target) === '_self' ? 'selected' : '' }}>Same Window</option>
                  <option value="_blank" {{ old('button_two_target', $heroSlider->button_two_target) === '_blank' ? 'selected' : '' }}>New Window</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-5">
          <div class="card border-light shadow-sm p-4 mb-4">
            <h2 class="h6 mb-3">Design & Behavior</h2>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="overlay_color">Overlay Color</label>
                <input id="overlay_color" name="overlay_color" type="text" class="form-control form-control-color @error('overlay_color') is-invalid @enderror" value="{{ old('overlay_color', $heroSlider->overlay_color) }}" maxlength="20" data-preview-color="overlay">
                @error('overlay_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="badge_color">Badge Color</label>
                <input id="badge_color" name="badge_color" type="text" class="form-control form-control-color @error('badge_color') is-invalid @enderror" value="{{ old('badge_color', $heroSlider->badge_color) }}" maxlength="20" data-preview-color="badge">
                @error('badge_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <label class="form-label" for="overlay_opacity">Overlay Opacity</label>
                <input id="overlay_opacity" name="overlay_opacity" type="range" class="form-range" value="{{ old('overlay_opacity', $heroSlider->overlay_opacity) }}" min="0" max="100" data-preview-opacity>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="text_alignment">Text Alignment</label>
                <select id="text_alignment" name="text_alignment" class="form-select">
                  @foreach(['start' => 'Left', 'center' => 'Center', 'end' => 'Right'] as $value => $label)
                    <option value="{{ $value }}" {{ old('text_alignment', $heroSlider->text_alignment) === $value ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="content_position">Content Position</label>
                <select id="content_position" name="content_position" class="form-select">
                  @foreach(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'] as $value => $label)
                    <option value="{{ $value }}" {{ old('content_position', $heroSlider->content_position) === $value ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="animation_type">Animation Type</label>
                <select id="animation_type" name="animation_type" class="form-select">
                  @foreach(['fade-up' => 'Fade Up', 'fade-down' => 'Fade Down', 'zoom-in' => 'Zoom In', 'slide-left' => 'Slide Left', 'slide-right' => 'Slide Right'] as $value => $label)
                    <option value="{{ $value }}" {{ old('animation_type', $heroSlider->animation_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="animation_duration">Animation Speed (ms)</label>
                <input id="animation_duration" name="animation_duration" type="number" class="form-control @error('animation_duration') is-invalid @enderror" value="{{ old('animation_duration', $heroSlider->animation_duration) }}" min="100" max="5000">
                @error('animation_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="display_order">Display Order</label>
                <input id="display_order" name="display_order" type="number" class="form-control @error('display_order') is-invalid @enderror" value="{{ old('display_order', $heroSlider->display_order) }}" min="0" max="9999">
                @error('display_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <div class="form-check form-switch">
                  <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $heroSlider->status) ? 'checked' : '' }}>
                  <label class="form-check-label" for="status">Active</label>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Animation</label>
                <div class="form-check form-switch">
                  <input id="enable_animation" name="enable_animation" type="checkbox" class="form-check-input" value="1" {{ old('enable_animation', $heroSlider->enable_animation) ? 'checked' : '' }}>
                  <label class="form-check-label" for="enable_animation">Enabled</label>
                </div>
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h2 class="h6 mb-3">Scheduling</h2>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="start_date">Start Date</label>
                <input id="start_date" name="start_date" type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $heroSlider->start_date?->format('Y-m-d\TH:i')) }}">
                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="end_date">End Date</label>
                <input id="end_date" name="end_date" type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $heroSlider->end_date?->format('Y-m-d\TH:i')) }}">
                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4">
            <h2 class="h6 mb-3">Live Preview</h2>
            <ul class="nav nav-pills mb-3">
              <li class="nav-item"><button type="button" class="nav-link active" data-preview-size="desktop">Desktop</button></li>
              <li class="nav-item"><button type="button" class="nav-link" data-preview-size="tablet">Tablet</button></li>
              <li class="nav-item"><button type="button" class="nav-link" data-preview-size="mobile">Mobile</button></li>
            </ul>
            <div class="hero-admin-preview-frame" id="hero-form-preview" style="background-image: linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)), url('{{ $selectedDesktop ? image_url($selectedDesktop) : '' }}');">
              <div>
                <span class="badge mb-3" id="preview-badge" style="background: {{ old('badge_color', $heroSlider->badge_color ?: '#d4af5f') }};">{{ old('badge_text', $heroSlider->badge_text) ?: 'Badge' }}</span>
                <p class="mb-2" id="preview-subtitle">{{ old('subtitle', $heroSlider->subtitle) ?: 'Subtitle' }}</p>
                <h3 id="preview-title">{{ old('title', $heroSlider->title) ?: 'Hero title preview' }}</h3>
                <p id="preview-description">{{ old('description', $heroSlider->description) ?: 'Hero description preview.' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.hero-sliders.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

@include('admin.hero-sliders._media-modal', ['modalId' => 'desktopImageModal', 'targetInput' => 'desktop_image_id', 'mediaItems' => $imageOptions, 'title' => 'Select Desktop Image'])
@include('admin.hero-sliders._media-modal', ['modalId' => 'mobileImageModal', 'targetInput' => 'mobile_image_id', 'mediaItems' => $imageOptions, 'title' => 'Select Mobile Image'])
@include('admin.hero-sliders._media-modal', ['modalId' => 'backgroundVideoModal', 'targetInput' => 'background_video_id', 'mediaItems' => $videoOptions, 'title' => 'Select Background Video', 'isVideo' => true])

<script>
  const previewFrame = document.getElementById('hero-form-preview');

  document.querySelectorAll('[data-preview-field]').forEach((field) => {
    field.addEventListener('input', () => {
      const target = document.getElementById(`preview-${field.dataset.previewField}`);
      if (target) {
        target.textContent = field.value || target.textContent;
      }
    });
  });

  function hexToRgba(hex, opacity) {
    const clean = /^#[0-9A-Fa-f]{6}$/.test(hex) ? hex.substring(1) : '000000';
    const red = parseInt(clean.substring(0, 2), 16);
    const green = parseInt(clean.substring(2, 4), 16);
    const blue = parseInt(clean.substring(4, 6), 16);
    return `rgba(${red}, ${green}, ${blue}, ${opacity / 100})`;
  }

  function updateOverlayPreview() {
    const color = document.getElementById('overlay_color').value || '#000000';
    const opacity = document.getElementById('overlay_opacity').value || 65;
    const image = previewFrame.dataset.image || '{{ $selectedDesktop ? image_url($selectedDesktop) : '' }}';
    previewFrame.style.backgroundImage = `linear-gradient(${hexToRgba(color, opacity)}, ${hexToRgba(color, opacity)}), url('${image}')`;
  }

  document.getElementById('overlay_color')?.addEventListener('input', updateOverlayPreview);
  document.getElementById('overlay_opacity')?.addEventListener('input', updateOverlayPreview);
  document.getElementById('badge_color')?.addEventListener('input', (event) => {
    document.getElementById('preview-badge').style.background = event.target.value || '#d4af5f';
  });

  document.querySelectorAll('[data-preview-size]').forEach((button) => {
    button.addEventListener('click', () => {
      document.querySelectorAll('[data-preview-size]').forEach((item) => item.classList.remove('active'));
      button.classList.add('active');
      previewFrame.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');
      previewFrame.classList.add(`preview-${button.dataset.previewSize}`);
    });
  });
</script>
