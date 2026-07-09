@extends('layouts.app')

@section('title', 'Access Denied | Fancy Decorators')

@section('content')
<section class="error-page py-5 bg-dark text-white d-flex align-items-center justify-content-center" style="min-height: 70vh;">
  <div class="container py-5 text-center">
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <span class="text-uppercase tracking-wider small text-danger mb-2 d-block">Error 403</span>
        <h1 class="display-3 fw-bold mb-3 font-heading" style="color: var(--danger-color, #dc3545);">Access Denied</h1>
        <p class="text-white-50 lead fs-6 mb-5 mx-auto" style="max-width: 580px;">
          You do not have administrative authorization or sufficient permissions to access this luxury node or page.
        </p>
        <div class="d-flex gap-3 justify-content-center">
          <a href="{{ url('/') }}" class="btn btn-gold btn-lg px-4 py-2 text-dark fw-semibold" style="background-color: var(--button-background, #b19356); border-color: var(--button-background, #b19356);">
            Back to Home
          </a>
          <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg px-4 py-2">
            Inquire Permissions
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
