@extends('admin.layouts.app')

@section('title', 'Create Department')
@section('page-title', 'Create Department')

@section('content')
  @include('admin.team-departments._form', [
    'action' => route('admin.team-departments.store'),
    'method' => 'POST',
    'submitLabel' => 'Create Department',
    'department' => $department,
  ])
@endsection
