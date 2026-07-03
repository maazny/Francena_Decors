@extends('admin.layouts.app')

@section('title', 'Contact Inbox & Lead Manager')
@section('page-title', 'Contact Inbox & Lead Manager')
@section('page-description', 'Manage client requests, assignments, follow-ups, and lead status states.')

@section('content')
<!-- Dashboard Summary Cards -->
<div class="row g-3 mb-4">
  <!-- Total Contacts -->
  <div class="col-md-3">
    <div class="card border-0 bg-dark text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-muted">Total Inquiries</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::withTrashed()->count() }}</h3>
        </div>
        <i class="bi bi-chat-left-dots fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-muted small">Total client submissions</div>
    </div>
  </div>
  <!-- Unread Messages -->
  <div class="col-md-3">
    <div class="card border-0 bg-danger text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">Unread Messages</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('is_read', false)->count() }}</h3>
        </div>
        <i class="bi bi-envelope-exclamation fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Requires attention</div>
    </div>
  </div>
  <!-- New Leads -->
  <div class="col-md-3">
    <div class="card border-0 bg-primary text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">New Leads</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('status', \App\Enums\ContactStatus::NEW)->count() }}</h3>
        </div>
        <i class="bi bi-person-plus fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Waiting review</div>
    </div>
  </div>
  <!-- Follow-up Today -->
  <div class="col-md-3">
    <div class="card border-0 bg-warning text-dark p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-dark-50">Follow-up Today</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::whereDate('follow_up_at', \Carbon\Carbon::today())->count() }}</h3>
        </div>
        <i class="bi bi-calendar-event fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-dark-50 small">Scheduled callbacks</div>
    </div>
  </div>
  <!-- Converted Leads -->
  <div class="col-md-3">
    <div class="card border-0 bg-success text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">Converted Leads</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('status', \App\Enums\ContactStatus::CONVERTED)->count() }}</h3>
        </div>
        <i class="bi bi-award fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Converted to sales</div>
    </div>
  </div>
  <!-- Open Leads -->
  <div class="col-md-3">
    <div class="card border-0 bg-info text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">Open/Active Leads</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('status', \App\Enums\ContactStatus::OPEN)->count() }}</h3>
        </div>
        <i class="bi bi-folder2-open fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Active discussion threads</div>
    </div>
  </div>
  <!-- Closed Leads -->
  <div class="col-md-3">
    <div class="card border-0 bg-secondary text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">Closed Leads</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('status', \App\Enums\ContactStatus::CLOSED)->count() }}</h3>
        </div>
        <i class="bi bi-archive fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Resolved tickets</div>
    </div>
  </div>
  <!-- Spam -->
  <div class="col-md-3">
    <div class="card border-0 bg-dark text-white p-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-uppercase small mb-1 text-white-50">Spam Messages</h6>
          <h3 class="mb-0">{{ \App\Models\Contact::where('status', \App\Enums\ContactStatus::SPAM)->count() }}</h3>
        </div>
        <i class="bi bi-trash fs-3 opacity-50"></i>
      </div>
      <div class="mt-2 text-white-50 small">Flagged unwanted inbox items</div>
    </div>
  </div>
</div>

<!-- Filters Panel -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-4">
    <h5 class="h6 mb-3"><i class="bi bi-funnel me-1"></i> Advanced Filters</h5>
    <form method="GET" action="{{ route('admin.contacts.inquiries.index') }}" class="row g-3">
      <!-- Search Input -->
      <div class="col-md-4">
        <label class="form-label small">Keyword Search</label>
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, phone, message..." value="{{ request('search') }}">
      </div>
      <!-- Category -->
      <div class="col-md-2">
        <label class="form-label small">Category</label>
        <select name="category_id" class="form-select form-select-sm">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Status -->
      <div class="col-md-2">
        <label class="form-label small">Status</label>
        <select name="status" class="form-select form-select-sm">
          <option value="">All Statuses</option>
          @foreach(\App\Enums\ContactStatus::cases() as $statusOption)
            <option value="{{ $statusOption->value }}" {{ request('status') == $statusOption->value ? 'selected' : '' }}>
              {{ ucfirst($statusOption->value) }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Priority -->
      <div class="col-md-2">
        <label class="form-label small">Priority</label>
        <select name="priority" class="form-select form-select-sm">
          <option value="">All Priorities</option>
          @foreach(\App\Enums\ContactPriority::cases() as $priorityOption)
            <option value="{{ $priorityOption->value }}" {{ request('priority') == $priorityOption->value ? 'selected' : '' }}>
              {{ ucfirst($priorityOption->value) }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Assigned To -->
      <div class="col-md-2">
        <label class="form-label small">Assigned Owner</label>
        <select name="assigned_to" class="form-select form-select-sm">
          <option value="">All Assignees</option>
          @foreach($users as $userOption)
            <option value="{{ $userOption->id }}" {{ request('assigned_to') == $userOption->id ? 'selected' : '' }}>
              {{ $userOption->name }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Source -->
      <div class="col-md-2">
        <label class="form-label small">Source</label>
        <select name="source" class="form-select form-select-sm">
          <option value="">All Sources</option>
          @foreach(\App\Enums\ContactSource::cases() as $srcOption)
            <option value="{{ $srcOption->value }}" {{ request('source') == $srcOption->value ? 'selected' : '' }}>
              {{ ucfirst($srcOption->value) }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- Read/Unread -->
      <div class="col-md-2">
        <label class="form-label small">Read State</label>
        <select name="is_read" class="form-select form-select-sm">
          <option value="">All</option>
          <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Read</option>
          <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Unread</option>
        </select>
      </div>
      <!-- Date range -->
      <div class="col-md-2">
        <label class="form-label small">Start Date</label>
        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label small">End Date</label>
        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
      </div>
      <!-- Actions buttons -->
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
        <a href="{{ route('admin.contacts.inquiries.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">Reset</a>
      </div>
    </form>
  </div>
</div>

<!-- Inbox List Table -->
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="h5 mb-0">Client Inquiries Inbox</h3>
      <a href="{{ route('admin.contacts.inquiries.create') }}" class="btn btn-primary btn-sm">Log Phone Call / Manual Lead</a>
    </div>

    <!-- Bulk Actions Form -->
    <form method="POST" action="" id="bulk-inquiry-form">
      @csrf
      <input type="hidden" name="action" id="bulk-inquiry-action" value="">
      <input type="hidden" name="status" id="bulk-inquiry-status" value="">
      <input type="hidden" name="priority" id="bulk-inquiry-priority" value="">
      <input type="hidden" name="assigned_to" id="bulk-inquiry-assigned" value="">

      <div class="table-responsive">
        <table class="table align-middle table-hover">
          <thead class="table-light">
            <tr>
              <th width="40"><input type="checkbox" id="select-all-inquiry"></th>
              <th>Client Contact</th>
              <th>Subject & Category</th>
              <th>Source</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Assigned Owner</th>
              <th>Callback / Follow-up</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($contacts as $contact)
              <tr class="{{ ! $contact->is_read ? 'table-warning fw-bold' : '' }}">
                <td><input type="checkbox" name="ids[]" value="{{ $contact->id }}" class="bulk-inquiry-checkbox"></td>
                <td>
                  <strong>{{ $contact->name }}</strong>
                  <div class="text-muted small">
                    {{ $contact->email }} <br>
                    {{ $contact->phone ?? 'No Phone' }}
                    @if($contact->company)
                      | <em>{{ $contact->company }}</em>
                    @endif
                  </div>
                  @if($contact->trashed())
                    <span class="badge bg-danger">Deleted</span>
                  @endif
                </td>
                <td>
                  <strong>{{ $contact->subject }}</strong>
                  <div class="text-muted small">Category: {{ $contact->category->name ?? 'Uncategorized' }}</div>
                </td>
                <td>
                  <span class="badge bg-light text-dark text-capitalize">{{ $contact->source->value ?? $contact->source }}</span>
                </td>
                <td>
                  <span class="badge bg-{{ 
                    match($contact->priority->value ?? $contact->priority) {
                      'low' => 'secondary',
                      'medium' => 'info',
                      'high' => 'warning',
                      'urgent' => 'danger',
                      default => 'secondary'
                    }
                  }}">
                    {{ ucfirst($contact->priority->value ?? $contact->priority) }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-{{
                    match($contact->status->value ?? $contact->status) {
                      'new' => 'primary',
                      'open' => 'info',
                      'contacted' => 'success',
                      'follow_up' => 'warning text-dark',
                      'converted' => 'success',
                      'closed' => 'dark',
                      'spam' => 'secondary',
                      default => 'secondary'
                    }
                  }}">
                    {{ ucfirst($contact->status->value ?? $contact->status) }}
                  </span>
                </td>
                <td>
                  {{ $contact->user->name ?? 'Unassigned' }}
                </td>
                <td>
                  {{ $contact->follow_up_at ? $contact->follow_up_at->format('M d, Y') : 'N/A' }}
                </td>
                <td class="text-end">
                  @if($contact->trashed())
                    <form action="{{ route('admin.contacts.inquiries.restore', $contact->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                    </form>
                  @else
                    <a href="{{ route('admin.contacts.inquiries.show', $contact) }}" class="btn btn-sm btn-outline-primary">Open Thread</a>
                    <form action="{{ route('admin.contacts.inquiries.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center py-4 text-muted">No inquiries found in this inbox.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Bulk actions buttons bar -->
      <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <button type="button" class="btn btn-outline-danger btn-sm bulk-action-btn" data-action="delete">Delete Selected</button>
          
          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Assign Owner</button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item bulk-assign-item" href="#" data-user="">Unassign</a></li>
              @foreach($users as $usr)
                <li><a class="dropdown-item bulk-assign-item" href="#" data-user="{{ $usr->id }}">{{ $usr->name }}</a></li>
              @endforeach
            </ul>
          </div>

          <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">Change Status</button>
            <ul class="dropdown-menu">
              @foreach(\App\Enums\ContactStatus::cases() as $st)
                <li><a class="dropdown-item bulk-status-item" href="#" data-status="{{ $st->value }}">{{ ucfirst($st->value) }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
        <div>
          {{ $contacts->links() }}
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all-inquiry');
    const checkboxes = document.querySelectorAll('.bulk-inquiry-checkbox');
    const bulkForm = document.getElementById('bulk-inquiry-form');
    const bulkAction = document.getElementById('bulk-inquiry-action');
    const bulkStatus = document.getElementById('bulk-inquiry-status');
    const bulkAssigned = document.getElementById('bulk-inquiry-assigned');

    selectAll?.addEventListener('change', function () {
      checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.querySelectorAll('.bulk-action-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const checkedCount = document.querySelectorAll('.bulk-inquiry-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one inquiry.');
          return;
        }

        const action = btn.dataset.action;
        if (action === 'delete' && !confirm('Are you sure you want to delete the selected inquiries?')) {
          return;
        }

        bulkAction.value = action;
        bulkForm.action = "{{ route('admin.contacts.inquiries.bulk-delete') }}";
        bulkForm.submit();
      });
    });

    document.querySelectorAll('.bulk-status-item').forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('.bulk-inquiry-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one inquiry.');
          return;
        }

        bulkAction.value = 'status';
        bulkStatus.value = item.dataset.status;
        bulkForm.action = "{{ route('admin.contacts.inquiries.bulk-status') }}";
        bulkForm.submit();
      });
    });

    document.querySelectorAll('.bulk-assign-item').forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const checkedCount = document.querySelectorAll('.bulk-inquiry-checkbox:checked').length;
        if (checkedCount === 0) {
          alert('Please select at least one inquiry.');
          return;
        }

        bulkAction.value = 'assign';
        bulkAssigned.value = item.dataset.user;
        bulkForm.action = "{{ route('admin.contacts.inquiries.bulk-assign') }}";
        bulkForm.submit();
      });
    });
  });
</script>
@endsection
