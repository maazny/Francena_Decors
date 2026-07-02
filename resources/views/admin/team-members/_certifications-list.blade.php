<ul class="list-group">
  @foreach($member->certifications as $certification)
    <li class="list-group-item d-flex justify-content-between align-items-center" data-nested-item>
      <div>
        <strong>{{ $certification->title }}</strong>
        <div class="small text-muted">{{ $certification->organization }} @if($certification->issue_date) - {{ $certification->issue_date->format('Y-m-d') }} @endif</div>
      </div>
      <div>
        <button type="button" class="btn btn-sm btn-outline-danger" data-ajax-delete data-action="{{ route('admin.team-certifications.destroy', $certification) }}">Delete</button>
      </div>
    </li>
  @endforeach
</ul>
