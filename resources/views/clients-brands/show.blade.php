@extends('layouts.app')

@section('title', $brand->name)
@section('meta_description', $brand->description)
@section('og_title', $brand->name)
@section('og_description', $brand->description)
@section('og_type', 'website')
@section('og_url', route('clients-brands.show', $brand))
@section('og_image', $brand->logo ? image_url($brand->logo) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('twitter_title', $brand->name)
@section('twitter_description', $brand->description)
@section('twitter_image', $brand->logo ? image_url($brand->logo) : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80')
@section('canonical', route('clients-brands.show', $brand))

@section('content')
<div class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-lg-4">
      @if($brand->logo)
        <img src="{{ $brand->logo->url }}" alt="{{ $brand->name }}" class="img-fluid rounded shadow-sm">
      @endif
    </div>
    <div class="col-lg-8">
      <span class="section-label">Client / Brand</span>
      <h1 class="display-6 fw-semibold">{{ $brand->name }}</h1>
      @if($brand->category)
        <p class="text-muted mb-3">{{ $brand->category }}</p>
      @endif
      <div class="text-muted">{!! nl2br(e($brand->description)) !!}</div>
      @if($brand->website_url)
        <a href="{{ $brand->website_url }}" target="_blank" rel="noopener" class="btn btn-dark mt-4">Visit Website</a>
      @endif
    </div>
  </div>
</div>
@endsection
