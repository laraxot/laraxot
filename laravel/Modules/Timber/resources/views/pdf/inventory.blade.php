<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 24px; color: #1c1917; }
        h1 { font-size: 24px; margin: 0 0 8px; }
        h2 { font-size: 16px; margin: 24px 0 8px; }
        .meta { color: #57534e; font-size: 12px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #d6d3d1; padding: 8px; font-size: 11px; text-align: left; vertical-align: top; }
        th { background: #fafaf9; }
    </style>
</head>
<body>
    <h1>Giacenza legname</h1>
    <p class="meta">
        Riferimento: {{ now()->toDateString() }}
        - Lotti disponibili {{ $stats['lots_available'] }}
        - Da lavorare {{ $stats['materials_to_process'] }}
        - In essiccazione {{ $stats['materials_drying'] }}
        - Pronto da confezionare {{ $stats['materials_ready_to_package'] }}
        - Pronto per vendita {{ $stats['materials_ready_to_sell'] }}
        - Confezioni pronte {{ $stats['packaged_units_ready'] }}
        - Scarti totali {{ number_format((float) $stats['total_waste_quantity'], 2, ',', '.') }}
    </p>

    <h2>Lotti di ingresso</h2>
    <table>
        <thead>
            <tr>
                <th>Lotto</th>
                <th>Specie</th>
                <th>Stato</th>
                <th>Area</th>
                <th>Quantita disponibile</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lots as $lot)
                <tr>
                    <td>{{ $lot->lot_code }}</td>
                    <td>{{ $lot->wood_species_name }}</td>
                    <td>{{ $lot->status_code }}</td>
                    <td>{{ $lot->current_area_code }}</td>
                    <td>{{ number_format((float) $lot->quantity_available, 2, ',', '.') }} {{ $lot->unit_of_measure }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Nessun lotto</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Materiali derivati</h2>
    <table>
        <thead>
            <tr>
                <th>Materiale</th>
                <th>Specie</th>
                <th>Stato</th>
                <th>Area</th>
                <th>Quantita</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($materials as $material)
                <tr>
                    <td>{{ $material->output_code }}</td>
                    <td>{{ $material->wood_species_name }}</td>
                    <td>{{ $material->status_code }}</td>
                    <td>{{ $material->current_area_code }}</td>
                    <td>{{ number_format((float) $material->quantity, 2, ',', '.') }} {{ $material->unit_of_measure }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Nessun materiale</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Prodotti confezionati</h2>
    <table>
        <thead>
            <tr>
                <th>Confezione</th>
                <th>Tipo</th>
                <th>Stato</th>
                <th>Quantita</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($packagedUnits as $unit)
                <tr>
                    <td>{{ $unit->package_code }}</td>
                    <td>{{ $unit->package_type_code }}</td>
                    <td>{{ $unit->status_code }}</td>
                    <td>{{ number_format((float) $unit->quantity, 2, ',', '.') }} {{ $unit->unit_of_measure }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Nessuna confezione</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
