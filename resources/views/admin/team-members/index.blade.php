@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Team Members</h1>

    <a href="{{ route('admin.team-members.create') }}" class="btn btn-primary mb-3">Create Member</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($members as $m)
            <tr>
                <td>{{ $m->full_name }}</td>
                <td>{{ $m->department?->name }}</td>
                <td>{{ $m->designation }}</td>
                <td>{{ $m->status ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="{{ route('admin.team-members.edit', $m) }}" class="btn btn-sm btn-secondary">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $members->links() }}
</div>
@endsection
