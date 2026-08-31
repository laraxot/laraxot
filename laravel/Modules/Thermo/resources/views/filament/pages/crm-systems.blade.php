<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(360px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-02 - CRM, clienti, impianti e documenti</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Anagrafica cliente con ricerca rapida durante la creazione intervento, storico impianto e area documentale.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Scheda cliente</p>
                        <h3 class="mt-1 text-xl font-semibold text-slate-950 dark:text-white">Condominio Le Rose</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Via Veneto 16, Scorze - referente: Marco Perini</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">Cliente attivo</span>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    @foreach ([
                        ['label' => 'Impianti censiti', 'value' => '4'],
                        ['label' => 'Documenti', 'value' => '18'],
                        ['label' => 'Interventi storici', 'value' => '27'],
                    ] as $metric)
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $metric['label'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $metric['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        'Caldaia centrale - matricola da verificare',
                        'Addolcitore vano tecnico - manutenzione semestrale',
                        'Circuito radiatori scala A - storico lavaggi',
                        'Scaldabagno appartamento custode - sostituito 2025',
                    ] as $system)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ $system }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Area documenti cliente</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Dichiarazioni di conformita e allegati obbligatori.',
                        'Preventivi firmati e PDF non modificabili.',
                        'Foto sopralluogo e album fine lavori.',
                        'Libretti, targhette, note tecniche e documenti OCR.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Ricerca in inserimento</h3>
                <div class="mt-4 rounded-xl border border-orange-200 bg-orange-50 px-4 py-3 text-sm text-orange-950 dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-100">
                    Durante la creazione di un intervento il sistema suggerisce clienti, sedi e impianti esistenti per evitare doppioni.
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
