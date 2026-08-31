<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    @include('wts::pdf.partials.unit-label-styles')
    <style>
        /* Una etichetta per pagina, stesso layout della stampa singola. */
        .label { page-break-after: always; }
        .label:last-child { page-break-after: auto; }
    </style>
</head>
<body>
    @forelse ($labels as $entry)
        @include('wts::pdf.partials.unit-label-content', [
            'unit' => $entry['unit'],
            'lot' => $entry['lot'],
            'record' => $entry['record'],
            'sheet' => $entry['sheet'],
            'qrSvgDataUri' => $entry['qrSvgDataUri'],
        ])
    @empty
        <p style="margin: 24px;">Nessun collo da stampare.</p>
    @endforelse
</body>
</html>
