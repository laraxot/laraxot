<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 24px; color: #0f172a; }
        h1 { font-size: 24px; margin: 0 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; font-size: 11px; text-align: left; }
        th { background: #f1f5f9; }
        .meta { font-size: 11px; color: #475569; margin: 0 0 12px; }
    </style>
</head>
<body>
    <h1>Report lavorazioni WTS</h1>
    <p class="meta">
        Emesso il {{ now()->format('d/m/Y H:i') }}
        - Documento gestionale interno WTS: non sostituisce i registri di carico/scarico e i formulari previsti dal D.Lgs. 152/2006, tenuti sui sistemi ufficiali.
    </p>
    <table>
        <thead>
            <tr>
                <th>Foglio</th>
                <th>Tipo</th>
                <th>Colli</th>
                <th>Peso input</th>
                <th>Output</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($steps as $step)
                <tr>
                    <td>{{ $step->work_sheet_code }}</td>
                    <td>{{ $step->step_type_code }}</td>
                    <td>{{ $step->selectedUnits->count() }}</td>
                    <td>{{ $step->input_weight ?? 'N/D' }}</td>
                    <td>{{ $step->derivedMaterials->pluck('output_code')->implode(', ') ?: 'N/D' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Nessuna lavorazione registrata</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
