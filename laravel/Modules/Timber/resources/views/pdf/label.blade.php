<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1c1917;
            margin: 24px;
        }

        .label {
            border: 4px solid #78350f;
            border-radius: 18px;
            padding: 24px;
        }

        .qr {
            width: 260px;
            height: 260px;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 12px;
        }

        .grid {
            width: 100%;
        }

        .muted {
            color: #78716c;
            font-size: 12px;
            text-transform: uppercase;
        }

        .value {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .payload {
            margin-top: 18px;
            font-size: 10px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="label">
        <table class="grid">
            <tr>
                <td style="width: 290px; vertical-align: top;">
                    <img class="qr" src="{{ $qrSvgDataUri }}" alt="QR Timber">
                </td>
                <td style="vertical-align: top;">
                    <p class="title">
                        {{ $labelable->lot_code ?? $labelable->output_code ?? $labelable->package_code ?? 'N/D' }}
                    </p>

                    <div class="muted">Specie legnosa</div>
                    <div class="value">{{ $labelable->wood_species_name ?: '-' }}</div>

                    <div class="muted">Quantita</div>
                    <div class="value">
                        {{ number_format((float) ($labelable->quantity_available ?? $labelable->quantity ?? 0), 2, ',', '.') }}
                        {{ $labelable->unit_of_measure ?? '' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="payload">{{ $payload }}</div>
    </div>
</body>
</html>
