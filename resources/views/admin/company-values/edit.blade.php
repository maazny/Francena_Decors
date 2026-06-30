@extends('admin.layouts.app')

@section('title', 'Edit Company Value')
@section('page-title', 'Edit Company Value')
@section('page-description', 'Update this About core value.')

@section('content')
  @include('admin.company-values._form', [
      'action' => route('admin.company-values.update', $companyValue),
      'method' => 'PUT',
      'submitLabel' => 'Update Value',
  ])
@endsection
