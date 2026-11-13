@props(['data' => []])
{{--
    Pagina dei risultati di ricerca Design Comuni, due modalita':
    - `source: site_search` (pagina reale /{lang}/ricerca, ricerca.index.json): risultati veri da SearchFrontOfficeAction, partial live.blade.php;
    - altrimenti la pagina di riferimento statica: testi, filtri, risultati e link arrivano dal JSON della pagina (tests.risultati-ricerca.json).
    Stili: resources/css/design-comuni/. Rating e contatti sono i partial condivisi con la homepage.
--}}
@if (data_get($data, 'source') === 'site_search')
    @include('pub_theme::components.blocks.search-results.live')
@else
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $results = $d('results.items', []);
@endphp

<h1 class="visually-hidden" id="search-container">{{ $d('title') }}</h1>
<div class="container" id="main-container">
    <div class="mb-md-5">
        <div class="cmp-breadcrumbs" role="navigation">
            <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                @foreach ($d('breadcrumb.items', []) as $crumb)
                    <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                @endforeach
                <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
            </ol></nav>
        </div>
    </div>
</div>
<div class="container">
    <div class="form-group cmp-input-search-button mt-2 mb-4 mb-lg-50">
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text"><svg class="icon icon-md"><use href="{{ $icon('it-search') }}"></use></svg></div>
            </div>
            <label for="input-group-1" class="active">{{ $d('search.label') }}</label>
            <input type="search" class="form-control" id="input-group-1" name="input-group-1" placeholder="{{ $d('search.placeholder') }}"@if (filled($d('search.query'))) value="{{ $d('search.query') }}"@endif data-focus-mouse="false">
        </div>
        <button type="button" data-bs-toggle="modal" data-bs-target="#" class="btn btn-primary"><span class="">{{ $d('search.button') }}</span></button>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-3 d-none d-lg-block scroll-filter-wrapper">
            <h2 class="visually-hidden" id="filter">{{ $d('filters.hidden_title') }}</h2>
            @foreach ($d('filters.groups', []) as $group)
                <fieldset>
                    <legend class="h6 text-uppercase category-list__title">{{ $group['title'] ?? '' }}</legend>
                    <div class="categoy-list pb-4">
                        <ul>
                            @foreach ($group['items'] ?? [] as $filter)
                                <li>
                                    <div class="form-check">
                                        <div class="checkbox-body border-light py-1">
                                            <input type="checkbox" id="{{ $filter['id'] ?? '' }}" name="category" value="{{ $filter['value'] ?? '' }}">
                                            <label for="{{ $filter['id'] ?? '' }}" class="subtitle-small_semi-bold mb-0 category-list__list">{{ $filter['label'] ?? '' }}</label>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </fieldset>
            @endforeach
        </div>
        <div class="col-lg-8 offset-lg-1">
            <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-3 mb-2">
                <h2 class="visually-hidden" id="search-result">{{ $d('results.hidden_title') }}</h2>
                <span class="search-results u-grey-light"><span class="numResult">{{ $d('results.count') }}</span> {{ $d('results.count_label') }}</span>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal-categories" class="btn p-0 pe-2 d-lg-none">
                    <span class="rounded-icon"><svg class="icon icon-primary icon-xs" aria-hidden="true"><use href="{{ $icon('it-funnel') }}"></use></svg></span>
                    <span class="t-primary title-xsmall-semi-bold ms-1">{{ $d('results.filter_button') }}</span>
                </button>
                <button type="button" class="btn d-none d-lg-block btn-result" disabled="disabled">{{ $d('results.clear') }}</button>
            </div>
            <div class="container p-0">
                <div class="row flex-column-reverse flex-lg-row">
                    <div class="col-12 pt-3">
                        @foreach ($results as $result)
                            <div @class(['cmp-card-latest-messages', 'mb-3', 'mb-30' => ! $loop->last, 'mb-4' => $loop->last]) data-bs-toggle="modal" data-bs-target="#">
                                <div class="card shadow-sm px-4 pt-4 pb-4 rounded">
                                    <span class="visually-hidden">{{ $d('results.category_label') }}</span>
                                    <div class="card-header border-0 p-0">
                                        <a class="text-decoration-none title-xsmall-bold mb-2 category text-uppercase" href="{{ $result['url'] ?? '#' }}">{{ $result['category'] ?? '' }}</a>
                                    </div>
                                    <div class="card-body p-0 my-2">
                                        <h3 class="green-title-big t-primary mb-8"><a href="{{ $result['url'] ?? '#' }}" class="text-decoration-none" data-element="service-link">{{ $result['title'] ?? '' }}</a></h3>
                                        <p class="text-paragraph">{{ $result['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="container p-0">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-primary pt-15 pb-15 pl-90 pr-90 mb-30 mb-lg-50 full-mb text-button"><span class="">{{ $d('results.load_more') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
@if (filled($d('report_modal')))
    <div class="it-example-modal">
        <div class="modal fade" tabindex="-1" role="dialog" id="{{ $d('report_modal.id') }}" aria-labelledby="modal2Title">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title title-small-semi-bold" id="modal2Title">{{ $d('report_modal.title') }}</h2>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ $d('report_modal.close_label') }}"><svg class="icon"><use href="{{ $icon('it-close') }}"></use></svg></button>
                    </div>
                    <div class="modal-body text-black">
                        @foreach ($d('report_modal.fields', []) as $field)
                            <div class="border-bottom border-light">
                                <h3 class="title-xsmall border-light pt-2">{{ $field['label'] ?? '' }}</h3>
                                <p class="subtitle-small pb-2">{{ $field['value'] ?? '' }}</p>
                            </div>
                        @endforeach
                        <h3 class="title-xsmall border-light pt-2">{{ $d('report_modal.images_label') }}</h3>
                        <img src="{{ $assets }}/images/{{ $d('report_modal.image.file') }}" class="w-100 mb-2" alt="{{ $d('report_modal.image.alt') }}">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary w-100 btn-sm" type="button" data-bs-dismiss="modal" aria-label="{{ $d('report_modal.close_label') }}">{{ $d('report_modal.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endif
