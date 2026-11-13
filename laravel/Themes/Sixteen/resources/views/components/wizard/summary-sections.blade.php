{{--
    Gruppi del riepilogo (dati: $review di BuildTicketReviewSummaryAction): "Segnalazione" con la scheda del disservizio (righe, link
    "Modifica", mappa) e, per l'utente autenticato, "Dati Generali" con autore e contatti. Righe e riquadri sono report-partials/info-summary.
    La mappa (`<x-geo::point-map>`, sola lettura, con il suo aria-label e l'alternativa testuale) sta in un nodo `data-dc-map` (display:none
    nei gate di parity) come ultimo elemento della scheda; non c'e' senza coordinate (l'indirizzo resta nella riga). Contratto: latitude, longitude, address, label.
--}}
@php
    $report = $review['report'] ?? [];
    $author = $review['author'] ?? null;
    $contacts = $review['contacts'] ?? null;
    $editStep = (string) ($report['edit_step'] ?? '');
@endphp
<h2 class="title-xxlarge mb-4 mt-40">{{ $report['title'] ?? '' }}</h2>
<div class="cmp-card mb-4">
    <div class="card has-bkg-grey shadow-sm mb-0">
        <div class="card-header border-0 p-0 mb-lg-20 m-0">
            <div class="d-flex"><h3 class="subtitle-large mb-4">{{ $report['card_title'] ?? '' }}</h3></div>
        </div>
        <div class="card-body p-0">
            @include($parts.'.info-summary', ['summary' => [
                'class' => 'p-3 p-lg-4',
                'edit' => ['label' => $report['edit_label'] ?? '', 'url' => '?'.http_build_query(['step' => $editStep]), 'hidden' => false, 'click' => $wired ? "goToStep('".$editStep."')" : null],
                'footer_hidden' => false,
                'rows' => $report['rows'] ?? [],
            ]])
            @include($wizard.'.report-partials.images-preview')
            @if (filled($report['location'] ?? null))
                <div data-dc-map>
                    <div class="mt-3">
                        <x-geo::point-map :latitude="$report['location']['latitude']" :longitude="$report['location']['longitude']" :address="$report['location']['address']" :label="$report['map_label'] ?? null" />
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@if (filled($author))
    <h2 class="title-xxlarge mb-4 mt-40">{{ $author['group_title'] ?? '' }}</h2>
    <div class="cmp-card mb-4">
        <div class="card has-bkg-grey shadow-sm mb-0">
            <div class="card-header border-0 p-0 mb-lg-20 m-0">
                <div class="d-flex"><h3 class="subtitle-large mb-4">{{ $author['card_title'] ?? '' }}</h3></div>
            </div>
            <div class="card-body p-0">
                @include($parts.'.info-summary', ['summary' => [
                    'class' => (filled($contacts) ? 'mb-4 mb-lg-30 ' : '').'p-3 p-lg-4',
                    'title' => $author['name'] ?? '',
                    'rows' => $author['rows'] ?? [],
                ]])
                @if (filled($contacts))
                    @include($parts.'.info-summary', ['summary' => [
                        'class' => 'p-3 p-lg-4',
                        'title' => $contacts['title'] ?? '',
                        'rows' => $contacts['rows'] ?? [],
                    ]])
                @endif
            </div>
        </div>
    </div>
@endif
