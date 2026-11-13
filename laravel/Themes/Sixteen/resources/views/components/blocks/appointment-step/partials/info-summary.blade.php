{{--
    Riquadro riepilogo (cmp-info-summary): titolo, link "Modifica" opzionale e righe etichetta/valore. Parametri: $class (classi del riquadro),
    $title, $rows[label, value], $edit (testo del link, null = nessun link), $editClass, $footerClass.
--}}
<div class="cmp-info-summary {{ $class }}">
    <div class="card">
        <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-between d-flex justify-content-end">
            <h4 class="title-large-semi-bold mb-3">{{ $title }}</h4>
            @if (filled($edit ?? null))
                <a href="#" class="{{ $editClass ?? 'text-decoration-none' }}"><span class="text-button-sm-semi t-primary">{{ $edit }}</span></a>
            @endif
        </div>
        <div class="card-body p-0">
            @foreach ($rows ?? [] as $row)
                <div class="single-line-info border-light">
                    <div class="text-paragraph-small">{{ $row['label'] ?? '' }}</div>
                    <div class="border-light"><p class="data-text"> {{ $row['value'] ?? '' }} </p></div>
                </div>
            @endforeach
        </div>
        <div class="card-footer {{ $footerClass ?? 'p-0' }}"></div>
    </div>
</div>
