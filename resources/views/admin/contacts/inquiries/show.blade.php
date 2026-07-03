@extends('admin.layouts.app')

@section('title', 'Inquiry Details')
@section('page-title', 'Inquiry Details')
@section('page-description', 'Manage client request, replies, internal annotations, and lifecycle state.')

@section('content')
@php
  // Generate chronological activity feed dynamically
  $timeline = collect();
  $timeline->push([
      'label' => 'Contact Created',
      'time' => $contact->created_at,
      'details' => "Inquiry received via " . ucfirst($contact->source->value ?? $contact->source),
      'icon' => 'bi-plus-circle',
      'color' => 'success'
  ]);
  foreach($contact->replies as $reply) {
      $timeline->push([
          'label' => 'Reply Sent',
          'time' => $reply->created_at,
          'details' => "Response sent by " . ($reply->user->name ?? 'Admin'),
          'icon' => 'bi-reply-fill',
          'color' => 'primary'
      ]);
  }
  foreach($contact->notes as $note) {
      $timeline->push([
          'label' => 'Internal Note Added',
          'time' => $note->created_at,
          'details' => "Added by " . ($note->user->name ?? 'Admin'),
          'icon' => 'bi-journal-text',
          'color' => 'info'
      ]);
  }
  if ($contact->follow_up_at) {
      $timeline->push([
          'label' => 'Follow-up Scheduled',
          'time' => $contact->follow_up_at,
          'details' => "Target callback scheduled",
          'icon' => 'bi-calendar-event',
          'color' => 'warning'
      ]);
  }
  $timeline = $timeline->sortBy('time');
@endphp

<div class="row g-4">
  <!-- Left Side Column: Inquiry details, reply form, notes -->
  <div class="col-lg-8">
    <!-- Message content card -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
          <div>
            <h3 class="h5 mb-1">{{ $contact->subject }}</h3>
            <p class="text-muted mb-0">From: <strong>{{ $contact->name }}</strong> ({{ $contact->email }})</p>
          </div>
          <span class="badge bg-{{ ! $contact->is_read ? 'danger' : 'secondary' }}">
            {{ ! $contact->is_read ? 'New / Unread' : 'Read' }}
          </span>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <span class="text-muted small d-block">Phone Number</span>
            <strong>{{ $contact->phone ?? 'N/A' }}</strong>
          </div>
          <div class="col-md-6">
            <span class="text-muted small d-block">Company / Institution</span>
            <strong>{{ $contact->company ?? 'N/A' }}</strong>
          </div>
          <div class="col-md-6">
            <span class="text-muted small d-block">IP Address</span>
            <code>{{ $contact->ip_address ?? 'N/A' }}</code>
          </div>
          <div class="col-md-6">
            <span class="text-muted small d-block">Browser User Agent</span>
            <small class="text-muted">{{ Str::limit($contact->user_agent, 60) }}</small>
          </div>
        </div>

        <h6 class="text-uppercase small text-muted mb-2">Original Message Text</h6>
        <div class="border rounded p-3 bg-light mb-4" style="white-space: pre-line;">{{ $contact->message }}</div>

        <!-- Attachment Viewer -->
        @if($contact->attachmentMedia)
          <div class="card bg-light border-0 mb-4">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-check fs-2 text-primary me-3"></i>
                <div>
                  <h6 class="mb-0">Attachment Attachment File</h6>
                  <small class="text-muted">{{ $contact->attachmentMedia->original_name }}</small>
                </div>
              </div>
              <a href="{{ route('admin.media.download', $contact->attachmentMedia->id) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-download me-1"></i> Download
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Replies History -->
    @if($contact->replies->count() > 0)
      <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
          <h5 class="h6 mb-3">Admin Responses History</h5>
          <div class="vstack gap-3">
            @foreach($contact->replies as $reply)
              <div class="border rounded p-3 bg-light">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                  <span class="small text-muted">Sent by <strong>{{ $reply->user->name ?? 'Admin' }}</strong></span>
                  <span class="small text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="message-body" style="white-space: pre-line;">{{ $reply->message }}</div>
                @if($reply->attachmentMedia)
                  <div class="mt-2 pt-2 border-top small">
                    <i class="bi bi-paperclip"></i>
                    <a href="{{ route('admin.media.download', $reply->attachmentMedia->id) }}">{{ $reply->attachmentMedia->original_name }}</a>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <!-- Send Reply Panel -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h5 class="h6 mb-3">Compose Reply Email</h5>
        <form action="{{ route('admin.contacts.inquiries.reply', $contact->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <textarea name="message" class="form-control rich-editor" rows="6" placeholder="Write reply message body..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label small">Attach File Response (Optional)</label>
            <input type="file" name="attachment" class="form-control form-control-sm">
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Send Email Response</button>
        </form>
      </div>
    </div>

    <!-- Internal Notes Annotator -->
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h5 class="h6 mb-3">Internal Collaboration Notes</h5>
        
        <!-- Notes list -->
        <div class="vstack gap-2 mb-3">
          @forelse($contact->notes as $note)
            <div class="border rounded p-2 bg-light d-flex justify-content-between align-items-start">
              <div>
                <p class="mb-1 text-dark small" style="white-space: pre-line;">{{ $note->note }}</p>
                <small class="text-muted">By: {{ $note->user->name ?? 'Admin' }} | {{ $note->created_at->format('M d, Y H:i') }}</small>
              </div>
            </div>
          @empty
            <p class="text-center text-muted py-2 small">No internal notes logged.</p>
          @endforelse
        </div>

        <form action="{{ route('admin.contacts.inquiries.note', $contact->id) }}" method="POST">
          @csrf
          <div class="mb-2">
            <textarea name="note" class="form-control form-control-sm" rows="3" placeholder="Add custom collaboration notes..."></textarea>
          </div>
          <button type="submit" class="btn btn-outline-secondary btn-sm">Save Note</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Right Side Column: Controls & Event Timeline -->
  <div class="col-lg-4">
    <!-- Lead lifecycle configurations -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h6 mb-3">Lead Lifecycle Configurations</h4>

        <!-- Assign Owner -->
        <div class="mb-3">
          <label class="form-label small">Assigned Owner</label>
          <select id="assign_owner_select" class="form-select form-select-sm" data-url="{{ route('admin.contacts.inquiries.assign', $contact->id) }}">
            <option value="">Unassigned</option>
            @foreach($users as $usr)
              <option value="{{ $usr->id }}" {{ $contact->assigned_to == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Lead Status -->
        <div class="mb-3">
          <label class="form-label small">Lead Status</label>
          <select id="status_select" class="form-select form-select-sm" data-url="{{ route('admin.contacts.inquiries.status', $contact->id) }}">
            @foreach(\App\Enums\ContactStatus::cases() as $st)
              <option value="{{ $st->value }}" {{ $contact->status == $st ? 'selected' : '' }}>{{ ucfirst($st->value) }}</option>
            @endforeach
          </select>
        </div>

        <!-- Follow-up callback scheduler -->
        <div class="mb-3">
          <label class="form-label small">Callback Scheduled Date</label>
          <input type="date" id="follow_up_input" class="form-control form-control-sm" value="{{ $contact->follow_up_at ? $contact->follow_up_at->format('Y-m-d') : '' }}" data-url="{{ route('admin.contacts.inquiries.follow-up', $contact->id) }}">
        </div>

        <!-- Soft delete options -->
        <form action="{{ route('admin.contacts.inquiries.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger btn-sm w-100 mb-2">Move to Trash</button>
        </form>
        
        <a href="{{ route('admin.contacts.inquiries.index') }}" class="btn btn-outline-secondary btn-sm w-100">Back to Inbox</a>
      </div>
    </div>

    <!-- Activity timeline -->
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h5 class="h6 mb-3"> chronological Activity Timeline</h5>
        <div class="timeline-wrapper ms-2">
          @foreach($timeline as $event)
            <div class="d-flex gap-3 mb-3 border-start pb-2 ps-3 position-relative" style="margin-left: 5px;">
              <span class="position-absolute translate-middle bg-white text-{{ $event['color'] }}" style="left: -1px; top: 12px;">
                <i class="bi {{ $event['icon'] }} fs-5"></i>
              </span>
              <div>
                <strong class="small d-block">{{ $event['label'] }}</strong>
                <p class="mb-0 text-muted small">{{ $event['details'] }}</p>
                <small class="text-muted block font-monospace" style="font-size: 0.75rem;">{{ $event['time']->format('Y-m-d H:i') }}</small>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Lead assignment ajax handler
    const assignOwnerSelect = document.getElementById('assign_owner_select');
    assignOwnerSelect?.addEventListener('change', async () => {
      const response = await fetch(assignOwnerSelect.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ assigned_to: assignOwnerSelect.value })
      });
      if (response.ok) {
        window.location.reload();
      } else {
        alert('Failed to update lead assignment owner.');
      }
    });

    // Status change ajax handler
    const statusSelect = document.getElementById('status_select');
    statusSelect?.addEventListener('change', async () => {
      const response = await fetch(statusSelect.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ status: statusSelect.value })
      });
      if (response.ok) {
        window.location.reload();
      } else {
        alert('Failed to update status.');
      }
    });

    // Follow-up date picker ajax handler
    const followUpInput = document.getElementById('follow_up_input');
    followUpInput?.addEventListener('change', async () => {
      const response = await fetch(followUpInput.dataset.url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ follow_up_at: followUpInput.value })
      });
      if (response.ok) {
        window.location.reload();
      } else {
        alert('Failed to update follow up callback date.');
      }
    });
  });
</script>
@endsection
