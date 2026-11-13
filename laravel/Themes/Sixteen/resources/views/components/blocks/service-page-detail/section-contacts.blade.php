@props(['section' => []])
{{--
    Sezione "Contatti" del dettaglio servizio: card ufficio (titolo con link, righe di testo o link, avatar) e argomenti come chip
    con data di aggiornamento. Dati: card (title, url, element, lines[] con label e url opzionale, avatar src/alt), topics
    (title, element, items[]), updated.
--}}
@php
    $card = $section['card'] ?? [];
@endphp
<section class="it-page-section" id="{{ $section['id'] ?? '' }}">
    <h2 class="mb-3">{{ $section['title'] ?? '' }}</h2>
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 mb-30">
            <div class="card-wrapper rounded h-auto mt-10">
                <div class="card card-teaser card-teaser-info rounded shadow-sm p-3">
                    <div class="card-body pe-3">
                        <p class="card-title text-paragraph-regular-medium-semi mb-3">
                            <a class="text-decoration-none" href="{{ $card['url'] ?? '#' }}" data-element="{{ $card['element'] ?? '' }}">{{ $card['title'] ?? '' }}</a>
                        </p>
                        <div class="card-text">
                            @foreach ($card['lines'] ?? [] as $line)
                                <p class="u-main-black">@if (filled($line['url'] ?? null))<a href="{{ $line['url'] }}">{{ $line['label'] ?? '' }}</a>@else{{ $line['label'] ?? '' }}@endif</p>
                            @endforeach
                        </div>
                    </div>
                    <div class="avatar size-xl">
                        <img src="{{ $card['avatar']['src'] ?? '' }}" alt="{{ $card['avatar']['alt'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-30">
            <span class="text-paragraph-small">{{ $section['topics']['title'] ?? '' }}</span>
            <ul class="d-flex flex-wrap gap-2 mt-10 mb-3">
                @foreach ($section['topics']['items'] ?? [] as $topic)
                    <li><a class="chip chip-simple" href="#" data-element="{{ $section['topics']['element'] ?? '' }}"><span class="chip-label">{{ $topic }}</span></a></li>
                @endforeach
            </ul>
            <p class="text-paragraph-small mb-0">{{ $section['updated'] ?? '' }}</p>
        </div>
    </div>
</section>
