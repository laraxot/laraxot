<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.login_title') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
<div class="mx-auto flex min-h-screen max-w-md items-center px-4">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/70 ring-1 ring-slate-100">
        <h1 class="text-xl font-semibold text-slate-950">{{ __('auth.login_title') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('auth.login_subtitle') }}</p>

        <form method="POST" action="{{ route('legacy.login.store') }}" class="mt-6 space-y-4">
            @csrf

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
                <div class="relative mt-1">
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 pr-10 text-slate-950 placeholder:text-slate-400 shadow-sm focus:border-primary-500 focus:ring-primary-500"/>
                    <button type="button" onclick="(function(btn){var i=document.getElementById('password');var show=i.type==='password';i.type=show?'text':'password';btn.querySelector('.eye-off').classList.toggle('hidden',show);btn.querySelector('.eye-on').classList.toggle('hidden',!show);})(this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700" tabindex="-1" aria-label="Mostra/nascondi password">
                        <svg class="eye-on size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg class="eye-off size-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('password')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
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
                {{ __('auth.actions.login') }}
            </button>
        </form>

        @php
            $registrationBlockedHosts = config('auth.registration.blocked_hosts', []);
            $registrationIsBlocked = is_array($registrationBlockedHosts) && in_array(request()->getHost(), $registrationBlockedHosts, true);
        @endphp

        @if (! $registrationIsBlocked)
            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                {{ config('app.demo_mode') ? 'Vuoi provare iTER7?' : 'Non hai ancora un account?' }}
                <a href="{{ route('account.register') }}" class="font-semibold text-slate-950 underline decoration-slate-300 underline-offset-2 hover:decoration-slate-950">
                    {{ config('app.demo_mode') ? 'Crea un account demo' : 'Richiedi accesso' }}
                </a>.
            </div>
        @endif

        <div class="mt-4 text-xs text-slate-500">
            {{ __('auth.hint_panels') }}
        </div>
    </div>
</div>
</body>
</html>

