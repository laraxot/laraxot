@props(['section' => []])
{{--
    Fascia grigia dell'elenco servizi: ricerca, conteggio, card servizio, "carica altri" e colonna laterale di link.
    `variant` = catalog (elenco generale: titolo visibile, pulsante Invio, colonna laterale prima su mobile) o category
    (elenco di una categoria: titolo nascosto, conteggio nel form, card senza categoria). Dati in `section`: title, search
    (label, placeholder, button), count (value, text), category_label, items[] (title, url, text, category), load_more
    (label, element) e sidebar (title, items[] con kind = cta per il link finale con icona).
--}}
@php
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $catalog = ($section['variant'] ?? 'catalog') === 'catalog';
    $search = $section['search'] ?? [];
    $items = $section['items'] ?? [];
    $sidebarItems = $section['sidebar']['items'] ?? [];
@endphp
<div class="bg-grey-card">
    <div class="container">
        <div class="row">
            @if ($catalog)
                <div class="col-12">
                    <h2 class="title-xxlarge mb-4 mt-5 mb-lg-10">{{ $section['title'] ?? '' }}</h2>
                </div>
            @else
                <h2 class="visually-hidden">{{ $section['title'] ?? '' }}</h2>
            @endif
            <div @class(['col-12 col-lg-8', 'pt-lg-50 pb-lg-50' => $catalog, 'pt-30 pt-lg-50 pb-lg-50' => ! $catalog])>
                <div class="cmp-input-search">
                    <div @class(['form-group autocomplete-wrapper', 'mb-2 mb-lg-4' => $catalog, 'mb-0' => ! $catalog])>
                        <div class="input-group">
                            <label for="autocomplete-autocomplete-three" class="visually-hidden">{{ $search['label'] ?? '' }}</label>
                            <input type="search" class="autocomplete form-control" placeholder="{{ $search['placeholder'] ?? '' }}" id="autocomplete-autocomplete-three" name="autocomplete-three" data-bs-autocomplete="[]">
                            @if ($catalog)
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="button-3">{{ $search['button'] ?? '' }}</button>
                                </div>
                            @endif
                            <span class="autocomplete-icon" aria-hidden="true">
                                <svg class="icon icon-sm icon-primary"><use href="{{ $icon('it-search') }}"></use></svg></span>
                        </div>
                        @unless ($catalog)
                            <p id="autocomplete-label" class="mt-2 mt-lg-3 mb-4"><strong>{{ $section['count']['value'] ?? '' }}</strong> {{ $section['count']['text'] ?? '' }}</p>
                        @endunless
                    </div>
                </div>
                @if ($catalog)
                    <p class="mb-4"><strong>{{ $section['count']['value'] ?? '' }}</strong> {{ $section['count']['text'] ?? '' }}</p>
                @endif
                @foreach ($items as $item)
                    <div @class(['cmp-card-latest-messages mb-3', 'mb-30' => ! ($catalog && $loop->last)]) data-bs-toggle="modal" data-bs-target="#">
                        <div class="card shadow-sm px-4 pt-4 pb-4 rounded">
                            <span class="visually-hidden">{{ $section['category_label'] ?? '' }}</span>
                            <div class="card-header border-0 p-0">
                                @if (filled($item['category'] ?? null))
                                    <a class="text-decoration-none title-xsmall-bold mb-2 category text-uppercase" href="{{ $item['category']['url'] ?? '#' }}">{{ $item['category']['label'] ?? '' }}</a>
                                @endif
                            </div>
                            <div class="card-body p-0 my-2">
                                <h3 class="green-title-big t-primary mb-8">
                                    <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none" data-element="service-link">{{ $item['title'] ?? '' }}</a></h3>
                                <p class="text-paragraph">{{ $item['text'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <button type="button"@if (filled($section['load_more']['element'] ?? null)) data-element="{{ $section['load_more']['element'] }}"@endif @class(['btn btn-outline-primary pt-15 pb-15 pl-90 pr-90', 'mb-30 mt-3 mb-lg-50' => $catalog, 'full-mb text-button'])><span class="">{{ $section['load_more']['label'] ?? '' }}</span></button>
            </div>
            <div @class(['col-12 col-lg-4', 'pt-30 pt-lg-5 ps-lg-5 order-first order-md-last' => $catalog, 'pt-50 pb-30 pt-lg-5 ps-lg-5' => ! $catalog])>
                <div class="link-list-wrap">
                    <h2 class="title-xsmall-semi-bold"><span>{{ $section['sidebar']['title'] ?? '' }}</span></h2>
                    <ul class="link-list t-primary">
                        @foreach ($sidebarItems as $link)
                            @php
                                $isCta = ($link['kind'] ?? '') === 'cta';
                                $nextIsCta = (($sidebarItems[$loop->index + 1]['kind'] ?? '') === 'cta');
                                $itemClass = $loop->last ? '' : ($loop->first ? 'mb-3 mt-3' : ($nextIsCta ? 'mb-4' : 'mb-3'));
                            @endphp
                            <li{!! $itemClass !== '' ? ' class="'.$itemClass.'"' : '' !!}>
                                @if ($isCta)
                                    <a class="list-item ps-0 text-button-xs-bold d-flex align-items-center text-decoration-none" href="{{ $link['url'] ?? '#' }}">
                                        <span class="mr-10">{{ $link['label'] ?? '' }}</span>
                                        <svg class="icon icon-xs icon-primary"><use href="{{ $icon($link['icon'] ?? 'it-arrow-right') }}"></use></svg></a>
                                @else
                                    <a class="list-item ps-0 title-medium" href="{{ $link['url'] ?? '#' }}"><span>{{ $link['label'] ?? '' }}</span></a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
