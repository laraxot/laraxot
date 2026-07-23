<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isDemoRegistration ? 'Registrazione demo' : 'Richiesta accesso' }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
<div class="mx-auto flex min-h-screen max-w-md items-center px-4">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70 ring-1 ring-slate-100">
        <h1 class="text-xl font-semibold text-slate-950">{{ $isDemoRegistration ? 'Crea account demo' : 'Richiedi accesso' }}</h1>
        <p class="mt-1 text-sm text-slate-600">
            @if ($isDemoRegistration)
                Usa la tua email: riceverai un codice per completare l'accesso.
            @else
                Crea la richiesta: un amministratore dovra approvarla prima che tu possa accedere.
            @endif
        </p>

        <form method="POST" action="{{ route('account.register.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-800" for="name">Nome</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-950 placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
                @error('name')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800" for="email">{{ __('auth.fields.email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-950 placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
                @error('email')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800" for="password">{{ __('auth.fields.password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-950 placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
                @error('password')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-800" for="password_confirmation">Conferma password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-950 placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
            </div>

            <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700">
                <input id="accept_legal" name="accept_legal" type="checkbox" value="1" required @checked(old('accept_legal'))
                       class="mt-0.5 rounded border-slate-300 bg-white"/>
                <span>
                    {{ __('auth.fields.accept_legal_prefix') }}
                    <a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="font-medium text-slate-950 underline decoration-slate-300 underline-offset-2 hover:decoration-slate-950">
                        {{ __('auth.fields.privacy_policy') }}
                    </a>
                    {{ __('auth.fields.accept_legal_middle') }}
                    <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="font-medium text-slate-950 underline decoration-slate-300 underline-offset-2 hover:decoration-slate-950">
                        {{ __('auth.fields.terms_and_conditions') }}
                    </a>.
                </span>
            </label>
            @error('accept_legal')
            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
            @enderror

            <button type="submit"
                    class="w-full rounded-lg bg-slate-950 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                {{ $isDemoRegistration ? 'Registrati e verifica email' : 'Invia richiesta accesso' }}
            </button>
        </form>

        <div class="mt-4 text-sm text-slate-600">
            Hai gia un account?
            <a href="{{ route('legacy.login') }}" class="font-semibold text-slate-950 underline decoration-slate-300 underline-offset-2 hover:decoration-slate-950">Accedi</a>.
        </div>
    </div>
</div>
</body>
</html>
