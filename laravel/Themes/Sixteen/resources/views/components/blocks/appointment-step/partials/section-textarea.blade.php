{{-- Sezione "textarea" (JSON: textarea_id, label, rows, hint). --}}
<div class="cmp-text-area p-0">
    <div class="form-group">
        <label for="{{ $section['textarea_id'] ?? '' }}" class="visually-hidden">{{ $section['label'] ?? '' }}</label>
        <textarea class="text-area" id="{{ $section['textarea_id'] ?? '' }}" rows="{{ $section['rows'] ?? 2 }}"></textarea>
        <span class="label">{{ $section['hint'] ?? '' }}</span>
    </div>
</div>
