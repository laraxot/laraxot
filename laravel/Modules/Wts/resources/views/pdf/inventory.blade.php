@php
    // Totale riassuntivo richiesto per verificare i limiti giornalieri di giacenza.
    $totalWeight = collect($rows)->sum(fn ($row): float => (float) ($row->weightKg ?? 0));
    $totalColli = collect($rows)->sum(fn ($row): int => (int) ($row->colliCount ?? 0));
@endphp
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 16px 16px 34px 16px; }
        body { font-family: DejaVu Sans, sans-serif; margin: 0; color: #0f172a; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        .meta { color: #475569; font-size: 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 5px; font-size: 8px; text-align: left; vertical-align: top; }
        th { background: #f1f5f9; font-size: 8px; }
        .num { text-align: right; white-space: nowrap; }
        .totals td { background: #f1f5f9; font-weight: 700; font-size: 9px; }
        .note { color: #475569; }
        .pagefoot { position: fixed; bottom: -24px; left: 0; right: 0; text-align: right; font-size: 8px; color: #475569; }
        .pagefoot .pnum:after { content: counter(page); }
        .pagefoot .pcount:after { content: counter(pages); }
    </style>
</head>
<body>
    <div class="pagefoot">Pagina <span class="pnum"></span> di <span class="pcount"></span></div>

    <h1>Giacenza WTS</h1>
    <p class="meta">
        Riferimento: {{ $referenceDate ?: now()->toDateString() }}
        - Fonte: {{ $usesEcosStock ? 'ECOS per partita' : 'Colli WTS locali' }} ({{ $dataSource['label'] }})
        - Righe {{ $stats['total'] }}
        - Disponibili {{ $stats['available'] }}
        - Consumati {{ $stats['consumed'] }}
    </p>
    <p class="meta">
        Emesso il {{ now()->format('d/m/Y H:i') }}
        - Documento gestionale interno WTS: non sostituisce i registri di carico/scarico e i formulari previsti dal D.Lgs. 152/2006, tenuti sui sistemi ufficiali.
    </p>
    <table>
        <thead>
            <tr>
                <th>Partita / sub</th>
                <th>Scheda tecnica</th>
                <th>Descrizione scheda</th>
                <th>Produttore</th>
                <th>HP</th>
                <th>Stato fisico</th>
                <th>Tratt.</th>
                <th>Linea / Ciclo</th>
                <th>CER</th>
                <th class="num">Colli</th>
                <th class="num">Peso</th>
                <th>Zona</th>
                <th>Formulario</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->batchCode }} / {{ $row->unitSubCode }}</td>
                    <td>{{ $row->sheetCode ?? 'N/D' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($row->sheetDescription ?? $row->productionDescription ?? $row->packagingDescription ?? 'N/D', 60) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($row->producerName ?? '—', 40) }}</td>
                    <td>{{ $row->hpCodes ?? '—' }}</td>
                    <td>
                        {{ $row->physicalStateCode ?? '—' }}
                        @if ($row->physicalStateLabel !== null)
                            <span class="note">{{ $row->physicalStateLabel }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $row->treatmentCode ?? '—' }}</strong>
                        @if ($row->treatmentLabel !== null)
                            <span class="note">{{ \Illuminate\Support\Str::limit($row->treatmentLabel, 24) }}</span>
                        @endif
                    </td>
                    <td>{{ trim(($row->treatmentLineCode ?? '').' / '.($row->productionCycleCode ?? ''), ' /') ?: '—' }}</td>
                    <td>{{ $row->wasteCode ?? 'N/D' }}</td>
                    <td class="num">{{ $row->colliCount ?? '—' }}</td>
                    <td class="num">{{ $row->weightKg !== null ? number_format($row->weightKg, 2, ',', '.') : 'N/D' }}</td>
                    <td>{{ $row->zoneLabel ?? $row->zoneCode ?? 'N/D' }}</td>
                    <td>{{ $row->formReference ?? '—' }}</td>
                    <td class="note">
                        @if ($row->note1 || $row->note2)
                            {{ \Illuminate\Support\Str::limit(trim(($row->note1 ?? '').' '.($row->note2 ?? '')), 50) }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="14">Nessuna riga in giacenza</td></tr>
            @endforelse
        </tbody>
        @if (count($rows) > 0)
            <tfoot>
                <tr class="totals">
                    <td colspan="9">TOTALE ({{ count($rows) }} partite)</td>
                    <td class="num">{{ number_format($totalColli, 0, ',', '.') }}</td>
                    <td class="num">{{ number_format($totalWeight, 2, ',', '.') }} kg</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
