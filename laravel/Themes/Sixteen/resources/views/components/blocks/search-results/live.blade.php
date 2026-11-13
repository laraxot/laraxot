{{--
    Ricerca globale del sito (/{lang}/ricerca?q=), modalita' live del blocco search-results/design-comuni (JSON: `source: site_search`).
    Risultati: SearchFrontOfficeAction (segnalazioni pubbliche, notizie, eventi, pagine CMS), letti a ogni richiesta dal parametro GET `q`:
    URL condivisibili, nessun JavaScript. Testi: lang fixcity::site_search. Story: Modules/Fixcity/docs/stories/site-wide-front-office-search.story.md.
    Accessibilita': H1 visibile, breadcrumb, conteggio in role="status", risultati come lista di link.
--}}
@php
    use Illuminate\Support\Str;
    use Modules\Fixcity\Actions\Search\SearchFrontOfficeAction;
    use Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes;

    $rawQuery = request()->query('q');
    $result = app(SearchFrontOfficeAction::class)->execute(is_string($rawQuery) ? $rawQuery : '', app()->getLocale());
    $t = static fn (string $key, array $replace = []): string => __('fixcity::site_search.'.$key, $replace);
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $partialData = ['breadcrumb' => ['items' => [['label' => $t('breadcrumb.home'), 'url' => route('home')]], 'current' => $t('breadcrumb.current')]];
    $d = static fn (string $key, mixed $default = '') => data_get($partialData, $key, $default);
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include('pub_theme::components.blocks.report-partials.breadcrumb')
            <div class="cmp-heading p-0">
                <h1 class="title-xxxlarge" id="search-container">{{ $t('heading') }}</h1>
            </div>
            <form id="site-search-form" method="get" action="{{ FrontofficeRoutes::searchIndex() }}" role="search" class="form-group cmp-input-search-button mt-2 mb-4 mb-lg-50">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><svg class="icon icon-md" aria-hidden="true"><use href="{{ $icon('it-search') }}"></use></svg></div>
                    </div>
                    <label for="site-search-q" class="active">{{ $t('form.label') }}</label>
                    <input type="search" class="form-control" id="site-search-q" name="q" value="{{ $result->query }}" placeholder="{{ $t('form.placeholder') }}" maxlength="{{ \Modules\Fixcity\Datas\PublicTicketListFilters::MAX_QUERY_LENGTH }}">
                </div>
                <button type="submit" class="btn btn-primary">{{ $t('form.button') }}</button>
            </form>
            @if ($result->isEmptyQuery())
                <p class="text-paragraph mb-5" data-element="search-empty-query">{{ $t('results.empty_query') }}</p>
            @else
                <h2 class="visually-hidden" id="search-result">{{ $t('results.hidden_title') }}</h2>
                <p class="search-results u-grey-light border-bottom border-light pb-3 mb-4" role="status">{{ trans_choice('fixcity::site_search.results.count', $result->count(), ['query' => $result->query]) }}</p>
                @if ($result->count() === 0)
                    <p class="text-paragraph mb-5" data-element="search-no-results">{{ $t('results.none') }}</p>
                @else
                    <ul class="list-unstyled mb-4" data-element="search-results" aria-labelledby="search-result">
                        @foreach ($result->items as $item)
                            <li class="cmp-card-latest-messages mb-3">
                                <div class="card shadow-sm px-4 pt-4 pb-4 rounded">
                                    <div class="card-header border-0 p-0">
                                        <span class="title-xsmall-bold mb-2 category text-uppercase">{{ $item->typeLabel }}</span>
                                    </div>
                                    <div class="card-body p-0 my-2">
                                        <h3 class="green-title-big t-primary mb-8"><a href="{{ $item->url }}" class="text-decoration-none" data-element="search-result-link">{{ $item->title }}</a></h3>
                                        @if ($item->excerpt !== '')
                                            <p class="text-paragraph">{{ Str::limit(Str::squish($item->excerpt), 200) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if ($result->hasMoreTickets())
                        <p class="mb-5"><a class="btn btn-outline-primary" href="{{ FrontofficeRoutes::ticketsIndex().'?'.http_build_query(['q' => $result->query]) }}">{{ $t('results.more_tickets', ['count' => $result->ticketTotal]) }}</a></p>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
