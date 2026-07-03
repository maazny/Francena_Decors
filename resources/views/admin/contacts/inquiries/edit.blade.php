@extends('admin.layouts.app')

@section('title', 'Edit Inquiry')
@section('page-title', 'Edit Inquiry')
@section('page-description', 'Modify the client details, original message body, or metadata properties.')

@section('content')
<form action="{{ route('admin.contacts.inquiries.update', $contact) }}" method="POST" enctype="multipart/form-data" class="row g-4">
  @csrf
  @method('PUT')

  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Client & Message Information</h4>

        <div class="mb-3">
          <label class="form-label">Client Name</label>
          <input type="text" name="name" class="form-control" value="{{ $contact->name }}" required>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ $contact->email }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ $contact->phone }}">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Company Name</label>
          <input type="text" name="company" class="form-control" value="{{ $contact->company }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input type="text" name="subject" class="form-control" value="{{ $contact->subject }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Original Message Text</label>
          <textarea name="message" class="form-control" rows="8" required>{{ $contact->message }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Replace Attachment File</label>
          <input type="file" name="attachment" class="form-control">
          @if($contact->attachmentMedia)
            <small class="text-muted d-block mt-1">Current file: <strong>{{ $contact->attachmentMedia->original_name }}</strong></small>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Routing & Status Settings</h4>

        <div class="mb-3">
          <label class="form-label">Inquiry Category</label>
          <select name="contact_category_id" class="form-select">
            <option value="">Select Category</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ $contact->contact_category_id == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Lead Priority</label>
          <select name="priority" class="form-select">
            @foreach(\App\Enums\ContactPriority::cases() as $pr)
              <option value="{{ $pr->value }}" {{ $contact->priority == $pr ? 'selected' : '' }}>
                {{ ucfirst($pr->value) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Lead Status</label>
          <select name="status" class="form-select">
            @foreach(\App\Enums\ContactStatus::cases() as $st)
              <option value="{{ $st->value }}" {{ $contact->status == $st ? 'selected' : '' }}>
                {{ ucfirst($st->value) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Assigned Owner</label>
          <select name="assigned_to" class="form-select">
            <option value="">Unassigned</option>
            @foreach($users as $usr)
              <option value="{{ $usr->id }}" {{ $contact->assigned_to == $usr->id ? 'selected' : '' }}>
                {{ $usr->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label">Callback / Follow-up Date</label>
          <input type="date" name="follow_up_at" class="form-control" value="{{ $contact->follow_up_at ? $contact->follow_up_at->format('Y-m-d') : '' }}">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Save Inquiry Updates</button>
        <a href="{{ route('admin.contacts.inquiries.show', $contact->id) }}" class="btn btn-outline-secondary w-100">Cancel</a>
      </div>
    </div>
  </div>
</form>
@endsection
