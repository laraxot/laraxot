{{-- Sezione "In evidenza" con immagini e titolo sovrapposto `it-grid-item-overlay`. $section = {title, col_class, items[{title,url,image{src,alt,title},rounded?}]}. --}}
<h2 class="title-xxlarge mb-4">{{ $section['title'] ?? '' }}</h2>
<div class="row g-4">
    @foreach ($section['items'] ?? [] as $item)
        <div class="{{ $section['col_class'] ?? 'col-sm-6 col-lg-4' }}">
            <div class="it-grid-item-wrapper it-grid-item-overlay">
                <a href="{{ $item['url'] ?? '#' }}">
                    <div class="img-responsive-wrapper">
                        <div class="{{ $cx('img-responsive', ! empty($item['rounded']) ? 'rounded' : null) }}">
                            <div class="img-wrapper"><img src="{{ $item['image']['src'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}" title="{{ $item['image']['title'] ?? '' }}"></div>
                        </div>
                    </div>
                    <div class="it-griditem-text-wrapper"><h3>{{ $item['title'] ?? '' }}</h3></div>
                </a>
            </div>
        </div>
    @endforeach
</div>
