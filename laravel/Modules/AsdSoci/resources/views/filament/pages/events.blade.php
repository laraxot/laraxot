<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-300">UI-07</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Calendario eventi e adesioni</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        Questa schermata racconta il lato organizzativo: pubblicazione evento, adesione socio, eventuale quota e lista partecipanti.
                    </p>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['title' => 'Track Day Adria', 'date' => '20/06/2026', 'place' => 'Adria Raceway', 'quota' => '25 euro', 'status' => 'Pubblicato'],
                        ['title' => 'Cena soci estate', 'date' => '05/07/2026', 'place' => 'Treviso', 'quota' => '15 euro', 'status' => 'Pubblicato'],
                        ['title' => 'Giornata sponsor', 'date' => '12/07/2026', 'place' => 'Padova', 'quota' => 'Solo invito', 'status' => 'Bozza'],
                        ['title' => 'Weekend pista Misano', 'date' => '03/09/2026', 'place' => 'Misano', 'quota' => '80 euro', 'status' => 'Pubblicato'],
                    ] as $event)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ $event['title'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $event['date'] }} · {{ $event['place'] }}</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $event['status'] }}</span>
                            </div>
                            <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">Quota: {{ $event['quota'] }}</p>
                            <button type="button" class="mt-4 w-full rounded-xl bg-amber-500 px-4 py-3 text-left font-semibold text-white">Apri adesioni</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Lista partecipanti demo</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ([
                            ['name' => 'Laura Bianchi', 'status' => 'Pagato'],
                            ['name' => 'Mario Rossi', 'status' => 'Iscritto'],
                            ['name' => 'Anna Bortolato', 'status' => 'Lista attesa'],
                        ] as $row)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                                <span class="font-medium text-slate-950 dark:text-white">{{ $row['name'] }}</span>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ $row['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni organizzative</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <button type="button" class="w-full rounded-xl bg-amber-500 px-4 py-3 text-left font-semibold text-white">Crea evento</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Pubblica evento</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Esporta partecipanti</button>
                        <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Segna presenti</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
