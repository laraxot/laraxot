{{--
    Card notizia Design Comuni (sito/novita.html, «In evidenza»): immagine panoramica, categoria, data, titolo, abstract.
    Caller: components.blocks.home.news. Input: $card = [title, url, image, excerpt, date, categoryLabel, categoryUrl].
    Il titolo resta l'unico link verso la notizia; l'immagine ha alt testuale perche' non e' dentro il link.
--}}
<div class="col-md-6 col-xl-4">
    <div class="card-wrapper border border-light rounded shadow-sm">
        <div class="card no-after rounded">
            @if (($card['image'] ?? '') !== '')
                <div class="img-responsive-wrapper">
                    <div class="img-responsive img-responsive-panoramic">
                        <figure class="img-wrapper">
                            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" loading="lazy">
                        </figure>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <div class="category-top">
                    <a class="category text-decoration-none" href="{{ $card['categoryUrl'] }}">{{ $card['categoryLabel'] }}</a>
                    @if (($card['date'] ?? '') !== '')
                        <span class="data">{{ $card['date'] }}</span>
                    @endif
                </div>
                <a href="{{ $card['url'] }}" class="text-decoration-none" data-element="news-category-link">
                    <h3 class="card-title">{{ $card['title'] }}</h3>
                </a>
                @if (($card['excerpt'] ?? '') !== '')
                    <p class="card-text text-secondary">{{ $card['excerpt'] }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
