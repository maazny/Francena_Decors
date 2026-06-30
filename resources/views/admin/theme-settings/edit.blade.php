@extends('admin.layouts.app')

@section('title', 'Theme Settings')
@section('page-title', 'Theme Settings')
@section('page-description', 'Customize the website appearance for colors, typography, layout, and interactions.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
      <div>
        <h2 class="h5 mb-1">Theme Settings</h2>
        <p class="text-muted mb-0">Customize the frontend theme settings from a single admin screen.</p>
      </div>
      <div>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('theme-settings-form').reset();">Reset</button>
      </div>
    </div>

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

    <form id="theme-settings-form" method="POST" action="{{ route('theme.settings.update') }}" novalidate>
      @csrf
      @method('PUT')

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Colors</h3>
            <div class="row g-3">
              @foreach([
                'primary_color' => 'Primary Color',
                'secondary_color' => 'Secondary Color',
                'accent_color' => 'Accent Color',
                'background_color' => 'Background',
                'surface_color' => 'Surface',
                'text_color' => 'Text',
                'heading_color' => 'Heading',
                'link_color' => 'Link',
                'link_hover_color' => 'Link Hover',
                'success_color' => 'Success',
                'warning_color' => 'Warning',
                'danger_color' => 'Danger',
                'info_color' => 'Info',
              ] as $field => $label)
                <div class="col-md-6">
                  <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                  <input id="{{ $field }}" name="{{ $field }}" type="text" class="form-control form-control-color @error($field) is-invalid @enderror" value="{{ old($field, $themeSetting->$field) }}" maxlength="20">
                  @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              @endforeach
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Buttons</h3>
            <div class="row g-3">
              @foreach([
                'button_background' => 'Button Background',
                'button_text_color' => 'Button Text',
                'button_hover_background' => 'Button Hover Background',
                'button_hover_text' => 'Button Hover Text',
              ] as $field => $label)
                <div class="col-md-6">
                  <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                  <input id="{{ $field }}" name="{{ $field }}" type="text" class="form-control form-control-color @error($field) is-invalid @enderror" value="{{ old($field, $themeSetting->$field) }}" maxlength="20">
                  @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              @endforeach
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Navigation</h3>
            <div class="row g-3">
              @foreach([
                'navbar_background' => 'Navbar Background',
                'navbar_text_color' => 'Navbar Text',
                'sidebar_background' => 'Sidebar Background',
                'sidebar_text_color' => 'Sidebar Text',
                'footer_background' => 'Footer Background',
                'footer_text_color' => 'Footer Text',
              ] as $field => $label)
                <div class="col-md-6">
                  <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                  <input id="{{ $field }}" name="{{ $field }}" type="text" class="form-control form-control-color @error($field) is-invalid @enderror" value="{{ old($field, $themeSetting->$field) }}" maxlength="20">
                  @error($field)
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              @endforeach
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Typography</h3>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="font_family">Font Family</label>
                <input id="font_family" name="font_family" type="text" class="form-control @error('font_family') is-invalid @enderror" value="{{ old('font_family', $themeSetting->font_family) }}" maxlength="100">
                @error('font_family')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="heading_font">Heading Font</label>
                <input id="heading_font" name="heading_font" type="text" class="form-control @error('heading_font') is-invalid @enderror" value="{{ old('heading_font', $themeSetting->heading_font) }}" maxlength="100">
                @error('heading_font')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="base_font_size">Base Font Size (px)</label>
                <input id="base_font_size" name="base_font_size" type="number" class="form-control @error('base_font_size') is-invalid @enderror" value="{{ old('base_font_size', $themeSetting->base_font_size) }}" min="10" max="32">
                @error('base_font_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="container_width">Container Width (px)</label>
                <input id="container_width" name="container_width" type="number" class="form-control @error('container_width') is-invalid @enderror" value="{{ old('container_width', $themeSetting->container_width) }}" min="800" max="2000">
                @error('container_width')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Style</h3>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="border_radius">Border Radius</label>
                <input id="border_radius" name="border_radius" type="text" class="form-control @error('border_radius') is-invalid @enderror" value="{{ old('border_radius', $themeSetting->border_radius) }}" maxlength="50">
                @error('border_radius')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="box_shadow">Box Shadow</label>
                <input id="box_shadow" name="box_shadow" type="text" class="form-control @error('box_shadow') is-invalid @enderror" value="{{ old('box_shadow', $themeSetting->box_shadow) }}" maxlength="255">
                @error('box_shadow')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Loader</h3>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Enable Loader</label>
                <div class="form-check form-switch">
                  <input id="loader_enabled" name="loader_enabled" type="checkbox" class="form-check-input" value="1" {{ old('loader_enabled', $themeSetting->loader_enabled) ? 'checked' : '' }}>
                  <label class="form-check-label" for="loader_enabled">Enabled</label>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="loader_style">Loader Style</label>
                <input id="loader_style" name="loader_style" type="text" class="form-control @error('loader_style') is-invalid @enderror" value="{{ old('loader_style', $themeSetting->loader_style) }}" maxlength="100">
                @error('loader_style')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="loader_color">Loader Color</label>
                <input id="loader_color" name="loader_color" type="text" class="form-control form-control-color @error('loader_color') is-invalid @enderror" value="{{ old('loader_color', $themeSetting->loader_color) }}" maxlength="20">
                @error('loader_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label" for="loader_background">Loader Background</label>
                <input id="loader_background" name="loader_background" type="text" class="form-control form-control-color @error('loader_background') is-invalid @enderror" value="{{ old('loader_background', $themeSetting->loader_background) }}" maxlength="20">
                @error('loader_background')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Animation</h3>
            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Enable Animation</label>
                <div class="form-check form-switch">
                  <input id="animation_enabled" name="animation_enabled" type="checkbox" class="form-check-input" value="1" {{ old('animation_enabled', $themeSetting->animation_enabled) ? 'checked' : '' }}>
                  <label class="form-check-label" for="animation_enabled">Enabled</label>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="animation_speed">Animation Speed (ms)</label>
                <input id="animation_speed" name="animation_speed" type="number" class="form-control @error('animation_speed') is-invalid @enderror" value="{{ old('animation_speed', $themeSetting->animation_speed) }}" min="0" max="2000">
                @error('animation_speed')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <h3 class="h6 mb-3">Custom Code</h3>
            <div class="mb-3">
              <label class="form-label" for="custom_css">Custom CSS</label>
              <textarea id="custom_css" name="custom_css" class="form-control @error('custom_css') is-invalid @enderror" rows="6">{{ old('custom_css', $themeSetting->custom_css) }}</textarea>
              @error('custom_css')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="form-label" for="custom_js">Custom JavaScript</label>
              <textarea id="custom_js" name="custom_js" class="form-control @error('custom_js') is-invalid @enderror" rows="6">{{ old('custom_js', $themeSetting->custom_js) }}</textarea>
              @error('custom_js')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="card border-light shadow-sm p-4 mb-4">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <div class="form-check form-switch">
                  <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $themeSetting->status) ? 'checked' : '' }}>
                  <label class="form-check-label" for="status">Active</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-outline-secondary">Reset</button>
        <button type="button" class="btn btn-outline-dark" onclick="window.location.reload();">Preview Theme</button>
      </div>
    </form>
  </div>
</div>
@endsection
