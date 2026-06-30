@php($image = $image ?? true)
<div class="mb-3">
  <label class="form-label">{{ $label }}</label>
  <input id="{{ $field }}" name="{{ $field }}" type="hidden" value="{{ old($field, $aboutSection->$field) }}">
  <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Select from Media Library</button>
  @if($image)
    <img id="{{ $field }}_preview" src="{{ $media ? image_url($media) : '' }}" alt="{{ $label }} preview" class="img-fluid border rounded mt-2 {{ $media ? '' : 'd-none' }}" style="height: 100px; width: 100%; object-fit: cover;">
  @else
    <div id="{{ $field }}_preview" class="small text-muted mt-2">{{ $media ? $media->original_name : 'No media selected' }}</div>
  @endif
</div>
