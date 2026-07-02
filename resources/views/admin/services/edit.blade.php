@extends('admin.layouts.app')

@section('title', 'Edit Service')
@section('page-title', 'Edit Service')
@section('page-description', 'Update this service and manage related FAQ content.')

@section('content')
<div class="card shadow-sm mb-4">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.services.update', $service) }}" novalidate>
      @csrf
      @method('PUT')
      @include('admin.services._form')
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Service</button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Service FAQ Items</h2>
        <p class="text-muted mb-0">Create and manage FAQ entries for this service.</p>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.services.faqs.store', $service) }}" class="row g-4 mb-4">
      @csrf
      <div class="col-md-6">
        <label class="form-label">Question</label>
        <input type="text" name="question" class="form-control" value="{{ old('question') }}" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Display Order</label>
        <input type="number" name="display_order" class="form-control" value="{{ old('display_order', 0) }}">
      </div>
      <div class="col-12">
        <label class="form-label">Answer</label>
        <textarea name="answer" class="form-control" rows="4">{{ old('answer') }}</textarea>
      </div>
      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="status" value="1" checked>
          <label class="form-check-label">Active</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Add FAQ</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Question</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($service->faqs as $faq)
            <tr>
              <td>{{ $faq->question }}</td>
              <td><span class="badge bg-{{ $faq->status ? 'success' : 'secondary' }}">{{ $faq->status ? 'Active' : 'Inactive' }}</span></td>
              <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#faqEdit{{ $faq->id }}">Edit</button>
                <form action="{{ route('admin.service-faqs.toggle-status', $faq) }}" method="POST" class="d-inline">
                  @csrf
                  <button class="btn btn-sm btn-outline-secondary">{{ $faq->status ? 'Deactivate' : 'Activate' }}</button>
                </form>
                <form action="{{ route('admin.service-faqs.destroy', $faq) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
            <tr class="collapse" id="faqEdit{{ $faq->id }}">
              <td colspan="3">
                <form method="POST" action="{{ route('admin.service-faqs.update', $faq) }}" class="row g-3">
                  @csrf
                  @method('PUT')
                  <div class="col-md-6">
                    <label class="form-label">Question</label>
                    <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $faq->display_order) }}">
                  </div>
                  <div class="col-12">
                    <label class="form-label">Answer</label>
                    <textarea name="answer" class="form-control" rows="3">{{ old('answer', $faq->answer) }}</textarea>
                  </div>
                  <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">Save FAQ</button>
                  </div>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">No FAQ items have been added yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
