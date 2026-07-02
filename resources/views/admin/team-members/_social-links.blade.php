<div class="card border-light shadow-sm p-4 mb-4">
  <h5>Social Links</h5>

  @if($member && $member->id)
    <form method="POST" action="{{ route('admin.team-members.social-links.store', $member) }}" class="row g-2 align-items-end" data-ajax-submit data-ajax-target="social-links-list">
      @csrf
      <div class="col-md-4">
        <label class="form-label">Platform</label>
        <input name="platform" type="text" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">URL</label>
        <input name="url" type="url" class="form-control" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Add</button>
      </div>
    </form>

    <hr>
    <div id="social-links-list">
      @include('admin.team-members._social-links-list', ['member' => $member])
    </div>
  @else
    <div class="alert alert-info">Save the team member first to add social links.</div>
  @endif
</div>
