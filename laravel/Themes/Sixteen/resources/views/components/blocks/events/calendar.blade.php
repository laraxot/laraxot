{{--
    Events Calendar Block - Splide carousel
    Reference: design-comuni-pagine-statiche/src/pages/sito/homepage.hbs #calendario section
--}}
@props([
    'month' => '',
    'items' => [],
])

<div class="row row-title pt-5 pt-lg-60 pb-3">
    <div class="col-12 d-lg-flex justify-content-between">
        <h2>Eventi</h2>
    </div>
</div>
<div class="row row-calendar">
    <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols it-calendar-wrapper splide"
        data-bs-carousel-splide>
        <div class="it-header-block">
            <div class="it-header-block-title">
                <h3 class="mb-0 text-center home-carousel-title">{{ $month }}</h3>
            </div>
        </div>
        <div class="splide__track">
            <ul class="splide__list it-carousel-all">
                @foreach($items as $item)
                <li class="splide__slide">
                    <div class="it-single-slide-wrapper h-100">
                        <div class="card-wrapper h-100">
                            <div class="card card-bg">
                                <div class="card-body">
                                    <h4 class="card-title pb-4 mb-10 text-secondary">{{ $item['day'] }}<span>{{ $item['weekday'] }}</span></h4>
                                    @foreach($item['events'] as $event)
                                    @if(!empty($event['image']))
                                    <p class="card-text px-2 pb-10 mb-10 d-flex">
                                        <img src="{{ $event['image'] }}" alt="random image" class="me-3 rounded">
                                        <a href="{{ $event['url'] ?? '#' }}">{{ $event['title'] }}</a>
                                    </p>
                                    @else
                                    <p class="card-text px-2 pb-10 mb-10"><a href="{{ $event['url'] ?? '#' }}">{{ $event['title'] }}</a></p>
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
