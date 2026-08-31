<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-4">
            @foreach ([
                ['label' => 'Quote stagione', 'value' => '142'],
                ['label' => 'Entrate mese', 'value' => '8.420 euro'],
                ['label' => 'Uscite mese', 'value' => '3.180 euro'],
                ['label' => 'Ricevute emesse', 'value' => '157'],
            ] as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">UI-05 / UI-06</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Tesoreria operativa</h2>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Mockup della parte quote, ricevute, entrate e uscite. Serve a far capire il flusso operativo della ASD senza promettere
                            una contabilita completa.
                        </p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">Fatture in Cloud: base</span>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ([
                        ['type' => 'Quota associativa', 'subject' => 'Rossi Mario', 'amount' => '50 euro', 'date' => '05/01/2026'],
                        ['type' => 'Sponsorizzazione', 'subject' => 'Moto Service SRL', 'amount' => '1.500 euro', 'date' => '09/01/2026'],
                        ['type' => 'Assicurazione', 'subject' => 'Polizza annuale', 'amount' => '620 euro', 'date' => '11/01/2026'],
                        ['type' => 'Vendita merchandising', 'subject' => 'Felpa team', 'amount' => '35 euro', 'date' => '12/01/2026'],
                    ] as $row)
                        <div class="grid gap-3 rounded-2xl border border-slate-200 p-4 dark:border-slate-700 md:grid-cols-[1.2fr_1fr_auto_auto]">
                            <div>
                                <p class="font-semibold text-slate-950 dark:text-white">{{ $row['type'] }}</p>
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $row['subject'] }}</p>
                            </div>
                            <div class="text-sm text-slate-600 dark:text-slate-300">{{ $row['date'] }}</div>
                            <div class="text-sm font-semibold text-slate-950 dark:text-white">{{ $row['amount'] }}</div>
                            <div><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Registrato</span></div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Categorie incluse</h3>
                    <div class="mt-4 grid gap-2 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Quote associative',
                            'Tesseramenti',
                            'Sponsorizzazioni',
                            'Versamenti liberali',
                            'Vendita merchandising',
                            'Banca, telefono, imposte, assicurazioni, affitti',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni rapide</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <button type="button" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-left font-semibold text-white">Nuova quota</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Genera ricevuta</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Nuova uscita</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Prepara per fatturazione</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
