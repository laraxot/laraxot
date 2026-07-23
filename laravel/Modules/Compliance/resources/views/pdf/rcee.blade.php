<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 11px; color: #111; margin: 24px; }
        h1 { font-size: 16px; margin: 0 0 2px; }
        .muted { color: #666; font-size: 10px; }
        .sec { margin-top: 14px; }
        .sec h2 { font-size: 12px; background: #f0f0f0; padding: 4px 6px; margin: 0 0 6px; border-left: 3px solid #444; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 3px 6px; vertical-align: top; }
        .kv td:first-child { color: #555; width: 38%; }
        .data th { background: #fafafa; border: 1px solid #ddd; text-align: left; }
        .data td { border: 1px solid #ddd; }
        .badge { padding: 2px 6px; border-radius: 3px; font-weight: bold; }
        .ok { background: #e6f4ea; color: #1e7e34; }
        .ko { background: #fdecea; color: #b71c1c; }
    </style>
</head>
<body>
    @php($system = $control->heatingSystem)
    @php($boiler = $control->boiler)
    <h1>Rapporto di controllo di efficienza energetica</h1>
    <div class="muted">
        {{ $control->control_type === 'efficiency_check' ? 'Controllo di efficienza energetica (RCEE)' : 'Manutenzione ordinaria' }}
        · Data: {{ $control->performed_on?->format('d/m/Y') }}
        @if ($control->rcee_type) · {{ $control->rcee_type }} @endif
    </div>

    <div class="sec">
        <h2>Impianto e cliente</h2>
        <table class="kv">
            <tr><td>Cliente</td><td>{{ $system?->customer?->legal_name ?? '—' }}</td></tr>
            <tr><td>Sede/Ubicazione</td><td>{{ $system?->site?->name ?? $system?->description ?? '—' }}</td></tr>
            <tr><td>Codice catasto impianto</td><td>{{ $system?->plant_registry_code ?? '—' }}</td></tr>
            <tr><td>Combustibile impianto</td><td>{{ $system?->fuel_type ?? '—' }}</td></tr>
        </table>
    </div>

    <div class="sec">
        <h2>Generatore</h2>
        <table class="kv">
            <tr><td>Marca / Modello</td><td>{{ trim(($boiler?->brand ?? '').' '.($boiler?->model ?? '')) ?: '—' }}</td></tr>
            <tr><td>Matricola</td><td>{{ $boiler?->serial_number ?? '—' }}</td></tr>
            <tr><td>Tipo</td><td>{{ $boiler?->generator_type ?? '—' }}</td></tr>
            <tr><td>Potenza</td><td>{{ $boiler?->power_kw ? $boiler->power_kw.' kW' : '—' }}</td></tr>
        </table>
    </div>

    <div class="sec">
        <h2>Analisi di combustione</h2>
        <table class="data">
            <tr>
                <th>Rendimento</th><th>CO₂</th><th>O₂</th><th>CO</th><th>T fumi</th><th>Indice fumo</th>
            </tr>
            <tr>
                <td>{{ $control->combustion_efficiency_percent !== null ? $control->combustion_efficiency_percent.' %' : '—' }}</td>
                <td>{{ $control->co2_percent !== null ? $control->co2_percent.' %' : '—' }}</td>
                <td>{{ $control->o2_percent !== null ? $control->o2_percent.' %' : '—' }}</td>
                <td>{{ $control->co_ppm !== null ? $control->co_ppm.' ppm' : '—' }}</td>
                <td>{{ $control->flue_gas_temp_c !== null ? $control->flue_gas_temp_c.' °C' : '—' }}</td>
                <td>{{ $control->smoke_index !== null ? $control->smoke_index : '—' }}</td>
            </tr>
        </table>
    </div>

    <div class="sec">
        <h2>Esito</h2>
        <table class="kv">
            <tr><td>Esito controllo</td><td>
                @if ($control->outcome === 'conforme')
                    <span class="badge ok">CONFORME</span>
                @elseif ($control->outcome === 'non_conforme')
                    <span class="badge ko">NON CONFORME</span>
                @else — @endif
            </td></tr>
            <tr><td>Prossima scadenza</td><td>{{ $control->next_due_on?->format('d/m/Y') ?? '—' }}</td></tr>
            <tr><td>Bollino</td><td>{{ $control->bollino_code ?: '—' }} {{ $control->bollino_paid ? '(pagato)' : '' }}</td></tr>
            @if ($control->notes)<tr><td>Note</td><td>{{ $control->notes }}</td></tr>@endif
        </table>
    </div>

    <div class="sec muted" style="margin-top: 30px;">
        Documento generato automaticamente dal gestionale. Firma del tecnico e del responsabile d'impianto da apporre sul libretto.
    </div>
</body>
</html>
