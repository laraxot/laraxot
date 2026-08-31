<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(360px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">UI-01 / UI-02</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Landing iscrizione e form domanda</h2>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Mockup mobile-first per nuovo socio e rinnovo annuale. L'obiettivo e ridurre i passaggi e raccogliere subito i dati
                            minimi coerenti con il libro soci.
                        </p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-400/20 dark:text-amber-100">Demo responsive</span>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <button type="button" class="rounded-2xl bg-amber-500 px-5 py-4 text-left font-semibold text-white shadow-sm">Nuova iscrizione</button>
                    <button type="button" class="rounded-2xl border border-slate-300 px-5 py-4 text-left font-semibold text-slate-800 dark:border-slate-700 dark:text-slate-100">Rinnova quota</button>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                    <div class="space-y-4">
                        @foreach ([
                            'Nome e cognome',
                            'Luogo e data di nascita',
                            'Codice fiscale',
                            'Indirizzo di residenza e CAP',
                            'Email e telefono',
                            'Qualifica / note',
                            'Patente o licenza sportiva',
                        ] as $field)
                            <div>
                                <p class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $field }}</p>
                                <div class="h-11 rounded-xl border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-900"></div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-950 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100">
                        <div class="mt-0.5 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                        <p>Nel flusso reale qui entrano privacy, consensi e controllo duplicato su codice fiscale o email.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Passi utente</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ([
                            ['step' => '01', 'title' => 'Compila domanda', 'body' => 'L’utente inserisce anagrafica, contatti e dati richiesti.'],
                            ['step' => '02', 'title' => 'Invia richiesta', 'body' => 'La pratica entra in stato in attesa di verifica.'],
                            ['step' => '03', 'title' => 'Segreteria verifica', 'body' => 'Controlla dati mancanti e possibili duplicati.'],
                            ['step' => '04', 'title' => 'Passa a delibera', 'body' => 'Solo le pratiche complete arrivano al libro soci.'],
                        ] as $item)
                            <div class="flex gap-3 rounded-2xl bg-slate-50 p-4 dark:bg-slate-800">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white dark:bg-slate-100 dark:text-slate-950">{{ $item['step'] }}</span>
                                <div>
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ $item['title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $item['body'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Stati pratica</h3>
                    <div class="mt-4 flex flex-wrap gap-2 text-sm">
                        @foreach (['bozza', 'in attesa di verifica', 'da integrare', 'pronta per delibera'] as $status)
                            <span class="rounded-full bg-slate-100 px-3 py-2 text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $status }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
