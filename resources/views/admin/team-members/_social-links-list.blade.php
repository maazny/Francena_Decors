<ul class="list-group">
  @foreach($member->socialLinks as $link)
    <li class="list-group-item d-flex justify-content-between align-items-center" data-nested-item>
      <div>
        <strong>{{ $link->platform }}</strong>
        <div class="small text-muted">{{ $link->url }}</div>
      </div>
      <div>
        <button type="button" class="btn btn-sm btn-outline-danger" data-ajax-delete data-action="{{ route('admin.team-social-links.destroy', $link) }}">Delete</button>
      </div>
    </li>
  @endforeach
</ul>
