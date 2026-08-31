<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-05 - App tecnico, SAL e rapportino</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Interfaccia mobile-first per completare fasi di cantiere, registrare ore e materiali extra, chiudere il rapportino.
                </p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-slate-950 p-5 shadow-sm dark:border-slate-700">
                <div class="mx-auto max-w-sm rounded-[2rem] bg-white p-5 shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Intervento in corso</p>
                            <p class="text-sm font-semibold text-slate-950">Installazione caldaia - Bianchi</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">65%</span>
                    </div>

                    <div class="mt-5 h-3 rounded-full bg-slate-100">
                        <div class="h-3 rounded-full bg-orange-500" style="width: 65%"></div>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ([
                            ['step' => 'Smontaggio vecchio impianto', 'status' => 'Completato'],
                            ['step' => 'Posa tubazioni', 'status' => 'In corso'],
                            ['step' => 'Collaudo', 'status' => 'Da fare'],
                            ['step' => 'Foto finali e firma', 'status' => 'Da fare'],
                        ] as $row)
                            <div class="rounded-2xl border border-slate-200 px-4 py-3">
                                <p class="text-sm font-semibold text-slate-950">{{ $row['step'] }}</p>
                                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">{{ $row['status'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Rapportino giornaliero</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        ['label' => 'Ore lavorate', 'value' => '7,5'],
                        ['label' => 'Materiale extra', 'value' => '2 raccordi, 1 curva rame'],
                        ['label' => 'Nota vocale AI', 'value' => 'Trascrizione da confermare'],
                        ['label' => 'Esito', 'value' => 'Proseguire domani mattina'],
                    ] as $row)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $row['label'] }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $row['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni rapide</h3>
                <div class="mt-4 flex flex-col gap-3">
                    <button type="button" class="rounded-xl bg-orange-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-500">
                        Detto nota intervento
                    </button>
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Aggiungi materiale extra
                    </button>
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Chiudi giornata
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
