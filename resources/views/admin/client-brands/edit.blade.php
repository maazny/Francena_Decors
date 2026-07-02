@extends('admin.layouts.app')

@section('title', 'Edit Client / Brand')
@section('page-title', 'Edit Client / Brand')
@section('page-description', 'Update the client, partner, or brand entry.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.client-brands.update', $brand) }}">
      @csrf
      @method('PUT')
      @include('admin.client-brands._form', ['brand' => $brand])
    </form>
  </div>
</div>
@endsection
