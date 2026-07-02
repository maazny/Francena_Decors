@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $teamMember->full_name }}</h1>
            <p class="text-muted">{{ $teamMember->designation }}</p>

            <div>
                {!! $teamMember->full_bio !!}
            </div>

            <h4 class="mt-4">Skills</h4>
            @foreach($teamMember->skills as $skill)
                <div class="mb-2">
                    <div class="d-flex justify-content-between"><strong>{{ $skill->skill_name }}</strong><span>{{ $skill->skill_percentage }}%</span></div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $skill->skill_percentage }}%;" aria-valuenow="{{ $skill->skill_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            @endforeach

            <h4 class="mt-4">Certifications</h4>
            <ul>
                @foreach($teamMember->certifications as $c)
                    <li>{{ $c->title }} @if($c->organization) - {{ $c->organization }} @endif</li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-4">
            <h5>Related</h5>
            @foreach($related as $r)
                <div><a href="{{ route('team.show', $r) }}">{{ $r->full_name }}</a></div>
            @endforeach
        </div>
    </div>
</div>
@endsection
