@extends('admin.layouts.app')

@section('title', 'Create Timeline Item')
@section('page-title', 'Create Timeline Item')
@section('page-description', 'Add a company milestone to the About timeline.')

@section('content')
  @include('admin.company-timelines._form', [
      'action' => route('admin.company-timelines.store'),
      'method' => 'POST',
      'submitLabel' => 'Create Timeline Item',
  ])
@endsection
