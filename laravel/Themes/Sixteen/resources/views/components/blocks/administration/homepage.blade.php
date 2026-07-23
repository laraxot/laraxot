{{--
    Administration Homepage Block
    Usage: <x-blocks.administration.homepage :items="$data['items']" :title="$data['title']" />
--}}

@props(['items' => [], 'title' => ''])

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
            <div class="row g-4">
                @foreach($items as $item)
                <div class="col-sm-6 col-lg-4">
                    <div class="it-grid-item-wrapper">
                        <a href="{{ $item['url'] }}" class="text-decoration-none">
                            <div class="card card-bg card-teaser shadow">
                                <div class="card-body">
                                    <h3 class="card-title h5">{{ $item['title'] }}</h3>
                                    <p class="card-text">{{ $item['description'] ?? '' }}</p>
                                    <a href="{{ $item['url'] }}" class="read-more">
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
                <a class="btn btn-outline-primary" href="/it/tests/amministrazione">
                    Tutta l'amministrazione
                    <svg class="icon icon-sm">
                        <use href="{{ asset('themes/sixteen/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-arrow-right') }}"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
