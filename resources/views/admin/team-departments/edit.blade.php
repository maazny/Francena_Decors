@extends('admin.layouts.app')

@section('title', 'Edit Department')
@section('page-title', 'Edit Department')

@section('content')
  @include('admin.team-departments._form', [
    'action' => route('admin.team-departments.update', $teamDepartment),
    'method' => 'PUT',
    'submitLabel' => 'Update Department',
    'department' => $teamDepartment,
  ])
@endsection
