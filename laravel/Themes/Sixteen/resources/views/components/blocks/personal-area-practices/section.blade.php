{{--
    Elenco a fisarmonica di segnalazioni (DCP-110), stesse classi di report-area/section-practices (`it-page-section`, `cmp-filter`,
    `cmp-accordion`, `cmp-icon-list`) ma senza i controlli del riferimento che qui non farebbero nulla (Filtra, Ordina, Cerca): le
    sezioni "in corso" e "concluse" sono gia' il filtro, e l'ordine e' sempre dal piu' recente.
    Parametri: $id, $heading, $rows (righe di BuildPersonalAreaTicketRowAction), $trackKey (track_url|track_query_url), $class.
    Usa $icon, $assets e $locale del chiamante.
--}}
@php
    $statusIcon = static fn (array $row): string => match (true) {
        $row['is_concluded'] => 'folder-concluded.svg',
        $row['status'] === 'on_hold' => 'folder-incomplete.svg',
        default => 'folder-wating.svg',
    };
@endphp
<section class="it-page-section {{ $class ?? 'mb-40 mb-lg-60' }}" id="{{ $id }}" aria-labelledby="{{ $id }}-title">
    <div class="cmp-filter">
        <div class="filter-section">
            <h2 class="cmp-filter__title title-xxlarge" id="{{ $id }}-title">{{ $heading }} ({{ count($rows) }})</h2>
        </div>
    </div>
    <div class="cmp-accordion">
        <div class="accordion" id="{{ $id }}-accordion">
            @foreach ($rows as $row)
                @php($key = $id.'-'.$row['id'])
                <div class="accordion-item">
                    <div class="accordion-header" id="heading-{{ $key }}">
                        <button class="accordion-button collapsed title-snall-semi-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}" aria-expanded="false" aria-controls="collapse-{{ $key }}">
                            <div class="button-wrapper"> {{ $row['title'] }}
                                <div class="icon-wrapper">
                                    <img class="icon-folder" src="{{ $assets }}/images/{{ $statusIcon($row) }}" alt="">
                                    <span class="u-main-black"><span class="visually-hidden">{{ __('fixcity::personal_area.ticket.status_prefix') }}</span> {{ $row['status_label'] }}</span>
                                </div>
                            </div>
                        </button>
                        <p class="accordion-date title-xsmall-regular mb-0"><time datetime="{{ $row['date_iso'] }}">{{ $row['date'] }}</time></p>
                    </div>
                    <div id="collapse-{{ $key }}" class="accordion-collapse collapse p-0" role="region" aria-labelledby="heading-{{ $key }}">
                        <div class="accordion-body">
                            @if ($row['code'] !== null)
                                <p class="mb-2 fw-normal">{{ __('fixcity::personal_area.ticket.code') }} <span class="label">{{ $row['code'] }}</span></p>
                            @endif
                            <div class="cmp-icon-list">
                                <div class="link-list-wrapper">
                                    <ul class="link-list">
                                        <li class="shadow mt-3">
                                            <a class="list-item icon-left t-primary title-small-semi-bold" href="{{ $row['detail_url'] }}" aria-label="{{ __('fixcity::personal_area.ticket.detail_aria', ['title' => $row['title']]) }}">
                                                <span class="list-item-title-icon-wrapper">
                                                    <svg class="icon icon-sm align-self-start icon-color" aria-hidden="true"><use href="{{ $icon('it-file') }}"></use></svg>
                                                    <span class="list-item-title title-small-semi-bold">{{ __('fixcity::personal_area.ticket.detail') }}</span>
                                                </span>
                                            </a>
                                        </li>
                                        @if ($row[$trackKey] !== null)
                                            <li class="shadow mt-3">
                                                <a class="list-item icon-left t-primary title-small-semi-bold" href="{{ $row[$trackKey] }}" aria-label="{{ __('fixcity::personal_area.ticket.track_aria', ['title' => $row['title']]) }}">
                                                    <span class="list-item-title-icon-wrapper">
                                                        <svg class="icon icon-sm align-self-start icon-color" aria-hidden="true"><use href="{{ $icon('it-search') }}"></use></svg>
                                                        <span class="list-item-title title-small-semi-bold">{{ __('fixcity::personal_area.ticket.track') }}</span>
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @if ($row['is_owner'] && $row[$trackKey] === null)
                                <p class="mt-3 mb-0 text-paragraph-small">{{ __('fixcity::personal_area.ticket.tracking_unavailable') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
