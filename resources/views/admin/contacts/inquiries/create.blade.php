@extends('admin.layouts.app')

@section('title', 'Log New Inquiry')
@section('page-title', 'Log New Inquiry')
@section('page-description', 'Manually record phone calls, walk-in leads, or custom customer inquiries.')

@section('content')
<form action="{{ route('admin.contacts.inquiries.store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
  @csrf

  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="h5 mb-3">Client & Message Information</h4>

        <div class="mb-3">
          <label class="form-label">Client Name</label>
          <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="john@example.com">
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="+123456789">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Company Name (Optional)</label>
          <input type="text" name="company" class="form-control" placeholder="Doe Enterprises">
        </div>

        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input type="text" name="subject" class="form-control" required placeholder="Inquiry topic summary">
        </div>

        <div class="mb-3">
          <label class="form-label">Message Details / Log Description</label>
          <textarea name="message" class="form-control" rows="8" required placeholder="Type the customer requests detail or call minutes..."></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Upload File Attachment</label>
          <input type="file" name="attachment" class="form-control">
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
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Lead Priority</label>
          <select name="priority" class="form-select">
            @foreach(\App\Enums\ContactPriority::cases() as $pr)
              <option value="{{ $pr->value }}" {{ $pr->value == 'medium' ? 'selected' : '' }}>{{ ucfirst($pr->value) }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Lead Status</label>
          <select name="status" class="form-select">
            @foreach(\App\Enums\ContactStatus::cases() as $st)
              <option value="{{ $st->value }}" {{ $st->value == 'new' ? 'selected' : '' }}>{{ ucfirst($st->value) }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Assigned Owner</label>
          <select name="assigned_to" class="form-select">
            <option value="">Unassigned</option>
            @foreach($users as $usr)
              <option value="{{ $usr->id }}">{{ $usr->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-4">
          <label class="form-label">Callback / Follow-up Date</label>
          <input type="date" name="follow_up_at" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Save Inquiry Log</button>
        <a href="{{ route('admin.contacts.inquiries.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
      </div>
    </div>
  </div>
</form>
@endsection
