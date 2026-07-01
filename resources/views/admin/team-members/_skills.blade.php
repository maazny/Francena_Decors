<div class="card border-light shadow-sm p-4 mb-4">
  <h5>Skills</h5>

  @if($member && $member->id)
    <form method="POST" action="{{ route('admin.team-members.skills.store', $member) }}" class="row g-2 align-items-end" data-ajax-submit data-ajax-target="skills-list">
      @csrf
      <div class="col-md-6">
        <label class="form-label">Skill Name</label>
        <input name="skill_name" type="text" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Percentage</label>
        <input name="skill_percentage" type="number" class="form-control" min="0" max="100" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Add</button>
      </div>
    </form>

    <hr>
    <div id="skills-list">
      @include('admin.team-members._skills-list', ['member' => $member])
    </div>
  @else
    <div class="alert alert-info">Save the team member first to add skills.</div>
  @endif
</div>
