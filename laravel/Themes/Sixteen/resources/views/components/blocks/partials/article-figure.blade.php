{{--
  Immagine di un Article (figura principale o blocco image nel corpo), markup Design Comuni.
  Callers: partials/article-body (diretto per la figura principale, via <x-render.block> per i blocchi image).
  Input: $src, $alt, $caption, $credit (stringhe), $lead (bool: prima immagine, caricata con priorita').
--}}
@php
    $src = isset($src) && is_string($src) ? $src : '';
    $alt = isset($alt) && is_string($alt) ? $alt : '';
    $caption = isset($caption) && is_string($caption) ? $caption : '';
    $credit = isset($credit) && is_string($credit) ? $credit : '';
    $lead = isset($lead) && $lead === true;
@endphp
@if ($src !== '')
    <figure class="mb-4 blog-article-detail__media">
        <img src="{{ $src }}" alt="{{ $alt }}" class="img-fluid w-100 rounded" width="1200" height="675" @if ($lead) fetchpriority="high" @else loading="lazy" @endif>
        @if ($caption !== '' || $credit !== '')
            <figcaption class="figure-caption text-center pt-3">
                @if ($caption !== '')
                    {{ $caption }}
                @endif
                @if ($credit !== '')
                    <span class="d-block text-muted small">{{ $credit }}</span>
                @endif
            </figcaption>
        @endif
    </figure>
@endif
