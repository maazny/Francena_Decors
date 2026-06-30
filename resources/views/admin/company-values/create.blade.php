@extends('admin.layouts.app')

@section('title', 'Create Company Value')
@section('page-title', 'Create Company Value')
@section('page-description', 'Add a core value to the About section.')

@section('content')
  @include('admin.company-values._form', [
      'action' => route('admin.company-values.store'),
      'method' => 'POST',
      'submitLabel' => 'Create Value',
  ])
@endsection
