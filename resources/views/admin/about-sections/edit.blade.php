@extends('admin.layouts.app')

@section('title', 'About CMS')
@section('page-title', 'About CMS')
@section('page-description', 'Manage company story, statistics, values, timeline, and Why Choose Us content.')

@section('content')
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

    <ul class="nav nav-tabs mb-4">
      <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#story-pane" type="button">Company Story</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#stats-pane" type="button">Statistics</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#values-pane" type="button">Core Values</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#timeline-pane" type="button">Timeline</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#why-pane" type="button">Why Choose Us</button></li>
      <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button">SEO</button></li>
    </ul>

    <form method="POST" action="{{ route('admin.about-sections.update') }}">
      @csrf
      @method('PUT')

      <div class="tab-content">
        <div id="story-pane" class="tab-pane fade show active">
          <div class="row g-4">
            <div class="col-lg-8">
              <div class="mb-3">
                <label class="form-label" for="company_story">Company Story</label>
                <textarea id="company_story" name="company_story" class="form-control" rows="5">{{ old('company_story', $aboutSection->company_story) }}</textarea>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="mission">Mission</label>
                  <textarea id="mission" name="mission" class="form-control" rows="4">{{ old('mission', $aboutSection->mission) }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="vision">Vision</label>
                  <textarea id="vision" name="vision" class="form-control" rows="4">{{ old('vision', $aboutSection->vision) }}</textarea>
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label" for="chairman_message">Chairman Message</label>
                <textarea id="chairman_message" name="chairman_message" class="form-control" rows="4">{{ old('chairman_message', $aboutSection->chairman_message) }}</textarea>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-3">
                <label class="form-label" for="chairman_name">Chairman Name</label>
                <input id="chairman_name" name="chairman_name" class="form-control" value="{{ old('chairman_name', $aboutSection->chairman_name) }}">
              </div>
              <div class="mb-3">
                <label class="form-label" for="chairman_designation">Chairman Designation</label>
                <input id="chairman_designation" name="chairman_designation" class="form-control" value="{{ old('chairman_designation', $aboutSection->chairman_designation) }}">
              </div>
              @include('admin.about-sections._media-field', ['field' => 'chairman_image_id', 'label' => 'Chairman Image', 'modalId' => 'chairmanImageModal', 'media' => $aboutSection->chairmanImage])
              @include('admin.about-sections._media-field', ['field' => 'company_video_id', 'label' => 'Company Video', 'modalId' => 'companyVideoModal', 'media' => $aboutSection->companyVideo, 'image' => false])
              @include('admin.about-sections._media-field', ['field' => 'brochure_file_id', 'label' => 'Company Brochure', 'modalId' => 'brochureModal', 'media' => $aboutSection->brochureFile, 'image' => false])
            </div>
          </div>
        </div>

        <div id="stats-pane" class="tab-pane fade">
          <div class="row g-3">
            @foreach(['experience_years' => 'Years of Experience', 'completed_projects' => 'Completed Projects', 'happy_clients' => 'Happy Clients', 'team_members' => 'Team Members'] as $field => $label)
              <div class="col-md-3">
                <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" class="form-control" value="{{ old($field, $aboutSection->$field) }}">
              </div>
            @endforeach
            <div class="col-12">
              <div class="form-check form-switch mt-3">
                <input id="status" name="status" type="checkbox" class="form-check-input" value="1" {{ old('status', $aboutSection->status) ? 'checked' : '' }}>
                <label class="form-check-label" for="status">Publish About Section</label>
              </div>
            </div>
          </div>
        </div>

        <div id="values-pane" class="tab-pane fade">
          @include('admin.about-sections._simple-table', ['items' => $companyValues, 'createRoute' => route('admin.company-values.create'), 'editName' => 'admin.company-values.edit', 'deleteName' => 'admin.company-values.destroy', 'heading' => 'Core Values'])
        </div>

        <div id="timeline-pane" class="tab-pane fade">
          @include('admin.about-sections._simple-table', ['items' => $companyTimelines, 'createRoute' => route('admin.company-timelines.create'), 'editName' => 'admin.company-timelines.edit', 'deleteName' => 'admin.company-timelines.destroy', 'heading' => 'Timeline'])
        </div>

        <div id="why-pane" class="tab-pane fade">
          @include('admin.about-sections._simple-table', ['items' => $whyChooseUsItems, 'createRoute' => route('admin.why-choose-us.create'), 'editName' => 'admin.why-choose-us.edit', 'deleteName' => 'admin.why-choose-us.destroy', 'heading' => 'Why Choose Us'])
        </div>

        <div id="seo-pane" class="tab-pane fade">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="meta_title">Meta Title</label>
              <input id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $aboutSection->meta_title) }}">
            </div>
            <div class="col-md-6">
              @include('admin.about-sections._media-field', ['field' => 'og_image_id', 'label' => 'Open Graph Image', 'modalId' => 'ogImageModal', 'media' => $aboutSection->ogImage])
            </div>
            <div class="col-12">
              <label class="form-label" for="meta_description">Meta Description</label>
              <textarea id="meta_description" name="meta_description" class="form-control" rows="3">{{ old('meta_description', $aboutSection->meta_description) }}</textarea>
            </div>
            <div class="col-12">
              <label class="form-label" for="meta_keywords">Meta Keywords</label>
              <textarea id="meta_keywords" name="meta_keywords" class="form-control" rows="2">{{ old('meta_keywords', $aboutSection->meta_keywords) }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Save About CMS</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

@include('admin.partials.media-picker-modal', ['modalId' => 'chairmanImageModal', 'title' => 'Select Chairman Image', 'targetInput' => 'chairman_image_id', 'mediaItems' => $imageOptions, 'isImage' => true])
@include('admin.partials.media-picker-modal', ['modalId' => 'companyVideoModal', 'title' => 'Select Company Video', 'targetInput' => 'company_video_id', 'mediaItems' => $videoOptions])
@include('admin.partials.media-picker-modal', ['modalId' => 'brochureModal', 'title' => 'Select Brochure', 'targetInput' => 'brochure_file_id', 'mediaItems' => $fileOptions])
@include('admin.partials.media-picker-modal', ['modalId' => 'ogImageModal', 'title' => 'Select Open Graph Image', 'targetInput' => 'og_image_id', 'mediaItems' => $imageOptions, 'isImage' => true])
@endsection
