@extends('admin.layouts.app')

@section('title', 'Footer Settings')
@section('page-title', 'Footer Settings')
@section('page-description', 'Manage the website footer layout, content, links, newsletter, styling, and widgets.')

@section('content')
@php
  $columns = old('columns', $footerSetting->columns->map(function ($column) {
      return [
          'title' => $column->title,
          'type' => $column->type,
          'sort_order' => $column->sort_order,
          'status' => $column->status,
          'links' => $column->links->map(fn ($link) => [
              'label' => $link->label,
              'url' => $link->url,
              'target' => $link->target,
              'icon' => $link->icon,
              'sort_order' => $link->sort_order,
              'status' => $link->status,
          ])->values()->all(),
      ];
  })->values()->all());
  $socialLinks = old('social_links', $footerSetting->socialLinks->toArray());
  $businessHours = old('business_hours', $footerSetting->businessHours->toArray());
  $widgets = old('widgets', $footerSetting->widgets->toArray());
@endphp

<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Enterprise Footer CMS</h2>
        <p class="text-muted mb-0">Configure all frontend footer sections from one production-ready settings screen.</p>
      </div>
      <a href="{{ route('admin.footer.settings.edit') }}" class="btn btn-outline-secondary btn-sm">Refresh</a>
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

    <form id="footer-settings-form" method="POST" action="{{ route('admin.footer.settings.update') }}">
      @csrf
      @method('PUT')

      <ul class="nav nav-tabs mb-4" id="footerSettingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab">General</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="links-tab" data-bs-toggle="tab" data-bs-target="#links-pane" type="button" role="tab">Columns & Links</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-pane" type="button" role="tab">Contact</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="newsletter-tab" data-bs-toggle="tab" data-bs-target="#newsletter-pane" type="button" role="tab">Newsletter</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="style-tab" data-bs-toggle="tab" data-bs-target="#style-pane" type="button" role="tab">Style & Preview</button>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="general-pane" role="tabpanel" tabindex="0">
          <div class="row g-4">
            <div class="col-lg-7">
              <div class="card border-light shadow-sm p-4 mb-4">
                <h3 class="h6 mb-3">Layout</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="layout">Footer Layout</label>
                    <select id="layout" name="layout" class="form-select @error('layout') is-invalid @enderror" required>
                      @foreach(['four_columns' => 'Four Columns', 'three_columns' => 'Three Columns', 'two_columns' => 'Two Columns', 'stacked' => 'Stacked'] as $value => $label)
                        <option value="{{ $value }}" {{ old('layout', $footerSetting->layout) === $value ? 'selected' : '' }}>{{ $label }}</option>
                      @endforeach
                    </select>
                    @error('layout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch">
                      <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $footerSetting->status) ? 'checked' : '' }}>
                      <label class="form-check-label" for="status">Published</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <label class="form-label" for="company_description">Company Description</label>
                    <textarea id="company_description" name="company_description" class="form-control @error('company_description') is-invalid @enderror" rows="4" maxlength="1000" data-preview-source="description">{{ old('company_description', $footerSetting->company_description) }}</textarea>
                    @error('company_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>

              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Section Visibility</h3>
                <div class="row g-3">
                  @foreach([
                    'show_logo' => 'Footer Logo',
                    'show_description' => 'Company Description',
                    'show_columns' => 'Link Columns',
                    'show_contact' => 'Contact Information',
                    'show_business_hours' => 'Business Hours',
                    'show_social_links' => 'Social Media',
                    'show_widgets' => 'Footer Widgets',
                    'bottom_bar_enabled' => 'Bottom Bar',
                  ] as $field => $label)
                    <div class="col-md-6">
                      <div class="form-check form-switch">
                        <input id="{{ $field }}" name="{{ $field }}" type="checkbox" class="form-check-input" value="1" {{ old($field, $footerSetting->$field) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $field }}">{{ $label }}</label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Media Library</h3>
                <div class="mb-3">
                  <label class="form-label" for="logo_media_id">Footer Logo</label>
                  <select id="logo_media_id" name="logo_media_id" class="form-select @error('logo_media_id') is-invalid @enderror" data-media-preview="footer-logo-preview">
                    <option value="">No logo selected</option>
                    @foreach($mediaOptions as $media)
                      <option value="{{ $media->id }}" data-url="{{ $media->url }}" {{ (string) old('logo_media_id', $footerSetting->logo_media_id) === (string) $media->id ? 'selected' : '' }}>{{ $media->title ?: $media->original_name }}</option>
                    @endforeach
                  </select>
                  @error('logo_media_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  <img id="footer-logo-preview" src="{{ $footerSetting->logo ? $footerSetting->logo->url : '' }}" alt="Footer logo preview" class="img-fluid border rounded mt-3 {{ $footerSetting->logo ? '' : 'd-none' }}" style="max-height: 90px;">
                </div>

                <div>
                  <label class="form-label" for="background_media_id">Footer Background</label>
                  <select id="background_media_id" name="background_media_id" class="form-select @error('background_media_id') is-invalid @enderror" data-media-preview="footer-background-preview">
                    <option value="">No background selected</option>
                    @foreach($mediaOptions as $media)
                      <option value="{{ $media->id }}" data-url="{{ $media->url }}" {{ (string) old('background_media_id', $footerSetting->background_media_id) === (string) $media->id ? 'selected' : '' }}>{{ $media->title ?: $media->original_name }}</option>
                    @endforeach
                  </select>
                  @error('background_media_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  <img id="footer-background-preview" src="{{ $footerSetting->background ? $footerSetting->background->url : '' }}" alt="Footer background preview" class="img-fluid border rounded mt-3 {{ $footerSetting->background ? '' : 'd-none' }}" style="max-height: 140px;">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="links-pane" role="tabpanel" tabindex="0">
          @for($columnIndex = 0; $columnIndex < 6; $columnIndex++)
            @php $column = $columns[$columnIndex] ?? ['title' => '', 'type' => 'links', 'sort_order' => $columnIndex, 'status' => true, 'links' => []]; @endphp
            <div class="card border-light shadow-sm p-4 mb-4">
              <div class="row g-3 align-items-end mb-3">
                <div class="col-md-4">
                  <label class="form-label" for="columns_{{ $columnIndex }}_title">Column Title</label>
                  <input id="columns_{{ $columnIndex }}_title" name="columns[{{ $columnIndex }}][title]" type="text" class="form-control" value="{{ $column['title'] ?? '' }}" maxlength="191">
                </div>
                <div class="col-md-3">
                  <label class="form-label" for="columns_{{ $columnIndex }}_type">Column Type</label>
                  <select id="columns_{{ $columnIndex }}_type" name="columns[{{ $columnIndex }}][type]" class="form-select">
                    @foreach(['quick_links' => 'Quick Links', 'services' => 'Services Links', 'useful_links' => 'Useful Links', 'links' => 'Custom Links'] as $value => $label)
                      <option value="{{ $value }}" {{ ($column['type'] ?? 'links') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <label class="form-label" for="columns_{{ $columnIndex }}_sort_order">Order</label>
                  <input id="columns_{{ $columnIndex }}_sort_order" name="columns[{{ $columnIndex }}][sort_order]" type="number" class="form-control" value="{{ $column['sort_order'] ?? $columnIndex }}" min="0" max="100">
                </div>
                <div class="col-md-3">
                  <div class="form-check form-switch">
                    <input id="columns_{{ $columnIndex }}_status" name="columns[{{ $columnIndex }}][status]" type="checkbox" class="form-check-input" value="1" {{ ($column['status'] ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="columns_{{ $columnIndex }}_status">Enabled</label>
                  </div>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-sm align-middle">
                  <thead>
                    <tr>
                      <th>Label</th>
                      <th>URL</th>
                      <th>Icon Class</th>
                      <th>Target</th>
                      <th style="width: 90px;">Order</th>
                      <th style="width: 110px;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($linkIndex = 0; $linkIndex < 6; $linkIndex++)
                      @php $link = $column['links'][$linkIndex] ?? ['label' => '', 'url' => '', 'target' => '_self', 'icon' => '', 'sort_order' => $linkIndex, 'status' => true]; @endphp
                      <tr>
                        <td><input name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][label]" type="text" class="form-control form-control-sm" value="{{ $link['label'] ?? '' }}" maxlength="191"></td>
                        <td><input name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][url]" type="text" class="form-control form-control-sm" value="{{ $link['url'] ?? '' }}" maxlength="500"></td>
                        <td><input name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][icon]" type="text" class="form-control form-control-sm" value="{{ $link['icon'] ?? '' }}" maxlength="100"></td>
                        <td>
                          <select name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][target]" class="form-select form-select-sm">
                            <option value="_self" {{ ($link['target'] ?? '_self') === '_self' ? 'selected' : '' }}>Same</option>
                            <option value="_blank" {{ ($link['target'] ?? '_self') === '_blank' ? 'selected' : '' }}>New</option>
                          </select>
                        </td>
                        <td><input name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][sort_order]" type="number" class="form-control form-control-sm" value="{{ $link['sort_order'] ?? $linkIndex }}" min="0" max="100"></td>
                        <td>
                          <div class="form-check form-switch">
                            <input name="columns[{{ $columnIndex }}][links][{{ $linkIndex }}][status]" type="checkbox" class="form-check-input" value="1" {{ ($link['status'] ?? true) ? 'checked' : '' }}>
                          </div>
                        </td>
                      </tr>
                    @endfor
                  </tbody>
                </table>
              </div>
            </div>
          @endfor
        </div>

        <div class="tab-pane fade" id="contact-pane" role="tabpanel" tabindex="0">
          <div class="row g-4">
            <div class="col-lg-6">
              <div class="card border-light shadow-sm p-4 mb-4">
                <h3 class="h6 mb-3">Contact Information</h3>
                <div class="mb-3">
                  <label class="form-label" for="contact_heading">Heading</label>
                  <input id="contact_heading" name="contact_heading" type="text" class="form-control @error('contact_heading') is-invalid @enderror" value="{{ old('contact_heading', $footerSetting->contact_heading) }}" maxlength="191">
                  @error('contact_heading')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label class="form-label" for="contact_address">Address</label>
                  <textarea id="contact_address" name="contact_address" class="form-control @error('contact_address') is-invalid @enderror" rows="3" maxlength="500">{{ old('contact_address', $footerSetting->contact_address) }}</textarea>
                  @error('contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="contact_phone">Phone</label>
                    <input id="contact_phone" name="contact_phone" type="text" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $footerSetting->contact_phone) }}" maxlength="50">
                    @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="contact_email">Email</label>
                    <input id="contact_email" name="contact_email" type="email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $footerSetting->contact_email) }}" maxlength="191">
                    @error('contact_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>

              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Business Hours</h3>
                <div class="mb-3">
                  <label class="form-label" for="business_hours_heading">Heading</label>
                  <input id="business_hours_heading" name="business_hours_heading" type="text" class="form-control" value="{{ old('business_hours_heading', $footerSetting->business_hours_heading) }}" maxlength="191">
                </div>
                @for($index = 0; $index < 7; $index++)
                  @php $hour = $businessHours[$index] ?? ['day_label' => '', 'time_label' => '', 'sort_order' => $index, 'status' => true]; @endphp
                  <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-4"><input name="business_hours[{{ $index }}][day_label]" type="text" class="form-control form-control-sm" value="{{ $hour['day_label'] ?? '' }}" placeholder="Day" maxlength="100"></div>
                    <div class="col-md-4"><input name="business_hours[{{ $index }}][time_label]" type="text" class="form-control form-control-sm" value="{{ $hour['time_label'] ?? '' }}" placeholder="Hours" maxlength="150"></div>
                    <div class="col-md-2"><input name="business_hours[{{ $index }}][sort_order]" type="number" class="form-control form-control-sm" value="{{ $hour['sort_order'] ?? $index }}" min="0" max="100"></div>
                    <div class="col-md-2"><input name="business_hours[{{ $index }}][status]" type="checkbox" class="form-check-input" value="1" {{ ($hour['status'] ?? true) ? 'checked' : '' }}></div>
                  </div>
                @endfor
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card border-light shadow-sm p-4 mb-4">
                <h3 class="h6 mb-3">Social Media</h3>
                @for($index = 0; $index < 8; $index++)
                  @php $social = $socialLinks[$index] ?? ['platform' => '', 'url' => '', 'icon' => '', 'sort_order' => $index, 'status' => true]; @endphp
                  <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-3"><input name="social_links[{{ $index }}][platform]" type="text" class="form-control form-control-sm" value="{{ $social['platform'] ?? '' }}" placeholder="Platform" maxlength="100"></div>
                    <div class="col-md-4"><input name="social_links[{{ $index }}][url]" type="url" class="form-control form-control-sm" value="{{ $social['url'] ?? '' }}" placeholder="https://..." maxlength="500"></div>
                    <div class="col-md-3"><input name="social_links[{{ $index }}][icon]" type="text" class="form-control form-control-sm" value="{{ $social['icon'] ?? '' }}" placeholder="fab fa-instagram" maxlength="100"></div>
                    <div class="col-md-1"><input name="social_links[{{ $index }}][sort_order]" type="number" class="form-control form-control-sm" value="{{ $social['sort_order'] ?? $index }}" min="0" max="100"></div>
                    <div class="col-md-1"><input name="social_links[{{ $index }}][status]" type="checkbox" class="form-check-input" value="1" {{ ($social['status'] ?? true) ? 'checked' : '' }}></div>
                  </div>
                @endfor
              </div>

              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Footer Widgets</h3>
                @for($index = 0; $index < 4; $index++)
                  @php $widget = $widgets[$index] ?? ['title' => '', 'content' => '', 'icon' => '', 'sort_order' => $index, 'status' => true]; @endphp
                  <div class="border rounded p-3 mb-3">
                    <div class="row g-2 mb-2">
                      <div class="col-md-5"><input name="widgets[{{ $index }}][title]" type="text" class="form-control form-control-sm" value="{{ $widget['title'] ?? '' }}" placeholder="Widget title" maxlength="191"></div>
                      <div class="col-md-4"><input name="widgets[{{ $index }}][icon]" type="text" class="form-control form-control-sm" value="{{ $widget['icon'] ?? '' }}" placeholder="Icon class" maxlength="100"></div>
                      <div class="col-md-2"><input name="widgets[{{ $index }}][sort_order]" type="number" class="form-control form-control-sm" value="{{ $widget['sort_order'] ?? $index }}" min="0" max="100"></div>
                      <div class="col-md-1"><input name="widgets[{{ $index }}][status]" type="checkbox" class="form-check-input" value="1" {{ ($widget['status'] ?? true) ? 'checked' : '' }}></div>
                    </div>
                    <textarea name="widgets[{{ $index }}][content]" class="form-control form-control-sm" rows="2" placeholder="Widget content" maxlength="1000">{{ $widget['content'] ?? '' }}</textarea>
                  </div>
                @endfor
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="newsletter-pane" role="tabpanel" tabindex="0">
          <div class="card border-light shadow-sm p-4">
            <div class="form-check form-switch mb-3">
              <input id="newsletter_enabled" name="newsletter_enabled" type="checkbox" class="form-check-input" value="1" {{ old('newsletter_enabled', $footerSetting->newsletter_enabled) ? 'checked' : '' }}>
              <label class="form-check-label" for="newsletter_enabled">Enable Newsletter Section</label>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="newsletter_title">Title</label>
                <input id="newsletter_title" name="newsletter_title" type="text" class="form-control" value="{{ old('newsletter_title', $footerSetting->newsletter_title) }}" maxlength="191" data-preview-source="newsletterTitle">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="newsletter_button_text">Button Text</label>
                <input id="newsletter_button_text" name="newsletter_button_text" type="text" class="form-control" value="{{ old('newsletter_button_text', $footerSetting->newsletter_button_text) }}" maxlength="100">
              </div>
              <div class="col-md-6">
                <label class="form-label" for="newsletter_placeholder">Input Placeholder</label>
                <input id="newsletter_placeholder" name="newsletter_placeholder" type="text" class="form-control" value="{{ old('newsletter_placeholder', $footerSetting->newsletter_placeholder) }}" maxlength="191">
              </div>
              <div class="col-12">
                <label class="form-label" for="newsletter_description">Description</label>
                <textarea id="newsletter_description" name="newsletter_description" class="form-control" rows="3" maxlength="500" data-preview-source="newsletterDescription">{{ old('newsletter_description', $footerSetting->newsletter_description) }}</textarea>
              </div>
              <div class="col-12">
                <label class="form-label" for="copyright_text">Copyright</label>
                <input id="copyright_text" name="copyright_text" type="text" class="form-control" value="{{ old('copyright_text', $footerSetting->copyright_text) }}" maxlength="500">
              </div>
              <div class="col-12">
                <label class="form-label" for="bottom_bar_text">Bottom Bar Text</label>
                <input id="bottom_bar_text" name="bottom_bar_text" type="text" class="form-control" value="{{ old('bottom_bar_text', $footerSetting->bottom_bar_text) }}" maxlength="255">
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="style-pane" role="tabpanel" tabindex="0">
          <div class="row g-4">
            <div class="col-lg-5">
              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Footer Colors</h3>
                <div class="row g-3">
                  @foreach([
                    'background_color' => 'Background',
                    'text_color' => 'Text',
                    'heading_color' => 'Headings',
                    'link_color' => 'Links',
                    'bottom_background_color' => 'Bottom Bar Background',
                  ] as $field => $label)
                    <div class="col-md-6">
                      <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                      <input id="{{ $field }}" name="{{ $field }}" type="text" class="form-control form-control-color @error($field) is-invalid @enderror" value="{{ old($field, $footerSetting->$field) }}" maxlength="20" data-footer-color="{{ $field }}">
                      @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="col-lg-7">
              <div class="card border-light shadow-sm p-4">
                <h3 class="h6 mb-3">Live Preview</h3>
                <div id="footer-live-preview" class="rounded p-4 text-white" style="background: {{ old('background_color', $footerSetting->background_color ?: '#111111') }};">
                  <div class="mb-3">
                    <strong data-preview-target="newsletterTitle">{{ old('newsletter_title', $footerSetting->newsletter_title) ?: 'Newsletter title' }}</strong>
                    <p class="small mb-0" data-preview-target="newsletterDescription">{{ old('newsletter_description', $footerSetting->newsletter_description) ?: 'Newsletter description preview' }}</p>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <div class="small text-uppercase">Brand</div>
                      <p class="mb-0" data-preview-target="description">{{ old('company_description', $footerSetting->company_description) ?: 'Company description preview' }}</p>
                    </div>
                    <div class="col-md-6">
                      <div class="small text-uppercase">Footer Sections</div>
                      <p class="mb-0">Columns, contact, hours, social links, widgets, and bottom bar follow the enabled settings.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Save Footer Settings</button>
        <button type="reset" class="btn btn-outline-secondary">Reset</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.querySelectorAll('[data-media-preview]').forEach((select) => {
    select.addEventListener('change', () => {
      const preview = document.getElementById(select.dataset.mediaPreview);
      const url = select.options[select.selectedIndex]?.dataset.url || '';

      if (! preview) {
        return;
      }

      preview.src = url;
      preview.classList.toggle('d-none', ! url);
    });
  });

  document.querySelectorAll('[data-preview-source]').forEach((field) => {
    const syncPreview = () => {
      document.querySelectorAll(`[data-preview-target="${field.dataset.previewSource}"]`).forEach((target) => {
        target.textContent = field.value || target.textContent;
      });
    };

    field.addEventListener('input', syncPreview);
  });

  document.querySelectorAll('[data-footer-color]').forEach((field) => {
    field.addEventListener('input', () => {
      const preview = document.getElementById('footer-live-preview');

      if (preview && field.dataset.footerColor === 'background_color') {
        preview.style.background = field.value || '#111111';
      }
    });
  });
</script>
@endsection
