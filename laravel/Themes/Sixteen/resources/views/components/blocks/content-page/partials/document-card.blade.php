{{-- Card di un documento (categoria con icona, titolo, testo). Parametri: $item = {category{label,url,icon}, title, text, url, element}, $col = classi della colonna. --}}
<div class="{{ $col }}">
    <div class="card-wrapper border border-light rounded shadow-sm pb-0">
        <div class="card no-after rounded">
            <div class="card-body rounded-top">
                <div class="categoryicon-top">
                    <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $icon($item['category']['icon'] ?? 'it-file') }}"></use></svg>
                    <span class="text fw-semibold"><a class="category text-decoration-none" href="{{ $item['category']['url'] ?? '#' }}">{{ $item['category']['label'] ?? '' }}</a></span>
                </div>
                <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none"@if (filled($item['element'] ?? null)) data-element="{{ $item['element'] }}"@endif><h3 class="card-title h4">{{ $item['title'] ?? '' }}</h3></a>
                <p class="text-secondary mb-0">{{ $item['text'] ?? '' }}</p>
            </div>
        </div>
    </div>
</div>
