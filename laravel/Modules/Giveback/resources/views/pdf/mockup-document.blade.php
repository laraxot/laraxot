<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>{{ $document['title'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
        }

        .page {
            padding: 34px 38px;
        }

        .cover {
            background: #ecfdf5;
            border-bottom: 4px solid #10b981;
        }

        .eyebrow {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #047857;
            font-weight: bold;
        }

        h1 {
            font-size: 26px;
            margin: 10px 0 8px;
        }

        h2 {
            font-size: 18px;
            margin: 0 0 8px;
        }

        p {
            margin: 0 0 10px;
        }

        ul {
            margin: 10px 0 0 18px;
            padding: 0;
        }

        li {
            margin: 0 0 6px;
        }

        .summary {
            margin-top: 22px;
            padding: 16px 18px;
            background: #ffffff;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
        }

        .section {
            margin-top: 18px;
            padding: 18px 20px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
        }

        .section + .section {
            margin-top: 16px;
        }

        .lead {
            color: #334155;
        }

        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="page cover">
        <div class="eyebrow">GiveBack Platform</div>
        <h1>{{ $document['title'] }}</h1>
        <p>{{ $document['subtitle'] }}</p>

        <div class="summary">
            <div class="eyebrow">Punti chiave</div>
            <ul>
                @foreach ($document['summary'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="page">
        @foreach ($document['sections'] as $section)
            <div class="section">
                <h2>{{ $section['title'] }}</h2>
                <p class="lead">{{ $section['lead'] }}</p>
                <ul>
                    @foreach ($section['bullets'] as $bullet)
                        <li>{{ $bullet }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <p class="footer">
            PDF demo generato dal mockup GiveBack Platform. Documento orientato a presentazione commerciale e allineamento funzionale.
        </p>
    </div>
</body>
</html>
