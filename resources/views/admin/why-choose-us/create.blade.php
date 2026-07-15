@extends('admin.layouts.app')

@section('title', 'Create Why Choose Us Item')
@section('page-title', 'Create Why Choose Us Item')
@section('page-description', 'Add a reason clients choose Francena Decors.')

@section('content')
  @include('admin.why-choose-us._form', [
      'action' => route('admin.why-choose-us.store'),
      'method' => 'POST',
      'submitLabel' => 'Create Item',
  ])
@endsection
