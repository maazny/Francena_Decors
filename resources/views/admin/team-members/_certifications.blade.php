<div class="card border-light shadow-sm p-4 mb-4">
  <h5>Certifications</h5>

  @if($member && $member->id)
    <form method="POST" action="{{ route('admin.team-members.certifications.store', $member) }}" class="row g-2 align-items-end" data-ajax-submit data-ajax-target="certifications-list">
      @csrf
      <div class="col-md-5">
        <label class="form-label">Title</label>
        <input name="title" type="text" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">Organization</label>
        <input name="organization" type="text" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Issue Date</label>
        <input name="issue_date" type="date" class="form-control">
      </div>
      <div class="col-md-9 mt-2">
        <label class="form-label">Certificate File (select from Media)</label>
        <div class="d-flex gap-2">
          <input name="certificate_file_id" type="hidden" id="certificate_file_id">
          <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#certificateFileModal">Select File</button>
        </div>
      </div>
      <div class="col-md-3 mt-2">
        <button type="submit" class="btn btn-primary w-100">Add</button>
      </div>
    </form>

    <hr>
    <div id="certifications-list">
      @include('admin.team-members._certifications-list', ['member' => $member])
    </div>

    @include('admin.hero-sliders._media-modal', ['modalId' => 'certificateFileModal', 'targetInput' => 'certificate_file_id', 'mediaItems' => $mediaOptions ?? collect(), 'title' => 'Select Certificate File', 'isVideo' => false])
  @else
    <div class="alert alert-info">Save the team member first to add certifications.</div>
  @endif
</div>
