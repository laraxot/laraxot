@props(['data' => []])

@php
    $placeholder = $data['placeholder'] ?? 'Cerca';
    $buttonLabel = $data['buttonLabel'] ?? 'Invio';
    $action = $data['action'] ?? '#';
    $inputId = $data['inputId'] ?? 'search-faq';
@endphp

<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2 px-sm-3 mt-2">
        <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
                <div class="input-group">
                    <label for="{{ $inputId }}" class="visually-hidden">Cerca nel sito</label>
                    <input type="search" id="{{ $inputId }}" name="autocomplete-three" class="autocomplete form-control" data-bs-autocomplete="[]" placeholder="{{ $placeholder }}">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="button-3">{{ $buttonLabel }}</button>
                    </div>
                    <span class="autocomplete-icon" aria-hidden="true">
                        <svg class="icon icon-sm icon-primary"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use></svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
