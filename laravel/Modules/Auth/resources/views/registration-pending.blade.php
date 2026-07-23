<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Richiesta in attesa</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
<div class="mx-auto flex min-h-screen max-w-md items-center px-4">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-lg shadow-slate-200/70 ring-1 ring-slate-100">
        <h1 class="text-xl font-semibold text-slate-950">Richiesta inviata</h1>
        <p class="mt-2 text-sm text-slate-600">
            Il tuo account e in attesa di approvazione. Potrai accedere solo dopo che un amministratore avra approvato la richiesta.
        </p>

        <a href="{{ route('legacy.login') }}"
           class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
            Torna al login
        </a>
    </div>
</div>
</body>
</html>
