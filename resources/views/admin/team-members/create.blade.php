@extends('admin.layouts.app')

@section('title', 'Create Team Member')
@section('page-title', 'Create Team Member')

@section('content')
  @include('admin.team-members._form', [
    'action' => route('admin.team-members.store'),
    'method' => 'POST',
    'submitLabel' => 'Create Member',
    'member' => $member,
    'mediaOptions' => $mediaOptions,
    'departments' => $departments,
  ])
@endsection
