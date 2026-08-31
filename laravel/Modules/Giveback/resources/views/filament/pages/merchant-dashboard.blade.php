<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-4">
            @foreach ([
                ['label' => 'Tentativi mese', 'value' => '428'],
                ['label' => 'Coupon disponibili', 'value' => '1.240'],
                ['label' => 'Ticket premio', 'value' => '428'],
                ['label' => 'Conversione widget', 'value' => '8,7%'],
            ] as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Scrivania del negozio</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Qui il negoziante vede se tutto e pronto. Non deve capire parole tecniche: deve solo vedere cosa va bene e cosa manca.
                        </p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">Mock dati pilot</span>
                </div>
                <div class="mt-5 space-y-3">
                    @foreach ([
                        ['name' => 'Codici promo', 'status' => 'Pronto', 'desc' => 'Hai ancora 1.240 codici. Quando scendi sotto 250 ricevi un avviso.'],
                        ['name' => 'Premio piattaforma', 'status' => 'Da controllare', 'desc' => 'Il premio e gestito da GiveBack. Prima di andare online serve conferma legale.'],
                        ['name' => 'Box sul sito', 'status' => 'Installato', 'desc' => 'Il box e visibile in pre-acquisto con split payment. La pagina libera resta opzionale.'],
                        ['name' => 'Promozione ai clienti', 'status' => 'Da fare', 'desc' => 'Se non lo comunichi ai clienti, la prova non dice davvero se funziona.'],
                    ] as $row)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950 dark:text-white">{{ $row['name'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $row['desc'] }}</p>
                                </div>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">{{ $row['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Cose veloci da fare</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <button type="button" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-left font-semibold text-white">Carica altri codici promo</button>
                    <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Verifica premio piattaforma</button>
                    <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Copia testo per newsletter</button>
                    <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-left font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Vedi clienti premiati</button>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ([
                ['title' => 'Incasso 1 euro', 'value' => '428 euro', 'desc' => 'Volume demo dei tentativi fortuna registrati nel mese.'],
                ['title' => 'Promo emesse', 'value' => '428', 'desc' => 'Promo applicate in checkout, senza sistema punti.'],
                ['title' => 'Alert attivi', 'value' => '2', 'desc' => 'Premio piattaforma da validare legalmente e campagna clienti da programmare.'],
            ] as $card)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $card['title'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $card['value'] }}</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $card['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">Esempio tabella clienti</h3>
            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                @foreach ([
                    ['email' => 'maria@example.com', 'gift' => '1 euro', 'coupon' => 'FV10-8842', 'result' => 'Promo applicata'],
                    ['email' => 'luca@example.com', 'gift' => '1 euro', 'coupon' => 'FV10-8843', 'result' => 'Promo applicata'],
                    ['email' => 'anna@example.com', 'gift' => '1 euro', 'coupon' => 'FV10-8844', 'result' => 'Ticket premio creato'],
                ] as $row)
                    <div class="grid gap-2 border-t border-slate-200 px-4 py-4 text-sm first:border-t-0 dark:border-slate-700 md:grid-cols-4">
                        <div class="font-semibold text-slate-950 dark:text-white">{{ $row['email'] }}</div>
                        <div class="text-slate-600 dark:text-slate-300">{{ $row['gift'] }}</div>
                        <div class="text-slate-600 dark:text-slate-300">{{ $row['coupon'] }}</div>
                        <div class="text-slate-600 dark:text-slate-300">{{ $row['result'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
