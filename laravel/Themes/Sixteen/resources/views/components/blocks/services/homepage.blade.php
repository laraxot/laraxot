{{--
    Services Homepage Block
    Usage: <x-blocks.services.homepage :services="$data['services']" :title="$data['title']" />
--}}

@props(['services' => [], 'title' => ''])

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
            <div class="row g-4">
                @foreach($services as $service)
                <div class="col-sm-6 col-lg-4">
                    <div class="it-grid-item-wrapper it-grid-item-overlay">
                        <a href="{{ $service['url'] }}" class="text-decoration-none">
                            <div class="card card-bg card-teaser shadow">
                                <div class="card-body">
                                    <div class="category-top">
                                        <svg class="icon icon-primary" aria-hidden="true">
                                            <use href="{{ asset('themes/sixteen/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#' . ($service['icon'] ?? 'it-services')) }}"></use>
                                        </svg>
                                        <span class="text">Servizio</span>
                                    </div>
                                    <h3 class="card-title h5">{{ $service['title'] }}</h3>
                                    <p class="card-text">{{ $service['description'] ?? '' }}</p>
                                    <a href="{{ $service['url'] }}" class="read-more">
                                        <span class="text">Leggi di più</span>
                                        <svg class="icon icon-primary icon-xs" aria-hidden="true">
                                            <use href="{{ asset('themes/sixteen/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right') }}"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a class="btn btn-outline-primary" href="/it/tests/servizi">
                    Tutti i servizi
                    <svg class="icon icon-sm">
                        <use href="{{ asset('themes/sixteen/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right') }}"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
