<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-06 - Amministrazione, fatture e presenze</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Back office economico e HR leggero: Fatture in Cloud, incassi con POS, timbrature, foglio ore mensile e export formulario Excel base.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Sincronizza Fatture in Cloud
                        </button>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                            Esporta formulario Excel
                        </button>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-4">
                    @foreach ([
                        ['label' => 'Fatture da emettere', 'value' => '11'],
                        ['label' => 'Incassi POS oggi', 'value' => '1.840 EUR'],
                        ['label' => 'Timbrature aperte', 'value' => '2'],
                        ['label' => 'Ore mese', 'value' => '1.276'],
                    ] as $item)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Coda amministrativa</h3>
                <div class="mt-5 space-y-3">
                    @foreach ([
                        ['title' => 'Intervento chiuso con POS', 'meta' => 'Hotel Blu - genera fattura immediata e invia email cliente.'],
                        ['title' => 'Rapportino da validare', 'meta' => 'Alfa Food - prodotti usati e firma cliente presenti.'],
                        ['title' => 'Foglio ore aprile', 'meta' => '2 tecnici con anomalie su pausa pranzo.'],
                        ['title' => 'Export formulario rifiuti', 'meta' => 'Preparazione Excel per conferimento base non RENTRI.'],
                    ] as $row)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $row['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $row['meta'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Integrazioni</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Fatture in Cloud per fatture attive e passive via API.',
                        'POS mobile collegato a intervento per incasso sul posto.',
                        'Export Excel per formulario base e riconciliazione documentale.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Presenze</h3>
                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach ([
                            ['name' => 'Marco Rossi', 'day' => '08:01 - 12:29 / 13:12 - 17:36'],
                            ['name' => 'Luca Bianchi', 'day' => '07:54 - 12:10 / 13:03 - 16:58'],
                            ['name' => 'Sara Neri', 'day' => '08:15 - pausa mancante'],
                        ] as $row)
                            <div class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                                <p class="font-semibold text-slate-950 dark:text-white">{{ $row['name'] }}</p>
                                <p class="mt-1">{{ $row['day'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
