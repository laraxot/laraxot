@props(['data' => []])
{{--
    Fascia grigia finale del dettaglio servizio: carosello "Contenuti correlati" (`related`: title, items[] con icon, title,
    aria_hidden, links[] e more = voce "Vedi altri N" con label, id e links[] dell'elenco nascosto) e scheda contatti (`contacts`, stessa forma del
    partial condiviso dc-contacts, che qui non basta perche' il carosello sta nella stessa riga). Il carosello e' montato dal
    tema per selettore (`data-bs-carousel-splide`).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5">
                <div class="it-carousel-wrapper carousel-4-card splide cmp-carousel" data-bs-carousel-splide>
                    <div class="it-header-block">
                        <div class="it-header-block-title">
                            <h2 class="text-center border-0 cmp-carousel__title pb-0 pb-0">{{ $d('related.title') }}</h2>
                        </div>
                    </div>
                    <div class="splide__track">
                        <ul class="splide__list it-carousel-all">
                            @foreach ($d('related.items', []) as $slide)
                                <li class="splide__slide">
                                    <div class="card-wrapper card-space h-100 pb-4">
                                        <div class="card card-bg single-card rounded shadow-sm">
                                            <div class="cmp-carousel__header">
                                                <svg class="icon icon-md"@if ($slide['aria_hidden'] ?? false) aria-hidden="true"@endif><use href="{{ $icon($slide['icon'] ?? '') }}"></use></svg>
                                                <span class="ms-3 cmp-carousel__header-title">{{ $slide['title'] ?? '' }}</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="link-list-wrapper">
                                                    <ul class="card-body__list">
                                                        @foreach ($slide['links'] ?? [] as $link)
                                                            <li>
                                                                <a class="list-item px-0" href="{{ $link['url'] ?? '#' }}"><span>{{ $link['label'] ?? '' }}</span></a>
                                                            </li>
                                                        @endforeach
                                                        @if (filled($slide['more'] ?? null))
                                                            <li>
                                                                <a class="show-more px-0" data-bs-toggle="collapse" href="#{{ $slide['more']['id'] }}" aria-expanded="false" aria-controls="{{ $slide['more']['id'] }}">
                                                                    <span class="show-more d-flex align-items-center">{{ $slide['more']['label'] }}
                                                                        <svg class="icon icon-primary icon-md"><use href="{{ $icon('it-expand') }}"></use></svg>
                                                                    </span>
                                                                </a>
                                                                <ul class="collapse" id="{{ $slide['more']['id'] }}">
                                                                    @foreach ($slide['more']['links'] ?? [] as $link)
                                                                        <li>
                                                                            <a class="list-item px-0" href="{{ $link['url'] ?? '#' }}"><span>{{ $link['label'] ?? '' }}</span></a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <span class="hr-shadow-sm d-none d-lg-block"></span>
                </div>
            </div>
            <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                <div class="cmp-contacts">
                    <div class="card w-100">
                        <div class="card-body">
                            @foreach (array_filter([$d('contacts.main', []), $d('contacts.problems', [])], static fn ($group) => filled($group['title'] ?? null)) as $group)
                                <h2 @class(['title-medium-2-semi-bold', 'mt-4' => ! $loop->first])>{{ $group['title'] ?? '' }}</h2>
                                <ul class="contact-list p-0">
                                    @foreach ($group['items'] ?? [] as $contact)
                                        <li>
                                            <a class="list-item" href="{{ $contact['url'] ?? '#' }}"@if (filled($contact['element'] ?? null)) data-element="{{ $contact['element'] }}"@endif>
                                                <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon($contact['icon'] ?? 'it-help-circle') }}"></use></svg>
                                                <span>{{ $contact['label'] ?? '' }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
