@props(['data' => []])
{{--
    Elenco pubblico delle segnalazioni (pagina reale tickets.index, riferimento sito/segnalazioni-elenco), DCP-104.
    Stessa struttura e stesse classi del blocco misurato report-list/design-comuni, ma con dati veri: le segnalazioni arrivano da
    ListPublicTicketsAction (solo stati visibili a tutti, nessun dato dell'autore, nessun codice di tracciamento) e filtri, ricerca,
    ordinamento e paginazione sono un normale form GET (URL condivisibili, nessun JavaScript obbligatorio). Testi: lang fixcity::ticket_list.
    Scheda attiva: `Elenco` se il visitatore cerca, filtra, ordina o sfoglia; altrimenti `Mappa` (vedi PublicTicketListFilters::fromRequest).
    La mappa (<map-lit>) legge /api/tickets/geojson con gli stessi filtri. JSON: solo `view` e `header_active`.
--}}
@php
    use Modules\Fixcity\Actions\ListPublicTicketsAction;
    use Modules\Fixcity\Datas\PublicTicketListFilters;
    use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

    // path senza host, come il resto del front office (FrontofficeUrl)
    $base = FrontofficeRoutes::ticketsIndex(false);
    $filters = PublicTicketListFilters::fromRequest(request());
    $list = app(ListPublicTicketsAction::class)->execute($filters);
    $t = static fn (string $key, array $replace = []): string => __('fixcity::ticket_list.'.$key, $replace);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
    $listActive = $filters->view === 'list';
    $hasRail = $list['type_facets'] !== [] || $list['status_facets'] !== [] || $filters->hasActiveFilters();
    $withQuery = static fn (string $url, array $query): string => $url.($query === [] ? '' : '?'.http_build_query($query));
    $mapUrl = $withQuery('/api/tickets/geojson', $filters->toQuery(['sort', 'page']));
    $listUrl = $withQuery($base, array_merge($filters->toQuery(['page']), ['view' => 'list']));
    // dati per i partial condivisi (breadcrumb, valutazione, contatti): stessa forma del JSON delle pagine di prova, ma dalla lingua attiva
    $partialData = [
        'breadcrumb' => ['items' => [['label' => $t('breadcrumb.home'), 'url' => route('home')]], 'current' => $t('breadcrumb.current')],
        'feedback' => array_merge((array) __('fixcity::ticket_list.feedback'), ['col_class' => 'col-12 col-lg-6 p-lg-0 px-3']),
        'contacts' => ['layout' => 'narrow', 'main' => ['title' => $t('contacts.title'), 'items' => [
            ['icon' => 'it-help-circle', 'label' => $t('contacts.faq'), 'url' => FrontofficeRoutes::faqIndex()],
            ['icon' => 'it-search', 'label' => $t('contacts.track'), 'url' => FrontofficeRoutes::ticketsTrack()],
            ['icon' => 'it-user', 'label' => $t('contacts.account'), 'url' => FrontofficeRoutes::personalAreaPratiche()],
            // DCP-289: il numero verde viene dall'ente (config('ente.toll_free')); senza numero la voce sparisce
            ['icon' => 'it-telephone', 'label' => $t('contacts.phone'), 'ente' => 'toll_free'],
        ]]],
    ];
    $d = static fn (string $key, mixed $default = '') => data_get($partialData, $key, $default);
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center mb-md-40 mb-lg-80">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
            <div class="cmp-heading p-0">
                <h1 class="title-xxxlarge">{{ $t('heading.title') }}</h1>
                <p class="subtitle-small">{{ trans_choice('fixcity::ticket_list.heading.subtitle', $list['resolved_last_12_months'], ['count' => $list['resolved_last_12_months']]) }}</p>
            </div>
        </div>
        <hr class="d-none d-lg-block mt-30 mb-2">
    </div>
    <div class="row justify-content-center">
        @if ($hasRail)
            <div class="col-lg-3 d-none d-lg-block">
                @include('pub_theme::components.blocks.ticket-list.filters', ['idp' => 'tl', 'collapsible' => true, 'showReset' => false])
            </div>
        @endif
        <div @class(['col-lg-8 offset-lg-1' => $hasRail, 'col-12' => ! $hasRail])>
            <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
                <span class="search-results" id="block-results-count" data-reference-total="{{ $list['total'] }}">{{ trans_choice('fixcity::ticket_list.results.count', $list['total'], ['count' => $list['total']]) }}</span>
                @if ($hasRail)
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories" class="btn p-0 pe-2 d-lg-none">
                        <span class="rounded-icon"><svg class="icon icon-primary icon-xs" aria-hidden="true"><use href="{{ $icon('it-funnel') }}"></use></svg></span>
                        <span class="t-primary title-xsmall-semi-bold ms-1">{{ $t('filters.open') }}</span></button>
                    <a href="{{ $base }}" class="btn p-0 pe-2 d-none d-lg-block"><span class="title-xsmall-semi-bold ms-1">{{ $t('filters.reset') }}</span></a>
                @endif
            </div>
            <ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" id="tabDisservizio" role="tablist" aria-label="{{ $t('tabs.label') }}">
                @foreach (['map' => $t('tabs.map'), 'list' => $t('tabs.list')] as $tabKey => $tabLabel)
                    {{-- ARIA valida (li presentation, a role=tab): il riferimento ha li role=tab con a role=button, che axe segnala (nested-interactive, aria-allowed-attr) --}}
                    <li class="nav-item w-100" role="presentation">
                        <a @class(['nav-link', 'active' => $listActive === ($tabKey === 'list'), 'title-medium-semi-bold pt-0']) href="#data-ex-disservizio{{ $loop->iteration }}" data-bs-toggle="tab" role="tab" aria-controls="data-ex-disservizio{{ $loop->iteration }}" aria-selected="{{ $listActive === ($tabKey === 'list') ? 'true' : 'false' }}"> {{ $tabLabel }} </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                <div @class(['tab-pane fade', 'show active' => ! $listActive]) id="data-ex-disservizio1" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="map-box" style="container-type:inline-size">
                                {{-- altezza = proporzioni del segnaposto del riferimento (774x673), minimo 320 px come map-lit --}}
                                <map-lit id="block-map" class="w-100" legend-mode="sidebar" data-url="{{ $mapUrl }}" height="max(320px, 86.95cqw)" style="display:block;width:100%" aria-label="{{ $t('map.alt') }}"></map-lit>
                                <noscript><p class="subtitle-small mt-3">{{ $t('map.noscript') }} <a href="{{ $listUrl }}">{{ $t('map.noscript_link') }}</a></p></noscript>
                            </div>
                        </div>
                        @include('pub_theme::components.blocks.ticket-list.invite', ['headingId' => 'create-ticket-heading'])
                    </div>
                </div>
                <div @class(['tab-pane fade', 'show active' => $listActive]) id="data-ex-disservizio2" role="tabpanel">
                    <div class="row">
                        <h2 class="visually-hidden">{{ $t('tabs.list') }}</h2>
                        @forelse ($list['items'] as $item)
                            @include('pub_theme::components.blocks.ticket-list.card', ['item' => $item, 'first' => $loop->first])
                        @empty
                            <div class="col-12 mb-4">
                                <p class="subtitle-small mb-2">{{ $t('empty.title') }}</p>
                                <p class="subtitle-small mb-0">{{ $filters->hasActiveFilters() ? $t('empty.filtered') : $t('empty.text') }}</p>
                            </div>
                        @endforelse
                        @if ($list['last_page'] > 1)
                            @include('pub_theme::components.blocks.ticket-list.pagination')
                        @endif
                        @include('pub_theme::components.blocks.ticket-list.invite', ['headingId' => 'create-ticket-heading-list'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include($parts.'.rating')
@include($parts.'.contacts')
@if ($hasRail)
    @include('pub_theme::components.blocks.ticket-list.modal')
@endif
