{{-- Sezione "luogo": campo di ricerca con suggerimenti (`data-bs-autocomplete`, JSON a virgolette doppie) e link "Usa la tua posizione". --}}
@php
    $suggestions = json_encode($section['suggestions'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $suggestions = str_replace("'", '&#039;', htmlspecialchars((string) $suggestions, ENT_NOQUOTES));
@endphp
<div class="cmp-input-autocomplete">
    <div class="form-group bg-white p-3 mb-0 mt-3">
        <label class="label-input d-none mb-2" for="{{ $section['field_id'] ?? '' }}">{{ $section['placeholder'] ?? '' }}</label>
        <input type="search" class="autocomplete" placeholder="{{ $section['placeholder'] ?? '' }}" id="{{ $section['field_id'] ?? '' }}" name="{{ $section['field_id'] ?? '' }}" required data-bs-autocomplete='{!! $suggestions !!}'>
        <div class="link-wrapper mt-3">
            <a class="list-item active icon-left" href="#">
                <span class="list-item-title-icon-wrapper">
                    <svg class="icon icon-sm icon-primary mb-1" aria-hidden="true"><use href="{{ $icon('it-map-marker') }}"></use></svg>
                    <span class="list-item-title t-primary">{{ $section['location_label'] ?? '' }}</span>
                </span>
            </a>
        </div>
    </div>
</div>
