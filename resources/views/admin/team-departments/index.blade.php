@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Team Departments</h1>

    <a href="{{ route('admin.team-departments.create') }}" class="btn btn-primary mb-3">Create Department</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Display Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($departments as $dep)
            <tr>
                <td>{{ $dep->name }}</td>
                <td>{{ $dep->status ? 'Active' : 'Inactive' }}</td>
                <td>{{ $dep->display_order }}</td>
                <td>
                    <a href="{{ route('admin.team-departments.edit', $dep) }}" class="btn btn-sm btn-secondary">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $departments->links() }}
</div>
@endsection
