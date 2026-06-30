@php
  $isImage = $isImage ?? false;
@endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title h5">{{ $title }}</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          @forelse($mediaItems as $media)
            <div class="col-md-4 col-xl-3">
              <button
                type="button"
                class="btn btn-light border w-100 h-100 text-start js-media-picker"
                data-target-input="{{ $targetInput }}"
                data-preview-target="{{ $previewTarget ?? $targetInput.'_preview' }}"
                data-media-id="{{ $media->id }}"
                data-media-url="{{ media_url($media) }}"
                data-media-label="{{ $media->title ?: $media->original_name }}"
                data-bs-dismiss="modal"
              >
                @if($isImage)
                  <img src="{{ thumbnail_url($media) }}" alt="{{ $media->alt_text ?: $media->original_name }}" class="img-fluid rounded mb-2" style="height: 120px; width: 100%; object-fit: cover;">
                @else
                  <div class="ratio ratio-16x9 bg-dark rounded d-flex align-items-center justify-content-center text-white mb-2">
                    <i class="fa-solid fa-file fa-2x"></i>
                  </div>
                @endif
                <div class="small fw-semibold text-truncate">{{ $media->title ?: $media->original_name }}</div>
                <div class="small text-muted">{{ $media->human_size }}</div>
              </button>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info mb-0">No matching media found.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

@once
  <script>
    document.addEventListener('click', (event) => {
      const picker = event.target.closest('.js-media-picker');

      if (! picker) {
        return;
      }

      const input = document.getElementById(picker.dataset.targetInput);
      const preview = document.getElementById(picker.dataset.previewTarget);

      if (input) {
        input.value = picker.dataset.mediaId;
      }

      if (! preview) {
        return;
      }

      if (preview.tagName === 'IMG') {
        preview.src = picker.dataset.mediaUrl;
        preview.classList.remove('d-none');
      } else {
        preview.textContent = picker.dataset.mediaLabel;
      }
    });
  </script>
@endonce
