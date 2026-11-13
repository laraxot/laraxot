{{--
    Form di ricerca e filtri dell'elenco (GET verso /{lang}/tickets). Parametri: $idp (prefisso degli id: lo stesso form sta nella colonna
    desktop e nella modale mobile), $collapsible (stato, periodo e ordinamento in un <details>, aperto se uno e' attivo), $showReset.
    Usa $filters, $list, $base, $t del blocco. Il markup di "categoria" e' quello del riferimento (fieldset > categoy-list).
--}}
@php
    $secondaryActive = $filters->statuses !== [] || $filters->period !== '' || $filters->sort !== \Modules\Fixcity\Datas\PublicTicketListFilters::DEFAULT_SORT;
@endphp
<form method="get" action="{{ $base }}" id="{{ $idp }}-form" role="search" aria-label="{{ $t('filters.region') }}">
    <div class="form-group mb-4">
        <label for="{{ $idp }}-q" class="subtitle-small_semi-bold">{{ $t('filters.search_label') }}</label>
        <input type="search" class="form-control" id="{{ $idp }}-q" name="q" value="{{ $filters->query }}" maxlength="{{ \Modules\Fixcity\Datas\PublicTicketListFilters::MAX_QUERY_LENGTH }}" autocomplete="off" enterkeyhint="search" aria-describedby="{{ $idp }}-q-help">
        <small id="{{ $idp }}-q-help" class="form-text">{{ $t('filters.search_help') }}</small>
    </div>
    @if ($list['type_facets'] !== [])
        <fieldset>
            <legend class="h6 text-uppercase category-list__title">{{ $t('filters.category') }}</legend>
            <div class="categoy-list pb-4">
                <ul>
                    @foreach ($list['type_facets'] as $facet)
                        <li>
                            <div class="form-check">
                                <div class="checkbox-body border-light {{ $collapsible ? 'py-1' : 'py-2' }}">
                                    <input type="checkbox" id="{{ $idp }}-type-{{ $facet['value'] }}" name="types[]" value="{{ $facet['value'] }}" data-count="{{ $facet['count'] }}" @checked(in_array($facet['value'], $filters->types, true))>
                                    <label for="{{ $idp }}-type-{{ $facet['value'] }}" class="subtitle-small_semi-bold mb-0 category-list__list">{{ $facet['label'] }} ({{ $facet['count'] }})</label>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </fieldset>
    @endif
    @if ($collapsible)
        <details class="mb-4" @if ($secondaryActive) open @endif>
            <summary class="title-xsmall-semi-bold t-primary py-2">{{ $t('filters.more') }}</summary>
    @endif
    @if ($list['status_facets'] !== [])
        <fieldset>
            <legend class="h6 text-uppercase category-list__title">{{ $t('filters.status') }}</legend>
            <div class="categoy-list pb-4">
                <ul>
                    @foreach ($list['status_facets'] as $facet)
                        <li>
                            <div class="form-check">
                                <div class="checkbox-body border-light {{ $collapsible ? 'py-1' : 'py-2' }}">
                                    <input type="checkbox" id="{{ $idp }}-status-{{ $facet['value'] }}" name="statuses[]" value="{{ $facet['value'] }}" data-count="{{ $facet['count'] }}" @checked(in_array($facet['value'], $filters->statuses, true))>
                                    <label for="{{ $idp }}-status-{{ $facet['value'] }}" class="subtitle-small_semi-bold mb-0 category-list__list">{{ $facet['label'] }} ({{ $facet['count'] }})</label>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </fieldset>
    @endif
    <div class="form-group mb-4">
        <label for="{{ $idp }}-period" class="subtitle-small_semi-bold">{{ $t('filters.period') }}</label>
        <select class="form-select" id="{{ $idp }}-period" name="period">
            <option value="" @selected($filters->period === '')>{{ $t('filters.periods.all') }}</option>
            @foreach (\Modules\Fixcity\Datas\PublicTicketListFilters::PERIODS as $period)
                <option value="{{ $period }}" @selected($filters->period === $period)>{{ $t('filters.periods.'.$period) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-4">
        <label for="{{ $idp }}-sort" class="subtitle-small_semi-bold">{{ $t('filters.sort') }}</label>
        <select class="form-select" id="{{ $idp }}-sort" name="sort">
            @foreach (\Modules\Fixcity\Datas\PublicTicketListFilters::SORTS as $sort)
                <option value="{{ $sort }}" @selected($filters->sort === $sort)>{{ $t('filters.sorts.'.$sort) }}</option>
            @endforeach
        </select>
    </div>
    @if ($collapsible)
        </details>
    @endif
    <div class="d-flex flex-wrap gap-2 pb-4">
        <button type="submit" class="btn btn-primary"><span>{{ $t('filters.apply') }}</span></button>
        @if ($showReset)
            <a href="{{ $base }}" class="btn btn-outline-primary"><span>{{ $t('filters.reset') }}</span></a>
        @endif
    </div>
</form>
