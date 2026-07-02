@extends('admin.layouts.app')

@section('title', 'Create Service Category')
@section('page-title', 'Create Service Category')
@section('page-description', 'Add a new service category for the services CMS.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.service-categories.store') }}" novalidate>
      @csrf
      @include('admin.service-categories._form')
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save Category</button>
        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
