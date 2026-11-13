{{-- Sezione di card semplici `cmp-card-simple`. $section = {title, col_class, title_class?, text_class?, items[{title,text,url,element?}]}. --}}
<h2 class="title-xxlarge mb-4">{{ $section['title'] ?? '' }}</h2>
<div class="row g-4">
    @foreach ($section['items'] ?? [] as $item)
        <div class="{{ $section['col_class'] ?? 'col-md-6 col-xl-4' }}">
            <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none"@if (filled($item['element'] ?? null)) data-element="{{ $item['element'] }}"@endif><h3 class="{{ $cx('card-title t-primary', $section['title_class'] ?? null) }}">{{ $item['title'] ?? '' }}</h3></a>
                        <p class="{{ $cx('text-secondary mb-0', $section['text_class'] ?? null) }}">{{ $item['text'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
