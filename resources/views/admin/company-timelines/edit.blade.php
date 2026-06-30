@extends('admin.layouts.app')

@section('title', 'Edit Timeline Item')
@section('page-title', 'Edit Timeline Item')
@section('page-description', 'Update this company timeline milestone.')

@section('content')
  @include('admin.company-timelines._form', [
      'action' => route('admin.company-timelines.update', $companyTimeline),
      'method' => 'PUT',
      'submitLabel' => 'Update Timeline Item',
  ])
@endsection
