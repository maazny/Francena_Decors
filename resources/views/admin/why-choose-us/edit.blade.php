@extends('admin.layouts.app')

@section('title', 'Edit Why Choose Us Item')
@section('page-title', 'Edit Why Choose Us Item')
@section('page-description', 'Update this Why Choose Us item.')

@section('content')
  @include('admin.why-choose-us._form', [
      'action' => route('admin.why-choose-us.update', $whyChooseUs),
      'method' => 'PUT',
      'submitLabel' => 'Update Item',
  ])
@endsection
