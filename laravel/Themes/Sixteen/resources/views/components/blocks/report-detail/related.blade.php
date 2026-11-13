{{--
    Carosello "Contenuti correlati" (JSON: related.title, related.cards[icon, title, links[label], more{id, label, items[]}]). Monta Splide
    `dcCarousel` per la classe `carousel-4-card` (voce di CAROUSELS da aggiungere in design-comuni-home.js, vedi story DCP-23).
--}}
<div class="it-carousel-wrapper carousel-4-card splide cmp-carousel" data-bs-carousel-splide>
    <div class="it-header-block">
        <div class="it-header-block-title"><h2 class="text-center border-0 cmp-carousel__title pb-0">{{ $d('related.title') }}</h2></div>
    </div>
    <div class="splide__track">
        <ul class="splide__list it-carousel-all">
            @foreach ($d('related.cards', []) as $card)
                <li class="splide__slide">
                    <div class="card-wrapper card-space h-100 pb-4">
                        <div class="card card-bg single-card rounded shadow-sm">
                            <div class="cmp-carousel__header">
                                <svg class="icon icon-md" aria-hidden="true"><use href="{{ $icon($card['icon'] ?? '') }}"></use></svg>
                                <span class="ms-3 cmp-carousel__header-title">{{ $card['title'] ?? '' }}</span>
                            </div>
                            <div class="card-body">
                                <div class="link-list-wrapper">
                                    <ul class="card-body__list">
                                        @foreach ($card['links'] ?? [] as $link)
                                            <li><a class="list-item px-0" href="#"><span>{{ $link }}</span></a></li>
                                        @endforeach
                                        @if (filled($card['more'] ?? null))
                                            <li>
                                                <a class="show-more px-0" data-bs-toggle="collapse" href="#{{ $card['more']['id'] ?? '' }}" aria-expanded="false" aria-controls="{{ $card['more']['id'] ?? '' }}">
                                                    <span class="show-more d-flex align-items-center">{{ $card['more']['label'] ?? '' }} <svg class="icon icon-primary icon-md"><use href="{{ $icon('it-expand') }}"></use></svg></span></a>
                                                <ul class="collapse" id="{{ $card['more']['id'] ?? '' }}">
                                                    @foreach ($card['more']['items'] ?? [] as $link)
                                                        <li><a class="list-item px-0" href="#"><span>{{ $link }}</span></a></li>
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
