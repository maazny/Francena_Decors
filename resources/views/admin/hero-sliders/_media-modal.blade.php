@php
  $isVideo = $isVideo ?? false;
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
                class="btn btn-light border w-100 h-100 text-start media-select-card"
                data-media-id="{{ $media->id }}"
                data-media-url="{{ media_url($media) }}"
                data-media-preview="{{ $isVideo ? $media->original_name : thumbnail_url($media) }}"
                data-target-input="{{ $targetInput }}"
                data-bs-dismiss="modal"
              >
                @if($isVideo)
                  <div class="ratio ratio-16x9 bg-dark rounded d-flex align-items-center justify-content-center text-white">
                    <i class="fa-solid fa-video fa-2x"></i>
                  </div>
                @else
                  <img src="{{ thumbnail_url($media) }}" alt="{{ $media->alt_text ?: $media->original_name }}" class="img-fluid rounded mb-2" style="height: 120px; width: 100%; object-fit: cover;">
                @endif
                <div class="small fw-semibold text-truncate">{{ $media->title ?: $media->original_name }}</div>
                <div class="small text-muted">{{ $media->human_size }}</div>
              </button>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info mb-0">No media items found in the Media Library.</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('#{{ $modalId }} .media-select-card').forEach((button) => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.targetInput);
      const preview = document.getElementById(`${button.dataset.targetInput}_preview`);

      if (input) {
        input.value = button.dataset.mediaId;
      }

      if (preview) {
        if (preview.tagName === 'IMG') {
          preview.src = button.dataset.mediaPreview;
          preview.classList.remove('d-none');

          if (button.dataset.targetInput === 'desktop_image_id' && previewFrame) {
            previewFrame.dataset.image = button.dataset.mediaUrl;
            updateOverlayPreview();
          }
        } else {
          preview.textContent = button.dataset.mediaPreview;
        }
      }
    });
  });
</script>
