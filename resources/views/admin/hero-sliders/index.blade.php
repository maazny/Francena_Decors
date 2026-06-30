@extends('admin.layouts.app')

@section('title', 'Hero Sliders')
@section('page-title', 'Hero Slider CMS')
@section('page-description', 'Manage dynamic homepage hero slides, scheduling, ordering, status, and media.')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="h5 mb-1">Hero Slides</h2>
        <p class="text-muted mb-0">Create unlimited slides and control their frontend publishing schedule.</p>
      </div>
      <a href="{{ route('admin.hero-sliders.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>
        Add Slide
      </a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="GET" action="{{ route('admin.hero-sliders.index') }}" class="row g-3 align-items-end mb-4">
      <div class="col-md-4">
        <label class="form-label" for="search">Search</label>
        <input id="search" name="search" type="search" class="form-control" value="{{ $search }}" placeholder="Search title, subtitle, badge">
      </div>
      <div class="col-md-3">
        <label class="form-label" for="status">Status</label>
        <select id="status" name="status" class="form-select">
          <option value="">All statuses</option>
          <option value="1" {{ (string) $status === '1' ? 'selected' : '' }}>Active</option>
          <option value="0" {{ (string) $status === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label" for="sort">Sort</label>
        <select id="sort" name="sort" class="form-select">
          @foreach(['display_order' => 'Display Order', 'title' => 'Title', 'status' => 'Status', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created'] as $value => $label)
            <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label" for="direction">Direction</label>
        <select id="direction" name="direction" class="form-select">
          <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>Ascending</option>
          <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Descending</option>
        </select>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary">
          <i class="fa-solid fa-magnifying-glass me-1"></i>
          Filter
        </button>
        <a href="{{ route('admin.hero-sliders.index') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>

    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
      <form id="bulk-action-form" method="POST" action="{{ route('admin.hero-sliders.bulk') }}" class="d-flex gap-2">
        @csrf
        <div id="bulk-selected-inputs"></div>
        <select name="action" class="form-select form-select-sm" required>
          <option value="">Bulk Action</option>
          <option value="activate">Activate</option>
          <option value="deactivate">Deactivate</option>
          <option value="delete">Delete</option>
          <option value="restore">Restore</option>
        </select>
        <button type="submit" class="btn btn-outline-dark btn-sm">Apply</button>
      </form>

      <form id="reorder-form" method="POST" action="{{ route('admin.hero-sliders.reorder') }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary btn-sm">
          <i class="fa-solid fa-arrow-down-wide-short me-1"></i>
          Save Ordering
        </button>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th style="width: 42px;"><input id="select-all-slides" type="checkbox" class="form-check-input"></th>
            <th>Slide</th>
            <th>Preview</th>
            <th style="width: 120px;">Order</th>
            <th>Schedule</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody id="hero-slider-sortable">
          @forelse($heroSliders as $heroSlider)
            <tr draggable="true" data-slide-row>
              <td>
                <input type="checkbox" class="form-check-input hero-slide-checkbox" value="{{ $heroSlider->id }}">
                <input form="reorder-form" type="hidden" name="orders[{{ $loop->index }}][id]" value="{{ $heroSlider->id }}">
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-grip-vertical text-muted" title="Drag to reorder"></i>
                  <div>
                    <div class="fw-semibold">{{ $heroSlider->title }}</div>
                    <div class="small text-muted">{{ $heroSlider->subtitle ?: 'No subtitle' }}</div>
                    @if($heroSlider->trashed())
                      <span class="badge bg-secondary mt-1">Deleted</span>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                @if($heroSlider->desktopImage)
                  <img src="{{ thumbnail_url($heroSlider->desktopImage) }}" alt="{{ $heroSlider->title }} preview" class="rounded border" style="width: 96px; height: 56px; object-fit: cover;">
                @else
                  <span class="text-muted small">Video only</span>
                @endif
              </td>
              <td>
                <input form="reorder-form" name="orders[{{ $loop->index }}][display_order]" type="number" class="form-control form-control-sm hero-order-input" value="{{ $heroSlider->display_order }}" min="0" max="9999">
              </td>
              <td class="small">
                <div>Start: {{ $heroSlider->start_date?->format('M d, Y H:i') ?: 'Always' }}</div>
                <div>End: {{ $heroSlider->end_date?->format('M d, Y H:i') ?: 'Never' }}</div>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.hero-sliders.toggle-status', $heroSlider) }}">
                  @csrf
                  <button type="submit" class="btn btn-sm {{ $heroSlider->status ? 'btn-success' : 'btn-outline-secondary' }}" {{ $heroSlider->trashed() ? 'disabled' : '' }}>
                    {{ $heroSlider->status ? 'Active' : 'Inactive' }}
                  </button>
                </form>
              </td>
              <td class="text-end">
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#previewSlide{{ $heroSlider->id }}">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  @if($heroSlider->trashed())
                    <form method="POST" action="{{ route('admin.hero-sliders.restore', $heroSlider->id) }}">
                      @csrf
                      <button type="submit" class="btn btn-outline-success"><i class="fa-solid fa-rotate-left"></i></button>
                    </form>
                  @else
                    <a href="{{ route('admin.hero-sliders.edit', $heroSlider) }}" class="btn btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.hero-sliders.duplicate', $heroSlider) }}">
                      @csrf
                      <button type="submit" class="btn btn-outline-secondary"><i class="fa-solid fa-copy"></i></button>
                    </form>
                    <form method="POST" action="{{ route('admin.hero-sliders.destroy', $heroSlider) }}" onsubmit="return confirm('Delete this hero slide?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>

            <div class="modal fade" id="previewSlide{{ $heroSlider->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title h5">{{ $heroSlider->title }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="hero-admin-preview-frame rounded overflow-hidden" style="background-image: linear-gradient({{ $heroSlider->overlay_rgba }}, {{ $heroSlider->overlay_rgba }}), url('{{ image_url($heroSlider->desktopImage) }}');">
                      <div>
                        @if($heroSlider->badge_text)<span class="badge mb-3" style="background: {{ $heroSlider->badge_color ?: '#d4af5f' }};">{{ $heroSlider->badge_text }}</span>@endif
                        <h2>{{ $heroSlider->title }}</h2>
                        @if($heroSlider->description)<p>{{ $heroSlider->description }}</p>@endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-5">No hero slides found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{ $heroSliders->links() }}
  </div>
</div>

<script>
  const selectAll = document.getElementById('select-all-slides');
  const checkboxes = document.querySelectorAll('.hero-slide-checkbox');
  const bulkForm = document.getElementById('bulk-action-form');
  const bulkSelectedInputs = document.getElementById('bulk-selected-inputs');
  const rows = document.querySelectorAll('[data-slide-row]');

  selectAll?.addEventListener('change', () => {
    checkboxes.forEach((checkbox) => checkbox.checked = selectAll.checked);
  });

  bulkForm?.addEventListener('submit', (event) => {
    bulkSelectedInputs.innerHTML = '';
    const selected = Array.from(checkboxes).filter((checkbox) => checkbox.checked);

    if (! selected.length) {
      event.preventDefault();
      alert('Select at least one slide.');
      return;
    }

    selected.forEach((checkbox) => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'selected[]';
      input.value = checkbox.value;
      bulkSelectedInputs.appendChild(input);
    });
  });

  rows.forEach((row) => {
    row.addEventListener('dragstart', () => row.classList.add('table-active'));
    row.addEventListener('dragend', () => row.classList.remove('table-active'));
    row.addEventListener('dragover', (event) => {
      event.preventDefault();
      const dragging = document.querySelector('.table-active');
      if (dragging && dragging !== row) {
        row.parentNode.insertBefore(dragging, row.nextSibling);
        document.querySelectorAll('.hero-order-input').forEach((input, index) => input.value = index + 1);
      }
    });
  });
</script>
@endsection
