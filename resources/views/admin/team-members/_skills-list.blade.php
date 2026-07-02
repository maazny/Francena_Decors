<ul class="list-group">
  @foreach($member->skills as $skill)
    <li class="list-group-item d-flex justify-content-between align-items-center" data-nested-item>
      <div>
        <strong>{{ $skill->skill_name }}</strong>
        <div class="small text-muted">{{ $skill->skill_percentage }}%</div>
      </div>
      <div>
        <button type="button" class="btn btn-sm btn-outline-danger" data-ajax-delete data-action="{{ route('admin.team-skills.destroy', $skill) }}">Delete</button>
      </div>
    </li>
  @endforeach
</ul>
