{{-- Card "teaser" della pagina argomento. $item = {category{label,url}, title, url, text, image?{src,alt}}. --}}
<div class="card card-teaser card-teaser-image card-flex no-after rounded shadow-sm border border-light mb-0">
    <div class="card-image-wrapper with-read-more">
        <div class="card-body p-3">
            <div class="category-top"><a href="{{ $item['category']['url'] ?? '#' }}" class="title-xsmall-semi-bold fw-semibold text-decoration-none">{{ $item['category']['label'] ?? '' }}</a></div>
            <h4 class="card-title u-grey-light"><a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none">{{ $item['title'] ?? '' }}</a></h4>
            <p class="text-paragraph-card u-grey-light m-0">{{ $item['text'] ?? '' }}</p>
        </div>
        @if (! empty($item['image']))
            <div class="card-image card-image-rounded"><img src="{{ $item['image']['src'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}"></div>
        @endif
    </div>
</div>
