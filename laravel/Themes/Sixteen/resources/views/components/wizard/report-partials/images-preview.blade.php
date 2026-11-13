{{--
    Anteprime delle foto caricate nel passo dei dati (DCP-182). Dati: `$report['image_previews']` (URL firmati di Livewire, solo jpg/jpeg/png),
    `$report['image_alts']` (testo alternativo, stesso ordine) e `$report['images_label']`: tutti prodotti da BuildTicketReviewSummaryAction,
    qui nessun testo. Senza anteprime non emette nulla, cosi' l'HTML del riepilogo resta identico al riferimento sito/segnalazione-03-riepilogo.
    Classi gia' presenti nello scope Design Comuni del CSS compilato (row, col-6, col-md-4, mb-3, mt-3, img-fluid, rounded, border): vedi il piano
    docs/blueprints/riepilogo-anteprima-foto.md. I nomi dei file sono input del cittadino: solo `{{ }}`, mai `{!! !!}`.
--}}
@php
    $previews = array_values((array) ($report['image_previews'] ?? []));
    $alts = array_values((array) ($report['image_alts'] ?? []));
@endphp
@if ($previews !== [])
    <div class="row mt-3" data-element="review-attachments" role="list" aria-label="{{ $report['images_label'] ?? '' }}">
        @foreach ($previews as $index => $url)
            <div class="col-6 col-md-4 mb-3" role="listitem">
                <img class="img-fluid rounded border" src="{{ $url }}" alt="{{ $alts[$index] ?? '' }}" width="320" height="240" loading="lazy" decoding="async">
            </div>
        @endforeach
    </div>
@endif
