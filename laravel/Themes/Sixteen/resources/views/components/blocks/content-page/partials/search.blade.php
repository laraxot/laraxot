{{--
    Campo di ricerca `cmp-input-search` del riferimento (senza comportamento: l'autocomplete del riferimento ha elenco vuoto).
    Parametro: $search = {id, name, label, placeholder, button_id, button, spacing?, result?, filter?} (result = riga di esito sotto il campo).
    `filter` = accordion_id di una sezione `accordion` con `filterable`: mentre si scrive l'elenco si filtra (evento `list-filter`, Alpine, nessun CSS).
--}}
<div class="cmp-input-search"@if (filled($search['filter'] ?? null)) x-data x-on:input.debounce.150ms="$dispatch('list-filter', { target: '{{ $search['filter'] }}', q: $event.target.value })" x-on:click="$event.target.closest('button') && $dispatch('list-filter', { target: '{{ $search['filter'] }}', q: $root.querySelector('input').value })"@endif>
    <div class="{{ $cx('form-group autocomplete-wrapper', $search['spacing'] ?? null) }}">
        <div class="input-group">
            <label for="{{ $search['id'] ?? '' }}" class="visually-hidden">{{ $search['label'] ?? '' }}</label>
            <input type="search" class="autocomplete form-control" placeholder="{{ $search['placeholder'] ?? '' }}" id="{{ $search['id'] ?? '' }}" name="{{ $search['name'] ?? '' }}" data-bs-autocomplete="[]">
            <div class="input-group-append"><button class="btn btn-primary" style="background-color:#007a52 !important;color:#fff !important;border-color:#007a52 !important" type="button" id="{{ $search['button_id'] ?? '' }}">{{ $search['button'] ?? '' }}</button></div>
            <span class="autocomplete-icon" aria-hidden="true"><svg class="icon icon-sm icon-primary"><use href="{{ $icon('it-search') }}"></use></svg></span>
        </div>
        @if (filled($search['result'] ?? null))
            <p id="autocomplete-label" class="u-grey-light text-paragraph-card mt-2 mb-30 mt-lg-3 mb-lg-40">{{ $search['result'] }}</p>
        @endif
    </div>
</div>
