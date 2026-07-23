{{--
    Search Results Block - Bootstrap Italia Exact Replica
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html
    
    Structure:
    - Search input with button
    - Filters sidebar (LEFT, col-lg-3)
    - Results (RIGHT, col-lg-9)
    - Rating section
    - Load more button
--}}
@props(['data' => []])

@php
    $query = $data['query'] ?? 'termine cercato';
    $count = $data['count'] ?? 645;
    $results = $data['results'] ?? [];
    $categories = $data['categories'] ?? [
        ['id' => 'unit', 'name' => 'Unità organizzativa', 'count' => 21],
        ['id' => 'public-person', 'name' => 'Persona pubblica', 'count' => 14],
        ['id' => 'documents', 'name' => 'Documenti', 'count' => 7],
        ['id' => 'services', 'name' => 'Servizi', 'count' => 208],
    ];
@endphp

{{-- Search Input Section --}}
<div class="container">
    <div class="form-group cmp-input-search-button mt-2 mb-4 mb-lg-50">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <svg class="icon icon-md">
                        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use>
                    </svg>
                </div>
            </div>
            <label for="input-group-ricerca" class="active">Con Etichetta</label>
            <input type="search" class="form-control" id="input-group-ricerca" name="input-group-ricerca" placeholder="Pagamento TARI" value="{{ $query }}">
        </div>
        <button type="button" class="btn btn-primary">
            <span class="">Cerca</span>
        </button>
    </div>
</div>

{{-- Main Content: Filters + Results --}}
<div class="container">
    <div class="row justify-content-center">
        {{-- Filters Sidebar (LEFT) --}}
        <div class="col-lg-3 d-none d-lg-block scroll-filter-wrapper">
            <h2 class="visually-hidden" id="filter">filtri da applicare</h2>
            <fieldset>
                <legend class="h6 text-uppercase category-list__title">Tipologie</legend>
                <div class="categoy-list pb-4">
                    <ul>
                        @foreach($categories as $cat)
                        <li>
                            <div class="form-check">
                                <div class="checkbox-body border-light py-1">
                                    <input type="checkbox" id="{{ $cat['id'] }}" name="category" value="{{ $cat['id'] }}">
                                    <label for="{{ $cat['id'] }}" class="subtitle-small_semi-bold mb-0 category-list__list">
                                        {{ $cat['name'] }} ({{ $cat['count'] }})
                                    </label>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </fieldset>
        </div>

        {{-- Results (RIGHT) --}}
        <div class="col-lg-8 offset-lg-1">
            {{-- Results Header Bar --}}
            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-3 mb-2">
                <h2 class="visually-hidden" id="search-result">Risultati di ricerca</h2>
                <span class="search-results u-grey-light">
                    <span class="numResult">{{ $count }}</span> Risultati
                </span>
                {{-- Mobile filter button --}}
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories"
                        class="btn p-0 pe-2 d-lg-none">
                    <span class="rounded-icon">
                        <svg class="icon icon-primary icon-xs"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-funnel"></use></svg>
                    </span>
                    <span class="t-primary title-xsmall-semi-bold ms-1">Filtra</span>
                </button>
                {{-- Desktop clear filters --}}
                <button type="button" class="btn d-none d-lg-block btn-result" disabled="disabled">
                    Rimuovi tutti i filtri
                </button>
            </div>

            {{-- Results List Container --}}
            <div class="container p-0">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-12 pt-3">
                        @foreach($results as $index => $result)
                        <div class="cmp-card-latest-messages mb-3 mb-30" data-bs-toggle="modal" data-bs-target="#">
                            <div class="card shadow-sm px-4 pt-4 pb-4 rounded">
                                <span class="visually-hidden">Categoria:</span>
                                <div class="card-header border-0 p-0">
                                    <a class="text-decoration-none title-xsmall-bold mb-2 category text-uppercase" href="#">
                                        {{ $result['type'] ?? 'Documento' }}
                                    </a>
                                </div>
                                <div class="card-body p-0 my-2">
                                    <h3 class="green-title-big t-primary mb-8">
                                        <a href="{{ $result['url'] ?? '#' }}" class="text-decoration-none" data-element="service-link">
                                            {{ $result['title'] }}
                                        </a>
                                    </h3>
                                    <p class="text-paragraph">{{ strip_tags($result['excerpt'] ?? '') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Load More Button --}}
            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-outline-primary pt-15 pb-15 pl-90 pr-90 mb-30 mb-lg-50 full-mb text-button">
                    <span class="">Carica altri risultati</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Filter Modal --}}
<div class="modal fade" id="modal-categories" tabindex="-1" role="dialog" aria-labelledby="modal-categories-label">
    <div class="modal-wrapper">
        <div class="modal-header">
            <h2 class="title-medium-semi-bold" id="modal-categories-label">Filtra per categoria</h2>
            <button class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi">
                <svg class="icon"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-close"></use></svg>
            </button>
        </div>
        <div class="modal-body">
            <fieldset>
                <legend class="h6 text-uppercase category-list__title">Tipologie</legend>
                <div class="categoy-list pb-4">
                    <ul>
                        @foreach($categories as $cat)
                        <li>
                            <div class="form-check">
                                <div class="checkbox-body border-light py-1">
                                    <input type="checkbox" id="modal-{{ $cat['id'] }}" name="category-modal" value="{{ $cat['id'] }}">
                                    <label for="modal-{{ $cat['id'] }}" class="subtitle-small_semi-bold mb-0 category-list__list">
                                        {{ $cat['name'] }} ({{ $cat['count'] }})
                                    </label>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" data-bs-dismiss="modal">Applica</button>
        </div>
    </div>
</div>

{{-- Rating Section --}}
{{-- Use theme-level rating block, NOT Blog module's rating (which requires $model) --}}
<x-blocks.rating.default :data="[]" />
