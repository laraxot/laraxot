<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(360px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-03 - Accessi account, registrazione e MFA</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Area prevista dal preventivo per responsabilizzare ogni accesso: email reale, registrazione obbligatoria, codice MFA e recupero password.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ([
                        ['title' => 'Email reale', 'desc' => 'Ogni utente usa una mail nominativa e verificabile.'],
                        ['title' => 'Codice MFA', 'desc' => 'Codice di accesso inviato via email o cellulare.'],
                        ['title' => 'Recupero password', 'desc' => 'Ripristino tramite email o cellulare registrato.'],
                    ] as $card)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <p class="text-base font-semibold text-slate-950 dark:text-white">{{ $card['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $card['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-950 shadow-sm dark:border-slate-800">
                <h3 class="text-base font-semibold">Anteprima registrazione light</h3>
                <div class="mt-4 space-y-3">
                    @foreach (['Nome', 'Email vera', 'Password', 'Conferma password'] as $field)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">{{ $field }}</div>
                    @endforeach
                    <button type="button" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white">
                        Registrati e verifica email
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 text-slate-950 shadow-sm dark:border-slate-800">
                <h3 class="text-base font-semibold">Codice MFA leggibile</h3>
                <div class="mt-4 rounded-xl border border-slate-200 bg-white px-4 py-5 text-center font-mono text-4xl font-semibold tracking-[0.35em] text-black">
                    123456
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
