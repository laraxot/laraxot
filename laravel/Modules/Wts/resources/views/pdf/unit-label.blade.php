<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    @include('wts::pdf.partials.unit-label-styles')
</head>
<body>
    @include('wts::pdf.partials.unit-label-content', [
        'unit' => $unit,
        'lot' => $lot,
        'record' => $record,
        'sheet' => $sheet,
        'qrSvgDataUri' => $qrSvgDataUri,
    ])
</body>
</html>
