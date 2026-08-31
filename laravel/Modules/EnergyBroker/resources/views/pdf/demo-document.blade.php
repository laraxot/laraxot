<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        .header { border-bottom: 3px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px; }
        .badge { display: inline-block; padding: 4px 8px; border: 1px solid #93c5fd; color: #1d4ed8; font-size: 10px; font-weight: bold; }
        .title { margin-top: 10px; font-size: 22px; font-weight: bold; }
        .muted { color: #64748b; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .grid td { width: 50%; vertical-align: top; padding-right: 8px; }
        .box { border: 1px solid #dbeafe; padding: 10px; margin-bottom: 10px; background: #f8fafc; }
        .items { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .items th, .items td { border-bottom: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        .items th { background: #eff6ff; color: #1e3a8a; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #cbd5e1; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <span class="badge">{{ $document['badge'] }}</span>
        <div class="title">{{ $document['title'] }}</div>
        <div class="muted">Numero: {{ $document['number'] }} - Data: {{ $document['date'] }}</div>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="box">
                    <strong>Intestatario</strong>
                    <div style="margin-top: 6px;">{{ $document['customer'] }}</div>
                    <div class="muted">CF/P.IVA: {{ $document['tax_code'] }}</div>
                    <div class="muted">{{ $document['address'] }}</div>
                </div>
            </td>
            <td>
                <div class="box">
                    <strong>Fornitore</strong>
                    <div style="margin-top: 6px;">{{ $document['supplier'] }}</div>
                    @if (! empty($document['offer_code']))
                        <div class="muted">Codice offerta: {{ $document['offer_code'] }}</div>
                    @endif
                    @if (! empty($document['period']))
                        <div class="muted">Periodo fatturato: {{ $document['period'] }}</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="box">
        <strong>Punti di fornitura</strong>
        <div style="margin-top: 6px;">POD: {{ $document['pod'] }} - PDR: {{ $document['pdr'] }}</div>
        @if (! empty($document['annual_power']))
            <div class="muted">Consumo annuo luce: {{ $document['annual_power'] }} - Consumo annuo gas: {{ $document['annual_gas'] }} - Potenza: {{ $document['power'] }}</div>
        @endif
        @if (! empty($document['duration']))
            <div class="muted">Durata: {{ $document['duration'] }} - Pagamento: {{ $document['payment'] }}</div>
        @endif
        @if (! empty($document['total']))
            <div class="muted">Totale bolletta: {{ $document['total'] }}</div>
        @endif
    </div>

    <table class="items">
        <thead>
        <tr>
            <th>Voce</th>
            <th>Dettaglio</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($document['rows'] as $row)
            <tr>
                <td>{{ $row['label'] }}</td>
                <td>{{ $row['value'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $document['notes'] }}
    </div>
</body>
</html>
