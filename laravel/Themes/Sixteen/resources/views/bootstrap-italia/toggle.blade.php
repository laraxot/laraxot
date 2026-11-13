{{-- Bootstrap Italia Toggle Component --}}

@php
$toggleId = $id ?? 'toggle-' . uniqid();
@endphp

<div class="form-check form-check-toggle toggles">
    <input class="form-check-input"
           type="checkbox"
           id="{{ $toggleId }}"
           name="{{ $name }}"
           @if($checked ?? false) checked @endif>
    <label class="form-check-label" for="{{ $toggleId }}">
        <span class="lever"></span>
        {{ $label }}
    </label>
</div>
