@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')
@section('page-description', 'Manage all global website settings from one place.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
      <div>
        <h2 class="h5 mb-1">Site Settings</h2>
        <p class="text-muted mb-0">Update global site information, branding assets, and system settings.</p>
      </div>
      <div>
        <a href="{{ route('admin.site-settings.edit') }}" class="btn btn-outline-primary btn-sm">Reset</a>
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

    <form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-5">
        <h3 class="h6 text-uppercase text-muted mb-3">General Information</h3>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" for="site_name">Site Name</label>
            <input id="site_name" name="site_name" type="text" class="form-control @error('site_name') is-invalid @enderror" value="{{ old('site_name', $siteSetting->site_name) }}" required maxlength="150">
            @error('site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label" for="company_name">Company Name</label>
            <input id="company_name" name="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $siteSetting->company_name) }}" required maxlength="150">
            @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label class="form-label" for="tagline">Tagline</label>
            <input id="tagline" name="tagline" type="text" class="form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $siteSetting->tagline) }}" required maxlength="255">
            @error('tagline')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label" for="company_email">Company Email</label>
            <input id="company_email" name="company_email" type="email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email', $siteSetting->company_email) }}" required maxlength="150">
            @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label" for="support_email">Support Email</label>
            <input id="support_email" name="support_email" type="email" class="form-control @error('support_email') is-invalid @enderror" value="{{ old('support_email', $siteSetting->support_email) }}" required maxlength="150">
            @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <div class="mb-5">
        <h3 class="h6 text-uppercase text-muted mb-3">Contact</h3>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label" for="phone">Phone</label>
            <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $siteSetting->phone) }}" required maxlength="30">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label" for="mobile">Mobile</label>
            <input id="mobile" name="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $siteSetting->mobile) }}" required maxlength="30">
            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label" for="whatsapp">WhatsApp</label>
            <input id="whatsapp" name="whatsapp" type="text" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $siteSetting->whatsapp) }}" required maxlength="30">
            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label class="form-label" for="address">Address</label>
            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required maxlength="500">{{ old('address', $siteSetting->address) }}</textarea>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label" for="city">City</label>
            <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $siteSetting->city) }}" required maxlength="100">
            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label" for="state">State</label>
            <input id="state" name="state" type="text" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $siteSetting->state) }}" required maxlength="100">
            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label" for="country">Country</label>
            <input id="country" name="country" type="text" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $siteSetting->country) }}" required maxlength="100">
            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label" for="postal_code">Postal Code</label>
            <input id="postal_code" name="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $siteSetting->postal_code) }}" required maxlength="20">
            @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label" for="google_map">Google Map URL</label>
            <input id="google_map" name="google_map" type="url" class="form-control @error('google_map') is-invalid @enderror" value="{{ old('google_map', $siteSetting->google_map) }}" maxlength="500">
            @error('google_map')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label" for="office_hours">Office Hours</label>
            <input id="office_hours" name="office_hours" type="text" class="form-control @error('office_hours') is-invalid @enderror" value="{{ old('office_hours', $siteSetting->office_hours) }}" required maxlength="150">
            @error('office_hours')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <div class="mb-5">
        <h3 class="h6 text-uppercase text-muted mb-3">Branding</h3>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label" for="logo">Logo Upload</label>
            <input id="logo" name="logo" type="file" class="form-control @error('logo') is-invalid @enderror">
            @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($siteSetting->logo_url)
              <div class="mt-3">
                <img src="{{ $siteSetting->logo_url }}" alt="Site logo preview" class="img-fluid border rounded" style="max-height: 90px;">
              </div>
            @endif
          </div>
          <div class="col-md-4">
            <label class="form-label" for="footer_logo">Footer Logo Upload</label>
            <input id="footer_logo" name="footer_logo" type="file" class="form-control @error('footer_logo') is-invalid @enderror">
            @error('footer_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($siteSetting->footer_logo_url)
              <div class="mt-3">
                <img src="{{ $siteSetting->footer_logo_url }}" alt="Footer logo preview" class="img-fluid border rounded" style="max-height: 90px;">
              </div>
            @endif
          </div>
          <div class="col-md-4">
            <label class="form-label" for="favicon">Favicon Upload</label>
            <input id="favicon" name="favicon" type="file" class="form-control @error('favicon') is-invalid @enderror">
            @error('favicon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($siteSetting->favicon_url)
              <div class="mt-3">
                <img src="{{ $siteSetting->favicon_url }}" alt="Favicon preview" class="img-fluid border rounded" style="max-height: 60px; width: auto;">
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="mb-5">
        <h3 class="h6 text-uppercase text-muted mb-3">System</h3>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label" for="default_language">Language</label>
            <input id="default_language" name="default_language" type="text" class="form-control @error('default_language') is-invalid @enderror" value="{{ old('default_language', $siteSetting->default_language) }}" required maxlength="20">
            @error('default_language')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label" for="timezone">Timezone</label>
            <input id="timezone" name="timezone" type="text" class="form-control @error('timezone') is-invalid @enderror" value="{{ old('timezone', $siteSetting->timezone) }}" required maxlength="150">
            @error('timezone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Maintenance Mode</label>
            <div class="form-check form-switch">
              <input id="maintenance_mode" name="maintenance_mode" type="checkbox" class="form-check-input" value="1" {{ old('maintenance_mode', $siteSetting->maintenance_mode) ? 'checked' : '' }}>
              <label class="form-check-label" for="maintenance_mode">Enabled</label>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label" for="maintenance_message">Maintenance Message</label>
            <textarea id="maintenance_message" name="maintenance_message" class="form-control @error('maintenance_message') is-invalid @enderror" rows="3" maxlength="500">{{ old('maintenance_message', $siteSetting->maintenance_message) }}</textarea>
            @error('maintenance_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <div class="form-check form-switch">
              <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $siteSetting->status) ? 'checked' : '' }}>
              <label class="form-check-label" for="status">Active</label>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-5">
        <h3 class="h6 text-uppercase text-muted mb-3">Footer</h3>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label" for="footer_text">Footer Text</label>
            <textarea id="footer_text" name="footer_text" class="form-control @error('footer_text') is-invalid @enderror" rows="3" required maxlength="500">{{ old('footer_text', $siteSetting->footer_text) }}</textarea>
            @error('footer_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <label class="form-label" for="copyright">Copyright</label>
            <input id="copyright" name="copyright" type="text" class="form-control @error('copyright') is-invalid @enderror" value="{{ old('copyright', $siteSetting->copyright) }}" required maxlength="255">
            @error('copyright')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-outline-secondary">Reset</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
