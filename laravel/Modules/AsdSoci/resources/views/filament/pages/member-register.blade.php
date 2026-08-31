<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.45fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">UI-03 / UI-04</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Backoffice domande, delibere e libro soci</h2>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Questa schermata mostra il cuore del progetto: dalla pratica in coda si arriva al socio attivo con progressivo libro soci
                            e storico consultabile.
                        </p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Coerente con Excel cliente</span>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="grid gap-2 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 md:grid-cols-6">
                        <div>N. ordine</div>
                        <div>Socio</div>
                        <div>Data domanda</div>
                        <div>Data delibera</div>
                        <div>Quota</div>
                        <div>Stato</div>
                    </div>
                    @foreach ([
                        ['order' => '001', 'name' => 'Rossi Mario', 'application' => '02/01/2026', 'board' => '05/01/2026', 'fee' => '50 euro', 'status' => 'Attivo'],
                        ['order' => '002', 'name' => 'Bianchi Laura', 'application' => '10/01/2026', 'board' => '15/01/2026', 'fee' => '50 euro', 'status' => 'Attivo'],
                        ['order' => '003', 'name' => 'Verdi Giacomo', 'application' => '15/01/2026', 'board' => '20/01/2026', 'fee' => '50 euro', 'status' => 'Cessato'],
                    ] as $row)
                        <div class="grid gap-2 border-t border-slate-200 px-4 py-4 text-sm first:border-t-0 dark:border-slate-700 md:grid-cols-6">
                            <div class="font-semibold text-slate-950 dark:text-white">{{ $row['order'] }}</div>
                            <div class="text-slate-700 dark:text-slate-200">{{ $row['name'] }}</div>
                            <div class="text-slate-600 dark:text-slate-300">{{ $row['application'] }}</div>
                            <div class="text-slate-600 dark:text-slate-300">{{ $row['board'] }}</div>
                            <div class="text-slate-600 dark:text-slate-300">{{ $row['fee'] }}</div>
                            <div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $row['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni segreteria</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <button type="button" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-left font-semibold text-white">Apri pratica</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Richiedi integrazione</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Registra delibera</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Registra cessazione</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Esporta libro soci</button>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Campi chiave libro soci</h3>
                    <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Numero progressivo',
                            'Data domanda',
                            'Data delibera consiglio',
                            'Codice fiscale',
                            'Quota versata',
                            'Data e numero ricevuta',
                            'Data cessazione e causa',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
