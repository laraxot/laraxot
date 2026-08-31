<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-05 - Portale clienti e documenti</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Area riservata web per clienti con storico interventi, contratti, rapportini firmati, planimetrie, conformita e notifiche di scadenza.
                    </p>
                </div>
                <span class="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 dark:bg-sky-400/20 dark:text-sky-100">Ruolo: dds_customer</span>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(340px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Documenti disponibili</h3>
                <div class="mt-5 grid gap-3">
                    @foreach ([
                        ['title' => 'Contratto annuale 2026', 'meta' => 'Firmato da cliente e ufficio', 'cta' => 'Scarica PDF'],
                        ['title' => 'Rapportino 18/04/2026', 'meta' => 'Intervento ordinario - firma tecnico + cliente', 'cta' => 'Apri rapportino'],
                        ['title' => 'Planimetria punti di controllo', 'meta' => 'Versione aggiornata con trappole e hotspot', 'cta' => 'Apri mappa'],
                        ['title' => 'Schede sicurezza prodotti', 'meta' => 'Archivio SDS e conformita', 'cta' => 'Scarica ZIP'],
                    ] as $doc)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $doc['title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $doc['meta'] }}</p>
                                </div>
                                <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $doc['cta'] }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Card cliente</h3>
                    <div class="mt-4 space-y-4 text-sm">
                        @foreach ([
                            ['label' => 'Sede', 'value' => 'Alfa Food - Stabilimento Nord'],
                            ['label' => 'Prossimo intervento', 'value' => '22/04/2026 - 08:00'],
                            ['label' => 'Contratto', 'value' => '12 interventi / anno'],
                            ['label' => 'Stato servizio', 'value' => 'In regola'],
                        ] as $item)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-1 font-medium text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Funzioni del portale</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Consultazione documenti, firme e rapportini.',
                            'Storico interventi con allegati multimediali selezionati.',
                            'Download export e gestione richieste al back office.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
