<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifica accesso</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
<div class="mx-auto flex min-h-screen max-w-md items-center px-4">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70 ring-1 ring-slate-100">
        <h1 class="text-xl font-semibold text-slate-950">Verifica accesso</h1>
        <p class="mt-1 text-sm text-slate-600">
            Abbiamo inviato un codice a 6 cifre alla tua email. Inseriscilo per continuare.
        </p>

        @if (session('status'))
            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('email-two-factor.verify') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-800" for="code">Codice email</label>
                <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-4 text-center font-mono text-4xl font-semibold tracking-[0.35em] text-black placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
                @error('code')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                    class="w-full rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                Conferma accesso
            </button>
        </form>

        <form method="POST" action="{{ route('email-two-factor.resend') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                Invia un nuovo codice
            </button>
        </form>

        <form method="POST" action="{{ route('legacy.logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="w-full text-sm text-slate-600 underline decoration-slate-300 underline-offset-2 hover:text-slate-950">
                Annulla accesso
            </button>
        </form>
    </div>
</div>
</body>
</html>
