<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            margin: 24px;
        }

        .label {
            border: 4px solid #0f172a;
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
            color: #64748b;
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
                    <img class="qr" src="{{ $qrSvgDataUri }}" alt="QR WTS">
                </td>
                <td style="vertical-align: top;">
                    <p class="title">{{ $material->output_code }}</p>

                    <div class="muted">Tipo output</div>
                    <div class="value">{{ $material->output_type_code }}</div>

                    <div class="muted">Contenitore</div>
                    <div class="value">{{ $material->container_type_code }}</div>

                    <div class="muted">Foglio lavorazione</div>
                    <div class="value">{{ $processingStep?->work_sheet_code ?? 'N/D' }}</div>

                    <div class="muted">CER / EER</div>
                    <div class="value">{{ $sheet?->waste_code ?? 'N/D' }}</div>
                </td>
            </tr>
        </table>

        <div class="payload">{{ $payload }}</div>
    </div>
</body>
</html>
