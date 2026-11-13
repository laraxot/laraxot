{{-- Card notizia/evento della pagina argomento (eredita $cx dal blocco). $item = {category{label,url}, date, title, url, text, image?{src,alt,title}, calendar?{date,day}}. --}}
<div class="col-12 col-md-6 col-lg-4">
    <div class="card-wrapper">
        <div class="{{ $cx('card card-img no-after', empty($item['calendar']) ? 'rounded' : null, ! empty($item['image']) ? 'sm-row' : null) }}">
            @if (! empty($item['image']))
                <div class="img-responsive-wrapper">
                    <div class="img-responsive img-responsive-panoramic">
                        <figure class="img-wrapper"><img src="{{ $item['image']['src'] ?? '' }}" title="{{ $item['image']['title'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}"></figure>
                        @if (! empty($item['calendar']))
                            <div class="card-calendar d-flex flex-column justify-content-center">
                                <span class="card-date">{{ $item['calendar']['date'] ?? '' }}</span>
                                <span class="card-day">{{ $item['calendar']['day'] ?? '' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="card-body p-4">
                <div class="category-top">
                    <a class="text-decoration-none fw-semibold" href="{{ $item['category']['url'] ?? '#' }}">{{ $item['category']['label'] ?? '' }}</a>
                    <span class="data text-secondary">{{ $item['date'] ?? '' }}</span>
                </div>
                <h3 class="mb-0"><a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">{{ $item['title'] ?? '' }}</a></h3>
                <p class="pt-3 text-secondary">{{ $item['text'] ?? '' }}</p>
            </div>
        </div>
    </div>
</div>
