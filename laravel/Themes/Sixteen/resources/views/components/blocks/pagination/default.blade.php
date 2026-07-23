{{--
    Pagination Block - Bootstrap Italia Style
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html
    Uses "Load more" button style instead of traditional page numbers
--}}
@props(['data' => []])

@php
    $current = $data['current'] ?? 1;
    $total = $data['total'] ?? 1;
    $prev = $data['prev'] ?? null;
    $next = $data['next'] ?? null;
@endphp

<div class="container p-0">
    <div class="d-flex justify-content-center">
        @if($next)
        <button type="button" class="btn btn-outline-primary pt-15 pb-15 pl-90 pr-90 mb-30 mb-lg-50 full-mb text-button"
                data-load-more
                data-page="{{ $next }}">
            <span class="">Carica altri risultati</span>
        </button>
        @endif
    </div>
</div>
