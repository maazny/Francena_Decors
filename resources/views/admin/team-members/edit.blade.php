@extends('admin.layouts.app')

@section('title', 'Edit Team Member')
@section('page-title', 'Edit Team Member')

@section('content')
  @include('admin.team-members._form', [
    'action' => route('admin.team-members.update', $teamMember),
    'method' => 'PUT',
    'submitLabel' => 'Update Member',
    'member' => $teamMember,
    'mediaOptions' => $mediaOptions,
    'departments' => $departments,
  ])
@endsection
