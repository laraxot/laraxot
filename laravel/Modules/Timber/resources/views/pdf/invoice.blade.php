<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }
        h1 { font-size: 18px; margin: 0 0 2px; }
        .muted { color: #666; }
        .row { width: 100%; }
        .col { display: inline-block; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 5px 6px; text-align: left; }
        th { background: #f2f2f2; }
        td.num, th.num { text-align: right; }
        .totals { margin-top: 12px; width: 45%; float: right; }
        .totals td { border: none; padding: 2px 6px; }
        .totals .grand { font-weight: bold; font-size: 13px; border-top: 1px solid #999; }
    </style>
</head>
<body>
    @php($money = fn (float $v): string => number_format($v, 2, ',', '.').' €')

    <table style="border: none; margin: 0;">
        <tr>
            <td style="border: none; width: 55%; vertical-align: top;">
                <h1>{{ $company['name'] ?? 'Azienda' }}</h1>
                <div class="muted">
                    {{ $company['address'] ?? '' }}<br>
                    {{ $company['postal_code'] ?? '' }} {{ $company['city'] ?? '' }} ({{ $company['province'] ?? '' }})<br>
                    P.IVA {{ $company['vat_number'] ?? '' }}
                </div>
            </td>
            <td style="border: none; width: 45%; vertical-align: top; text-align: right;">
                <h1>{{ $kindLabel }}</h1>
                <div>N. <strong>{{ $invoice->number ?? '—' }}</strong></div>
                <div>Data {{ \Illuminate\Support\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <table style="border: none; margin-top: 10px;">
        <tr>
            <td style="border: none;">
                <strong>Cliente</strong><br>
                {{ $invoice->customer?->business_name ?? '—' }}<br>
                @if ($invoice->customer)
                    {{ $invoice->customer->address_line1 }}<br>
                    {{ $invoice->customer->postal_code }} {{ $invoice->customer->city }} ({{ $invoice->customer->province }})<br>
                    P.IVA {{ $invoice->customer->vat_number }}
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Descrizione</th>
                <th class="num">Q.ta</th>
                <th>U.m.</th>
                <th class="num">Prezzo</th>
                <th class="num">Sc. %</th>
                <th class="num">IVA %</th>
                <th class="num">Totale</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->lines as $line)
                <tr>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ number_format((float) $line->quantity, 2, ',', '.') }}</td>
                    <td>{{ $line->unit_of_measure }}</td>
                    <td class="num">{{ $money((float) $line->unit_price) }}</td>
                    <td class="num">{{ (float) $line->discount_percent > 0 ? number_format((float) $line->discount_percent, 0) : '' }}</td>
                    <td class="num">{{ number_format((float) $line->vat_rate, 0) }}</td>
                    <td class="num">{{ $money((float) $line->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Imponibile</td><td class="num">{{ $money($totals->taxable->asFloat()) }}</td></tr>
        <tr><td>IVA</td><td class="num">{{ $money($totals->vat->asFloat()) }}</td></tr>
        <tr><td class="grand">Totale documento</td><td class="num grand">{{ $money($totals->total->asFloat()) }}</td></tr>
    </table>

    @if ($invoice->notes)
        <div style="clear: both; margin-top: 20px;" class="muted">{{ $invoice->notes }}</div>
    @endif
</body>
</html>
