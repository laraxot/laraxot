{{-- Bootstrap Italia Select Component --}}

@php
$selectId = $id ?? 'select-' . uniqid();
@endphp

<div class="form-group select-italia">
    @if(isset($label))
    <label for="{{ $selectId }}" class="form-label">{{ $label }}</label>
    @endif
    <select id="{{ $selectId }}"
            name="{{ $name }}"
            class="form-select form-select-italia"
            @if($required ?? false) required @endif
            @if($disabled ?? false) disabled @endif>
        @if(isset($placeholder))
        <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        @foreach($options ?? [] as $value => $optionLabel)
        <option value="{{ $value }}">{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if(isset($hint))
    <small class="form-text text-muted">{{ $hint }}</small>
    @endif
</div>
