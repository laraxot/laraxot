@props(['section' => []])
{{--
    Sezione di card semplici (categorie, bandi) Design Comuni. `section`: title, heading (xxlarge|secondary), id opzionale,
    variant (grid = griglia con bordo, compact = riga larga senza bordo), link_element (data-element del link), described
    (true = classe `description` sul testo) e items[] (title, text, url). Testi e link dal JSON; le classi sono quelle del riferimento.
--}}
@php
    $compact = ($section['variant'] ?? 'grid') === 'compact';
    $headings = ['xxlarge' => 'title-xxlarge mb-4', 'secondary' => 'text-secondary mb-4'];
    $headingClass = $compact ? 'title-xxlarge mt-40 mb-40 mt-lg-60 mb-lg-40' : ($headings[$section['heading'] ?? 'xxlarge'] ?? $headings['xxlarge']);
@endphp
<div @class(['container', 'py-5' => ! $compact])@if (filled($section['id'] ?? null)) id="{{ $section['id'] }}"@endif>
    <h2 class="{{ $headingClass }}">{{ $section['title'] ?? '' }}</h2>
    <div @class(['row g-4' => ! $compact, 'row flex-wrap justify-content-between gy-4 gy-lg-5 gx-lg-5 pb-40 pb-lg-60 align-items-stretch' => $compact])>
        @foreach ($section['items'] ?? [] as $item)
            <div @class(['col-md-6 col-xl-4' => ! $compact, 'col-12 col-md-6 col-lg-4' => $compact])>
                <div @class(['cmp-card-simple card-wrapper pb-0 rounded', 'border border-light' => ! $compact])>
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none"@if (filled($section['link_element'] ?? null)) data-element="{{ $section['link_element'] }}"@endif>
                                <h3 class="card-title t-primary title-xlarge">{{ $item['title'] ?? '' }}</h3></a>
                            <p @class(['text-secondary mb-0', 'description' => (bool) ($section['described'] ?? false)])>{{ $item['text'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
