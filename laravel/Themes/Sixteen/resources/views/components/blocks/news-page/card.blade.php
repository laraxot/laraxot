@props(['item' => [], 'assets' => ''])
{{-- Card notizia della pagina Novita (Design Comuni): immagine opzionale, categoria, data, titolo e testo. Dati dal JSON. --}}
<div class="col-md-6 col-xl-4">
    <div class="card-wrapper border border-light rounded shadow-sm">
        <div class="card no-after rounded">
            @if (filled($item['image'] ?? null))
                <div class="img-responsive-wrapper">
                    <div class="img-responsive img-responsive-panoramic"><figure class="img-wrapper">
                        <img class="" src="{{ $assets }}/images/{{ $item['image']['file'] ?? '' }}" title="{{ $item['image']['title'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}"></figure>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <div class="category-top">
                    <a class="category text-decoration-none" href="{{ $item['category']['url'] ?? '#' }}">{{ $item['category']['label'] ?? '' }}</a>
                    <span class="data">{{ $item['date'] ?? '' }}</span>
                </div>
                <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none" data-element="news-category-link"><h3 class="card-title">{{ $item['title'] ?? '' }}</h3></a>
                <p class="card-text text-secondary">{{ $item['text'] ?? '' }}</p>
            </div>
        </div>
    </div>
</div>
