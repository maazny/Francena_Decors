@extends('admin.layouts.app')

@section('title', 'Create Client / Brand')
@section('page-title', 'Create Client / Brand')
@section('page-description', 'Add a new trusted client, partner, or brand entry.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.client-brands.store') }}">
      @csrf
      @include('admin.client-brands._form', ['brand' => $brand])
    </form>
  </div>
</div>
@endsection
