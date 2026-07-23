@props(['title' => 'Ultime notizie', 'news' => []])

{{--
    News Carousel Block
    
    Usage:
    "view": "pub_theme::components.blocks.news.carousel",
    "title": "Ultime notizie",
    "news": [
        {
            "title": "Nuovo servizio digitale",
            "date": "2026-04-01",
            "excerpt": "Attivato nuovo servizio online per...",
            "url": "/it/novita/nuovo-servizio"
        }
    ]
    
    Docs: docs/design-comuni/blocks/00-index.md
--}}

<section class="news-carousel-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title">{{ $title }}</h2>
                
                <div class="news-carousel">
                    @foreach($news as $item)
                        <div class="news-card">
                            <div class="news-date">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</div>
                            <h3 class="news-title">
                                <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                            </h3>
                            @if(isset($item['excerpt']))
                                <p class="news-excerpt">{{ $item['excerpt'] }}</p>
                            @endif
                            <a href="{{ $item['url'] }}" class="btn btn-link">
                                Leggi di più
                                <svg class="icon icon-primary icon-sm">
                                    <use href="#it-arrow-right"></use>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
