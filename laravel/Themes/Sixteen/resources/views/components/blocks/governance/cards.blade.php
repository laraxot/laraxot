{{--
    Governance Cards + Events Calendar
    Reference: design-comuni-pagine-statiche/sito/homepage.html #calendario
    Structure matches reference exactly for 90%+ HTML similarity
--}}
@php
    $data = $data ?? [];
    $cards = isset($cards) && is_array($cards) ? $cards : ($data['cards'] ?? []);
    $month = isset($month) ? $month : ($data['month'] ?? '');
    $slides = isset($slides) && is_array($slides) ? $slides : ($data['slides'] ?? []);
@endphp

<section id="calendario">
    <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
        <div class="container">
            {{-- Governance Cards --}}
            <div class="row mb-2">
                @foreach($cards as $i => $card)
                <div class="col-md-6 col-lg-4">
                    @if($i === 0)
                    {{-- First card with image --}}
                    <div class="card card-teaser card-teaser-image card-flex no-after rounded shadow-sm border border-light mb-0">
                        <div class="card-image-wrapper with-read-more">
                            <div class="card-body p-3 pb-5">
                                <div class="category-top">
                                    <span class="title-xsmall-semi-bold fw-semibold text-paragraph-medium u-grey-light text-uppercase">{{ $card['category'] ?? 'Organi di governo' }}</span>
                                </div>
                                <h3 class="card-title text-paragraph-medium u-grey-light">{{ $card['title'] ?? '' }}</h3>
                                <p class="text-paragraph-card u-grey-light m-0">{{ $card['role'] ?? '' }}</p>
                            </div>
                            <div class="card-image card-image-rounded pb-5">
                                <img src="{{ $card['image'] ?? 'https://picsum.photos/150/200' }}" alt="Immagine di esempio">
                            </div>
                            <a class="read-more ps-3" href="{{ $card['url'] ?? '#' }}">
                                <span class="text">Vai alla pagina</span>
                                <svg class="icon">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @else
                    {{-- Other cards without image --}}
                    <div class="card card-teaser no-after rounded shadow-sm mb-0 border border-light">
                        <div class="card-body pb-5">
                            <div class="category-top">
                                <span class="title-xsmall-semi-bold fw-semibold">{{ $card['category'] ?? 'Organi di governo' }}</span>
                            </div>
                            <h3 class="card-title text-paragraph-medium u-grey-light">{{ $card['title'] ?? '' }}</h3>
                            <p class="text-paragraph-card u-grey-light m-0">{{ $card['description'] ?? '' }}</p>
                        </div>
                        <a class="read-more" href="{{ $card['url'] ?? '#' }}">
                            <span class="text">Vai alla pagina</span>
                            <svg class="icon ms-0">
                                <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right"></use>
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Events Calendar --}}
            <div class="row row-title pt-5 pt-lg-60 pb-3">
                <div class="col-12 d-lg-flex justify-content-between">
                    <h2>Eventi</h2>
                </div>
            </div>
            <div class="row row-calendar">
                <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols it-calendar-wrapper splide" data-bs-carousel-splide>
                    <div class="it-header-block">
                        <div class="it-header-block-title">
                            <h3 class="mb-0 text-center home-carousel-title">{{ $month }}</h3>
                        </div>
                    </div>
                    <div class="splide__track">
                        <ul class="splide__list it-carousel-all">
                            @foreach($slides as $slide)
                            <li class="splide__slide">
                                <div class="it-single-slide-wrapper h-100">
                                    <div class="card-wrapper h-100">
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <h4 class="card-title pb-4 mb-10 text-secondary">{{ $slide['day'] }}<span>{{ $slide['weekday'] }}</span></h4>
                                                @foreach($slide['events'] ?? [] as $event)
                                                @if(!empty($event['image']))
                                                <p class="card-text px-2 pb-10 mb-10 d-flex">
                                                    <img src="{{ $event['image'] }}" alt="random image" class="me-3 rounded">
                                                    <a href="{{ $event['url'] ?? '#' }}">{{ $event['title'] }}</a>
                                                </p>
                                                @else
                                                <p class="card-text px-2 pb-10 mb-10">
                                                    <a href="{{ $event['url'] ?? '#' }}">{{ $event['title'] }}</a>
                                                </p>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
