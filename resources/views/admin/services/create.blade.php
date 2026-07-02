@extends('admin.layouts.app')

@section('title', 'Create Service')
@section('page-title', 'Create Service')
@section('page-description', 'Add a new service to the services CMS.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.services.store') }}" novalidate>
      @csrf
      @include('admin.services._form')
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Service</button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
