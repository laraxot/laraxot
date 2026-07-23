@props([
    'ns' => 'fixcity::ticket',
    'resultsCount' => 0,
    'sprite' => '',
])

<div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
    <span
        id="block-results-count"
        class="search-results"
        data-count-template="{{ __($ns . '.results.count.text', ['count' => ':count']) }}"
        data-reference-total="{{ (int) $resultsCount }}"
    >{{ __($ns . '.results.count.text', ['count' => $resultsCount]) }}</span>

    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories" class="btn btn-outline-primary btn-sm d-lg-none">
        <svg class="icon icon-primary icon-xs" aria-hidden="true">
            <use href="{{ $sprite }}#it-funnel"></use>
        </svg>
        <span class="ms-1">{{ __($ns . '.filter.button.label') }}</span>
    </button>

    <button type="button" id="block-clear-filters" class="btn btn-primary btn-sm d-none d-lg-block">
        <span class="title-xsmall-semi-bold">{{ __($ns . '.filter.remove.label') }}</span>
    </button>
</div>
