@extends('admin.layouts.app')

@section('title', 'Edit Service Category')
@section('page-title', 'Edit Service Category')
@section('page-description', 'Update the details for this service category.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.service-categories.update', $serviceCategory) }}" novalidate>
      @csrf
      @method('PUT')
      @include('admin.service-categories._form')
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Category</button>
        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
