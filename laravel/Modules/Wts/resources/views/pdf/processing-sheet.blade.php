<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; margin: 24px; }
        h1 { font-size: 24px; margin: 0 0 8px; }
        h2 { font-size: 16px; margin: 24px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; font-size: 11px; text-align: left; }
        th { background: #f1f5f9; text-transform: uppercase; }
        .meta { font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <h1>Foglio lavorazione WTS {{ $step->work_sheet_code }}</h1>
    <p class="meta">Tipo: {{ $step->step_type_code }} - Eseguita: {{ $step->executed_at?->format('d/m/Y H:i') ?? 'N/D' }}</p>
    <p class="meta">Peso input: {{ $step->input_weight ?? 'N/D' }} kg - Peso output: {{ $step->output_weight ?? 'N/D' }} kg - Rimanenza: {{ $step->remainder_weight ?? 'N/D' }} kg</p>

    <h2>Colli lavorati</h2>
    <table>
        <thead>
            <tr>
                <th>Collo</th>
                <th>Lotto</th>
                <th>Formulario</th>
                <th>CER</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($selectedUnits as $unit)
                <tr>
                    <td>{{ $unit->unit_code }}</td>
                    <td>{{ $unit->inboundLot?->lot_code ?? 'N/D' }}</td>
                    <td>{{ $unit->inboundLot?->inboundRecord?->form_number ?? 'N/D' }}</td>
                    <td>{{ $unit->inboundLot?->inboundRecord?->technicalSheetSnapshot?->waste_code ?? 'N/D' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Nessun collo collegato</td></tr>
            @endforelse
        </tbody>
    </table>

    @php
        $typeLabels = [
            'drum' => 'Fusto',
            'dirty_drum' => 'Fusto sporco',
            'pallet' => 'Bancale',
            'deferred_iron' => 'Ferro deferizzato',
            'generated_liquid' => 'Liquido generato',
        ];
        $outputTotal = (float) $derivedMaterials->sum('weight');
        $remainder = $step->remainder_weight !== null ? (float) $step->remainder_weight : null;
        $input = $step->input_weight !== null ? (float) $step->input_weight : null;
        $totalUscite = $outputTotal + ($remainder ?? 0.0);
        $balance = $input !== null ? $input - $totalUscite : null;
        $balanced = $balance !== null && abs($balance) < 0.01;
        $kg = static fn ($v): string => number_format((float) $v, 2, ',', '.') . ' kg';
    @endphp

    <h2>Uscite (materiali derivati + rimanenza)</h2>
    <table>
        <thead>
            <tr>
                <th>Materiale</th>
                <th>CER / EER</th>
                <th>Quantita</th>
                <th>Peso</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($derivedMaterials as $material)
                <tr>
                    <td>{{ $typeLabels[$material->output_type_code] ?? $material->output_type_code }}</td>
                    <td>{{ $material->technicalSheetSnapshot?->waste_code ?? '—' }}</td>
                    <td>{{ $material->quantity }}</td>
                    <td>{{ $material->weight !== null ? $kg($material->weight) : 'N/D' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Nessun materiale derivato</td></tr>
            @endforelse

            {{-- Rimanenza sul rifiuto: puo' avere un CER diverso da quello di ingresso. --}}
            <tr>
                <td>Rimanenza sul rifiuto</td>
                <td>{{ $step->remainder_waste_code ?? '—' }}</td>
                <td>—</td>
                <td>{{ $remainder !== null ? $kg($remainder) : 'N/D' }}</td>
            </tr>

            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold; background: #f8fafc;">Totale uscite</td>
                <td style="font-weight: bold; background: #f8fafc;">{{ $kg($totalUscite) }}</td>
            </tr>
        </tbody>
    </table>

    <h2>Bilancio di massa</h2>
    <p class="meta">
        Ingresso (peso lavorato): <strong>{{ $input !== null ? $kg($input) : 'N/D' }}</strong>
        &minus; Uscite: <strong>{{ $kg($totalUscite) }}</strong>
        = <strong>{{ $balance !== null ? $kg($balance) : 'N/D' }}</strong>
    </p>
    <p class="meta" style="font-weight: bold; color: {{ $balanced ? '#15803d' : '#b91c1c' }};">
        {{ $balanced ? 'BILANCIATO (ingressi - uscite = 0)' : 'SBILANCIATO — verificare i pesi (ingressi - uscite deve fare 0)' }}
    </p>

    <h2>Note</h2>
    <p class="meta">{{ $step->notes ?: 'Nessuna nota operativa' }}</p>

    <p class="meta">
        Emesso il {{ now()->format('d/m/Y H:i') }}
        - Documento gestionale interno WTS: non sostituisce i registri di carico/scarico e i formulari previsti dal D.Lgs. 152/2006, tenuti sui sistemi ufficiali.
    </p>
</body>
</html>
