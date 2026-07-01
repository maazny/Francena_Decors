@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Our Team</h1>

    <div class="row">
        @foreach($members as $member)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($member->profilePhoto)
                        <img src="{{ $member->profilePhoto->thumbnailUrl ?? $member->profilePhoto->url }}" class="card-img-top" alt="{{ $member->full_name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title"><a href="{{ route('team.show', $member) }}">{{ $member->full_name }}</a></h5>
                        <p class="card-text">{{ $member->designation }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $members->links() }}
</div>
@endsection
