<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 24px; color: #1c1917; }
        h1 { font-size: 24px; margin: 0 0 8px; }
        h2 { font-size: 16px; margin: 24px 0 8px; }
        .meta { color: #57534e; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #d6d3d1; padding: 8px; font-size: 11px; text-align: left; vertical-align: top; }
        th { background: #fafaf9; }
    </style>
</head>
<body>
    <h1>Tracciabilita legname</h1>

    <h2>Acquisto</h2>
    <p class="meta">
        Fornitore: {{ $purchaseRecord?->supplier?->legal_name ?? 'N/D' }}
        - Provenienza: {{ $purchaseRecord?->origin_forest ?? 'N/D' }}
        - DDT: {{ $purchaseRecord?->ddt_number ?? 'N/D' }}
    </p>

    <h2>Lotto</h2>
    <p class="meta">
        {{ $lot?->lot_code ?? 'N/D' }} - Specie {{ $lot?->wood_species_name ?? 'N/D' }}
        - Stato {{ $lot?->status_code ?? 'N/D' }} - Area {{ $lot?->current_area_code ?? 'N/D' }}
    </p>

    <h2>Lavorazioni</h2>
    <table>
        <thead>
            <tr>
                <th>Foglio</th>
                <th>Tipo</th>
                <th>Input</th>
                <th>Output</th>
                <th>Scarto</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($processingSteps as $step)
                <tr>
                    <td>{{ $step->work_sheet_code }}</td>
                    <td>{{ $step->step_type_code }}</td>
                    <td>{{ number_format((float) $step->input_quantity, 2, ',', '.') }}</td>
                    <td>{{ number_format((float) $step->output_quantity, 2, ',', '.') }}</td>
                    <td>{{ number_format((float) $step->waste_quantity, 2, ',', '.') }}</td>
                    <td>{{ optional($step->executed_at)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Nessuna lavorazione registrata</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Materiale derivato</h2>
    <p class="meta">{{ $derivedMaterial?->output_code ?? 'N/D' }} - Stato {{ $derivedMaterial?->status_code ?? 'N/D' }}</p>

    <h2>Confezione</h2>
    <p class="meta">{{ $packagedUnit?->package_code ?? 'N/D' }} - Tipo {{ $packagedUnit?->package_type_code ?? 'N/D' }}</p>
</body>
</html>
