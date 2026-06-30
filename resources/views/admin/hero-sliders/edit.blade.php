@extends('admin.layouts.app')

@section('title', 'Edit Hero Slide')
@section('page-title', 'Edit Hero Slide')
@section('page-description', 'Update hero slide content, media, scheduling, and display behavior.')

@section('content')
  @include('admin.hero-sliders._form', [
      'action' => route('admin.hero-sliders.update', $heroSlider),
      'method' => 'PUT',
      'submitLabel' => 'Update Slide',
  ])
@endsection
