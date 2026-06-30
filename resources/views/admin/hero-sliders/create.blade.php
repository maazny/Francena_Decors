@extends('admin.layouts.app')

@section('title', 'Create Hero Slide')
@section('page-title', 'Create Hero Slide')
@section('page-description', 'Add a scheduled, media-driven hero slide for the website homepage.')

@section('content')
  @include('admin.hero-sliders._form', [
      'action' => route('admin.hero-sliders.store'),
      'method' => 'POST',
      'submitLabel' => 'Create Slide',
  ])
@endsection
