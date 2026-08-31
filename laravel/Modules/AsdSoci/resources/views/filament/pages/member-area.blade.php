<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(340px,1fr)]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">UI-08</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Area socio da telefono</h2>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Vista personale semplice: stato quota, documenti, rinnovo, dati ammessi e prossimi eventi.
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Mobile first</span>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950 dark:text-white">Mario Rossi</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Socio attivo · Tessera 2026</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Quota ok</span>
                    </div>

                    <div class="mt-5 grid gap-3">
                        @foreach ([
                            ['label' => 'Stagione corrente', 'value' => '2026'],
                            ['label' => 'Ultimo pagamento', 'value' => '05/01/2026'],
                            ['label' => 'Ricevuta', 'value' => 'Ric. 01'],
                            ['label' => 'Prossimo evento', 'value' => 'Track Day Adria'],
                        ] as $item)
                            <div class="flex items-center justify-between rounded-2xl bg-white px-4 py-3 dark:bg-slate-900">
                                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $item['label'] }}</span>
                                <span class="text-sm font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni socio</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <button type="button" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-left font-semibold text-white">Rinnova quota</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Scarica ricevuta</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Aggiorna dati ammessi</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Vedi eventi</button>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Documenti e storico</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Ricevuta quota 2026',
                            'Conferma iscrizione stagione 2026',
                            'Storico adesioni eventi',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
