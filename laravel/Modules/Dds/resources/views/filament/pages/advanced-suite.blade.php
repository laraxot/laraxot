<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-07 - Full suite: magazzino, RENTRI, AI e multi-tenant</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Vista executive per la versione avanzata: lotti, barcode, trappole in planimetria, formulari via Aruba RENTRI, KPI predittivi e rivendita multi-azienda.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Avvia sync RENTRI
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                        Analisi AI
                    </button>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            @foreach ([
                ['title' => 'Logistica avanzata', 'items' => ['Lotti e barcode', 'Alert scorta minima', 'Carichi/scarichi magazziniere', 'Tracciabilita prodotto e produzione']],
                ['title' => 'Regulatory / RENTRI', 'items' => ['Formulari rifiuti', 'Invio Aruba RENTRI', 'Storico conferimenti', 'Audit trail conformita']],
                ['title' => 'AI e multi-tenant', 'items' => ['KPI predittivi', 'Suggerimenti planning', 'Istanze separate per cliente finale', 'Console rivenditore']],
            ] as $card)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $card['title'] }}</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ($card['items'] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.3fr)_minmax(0,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Mappatura grafica trappole</h3>
                <div class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['code' => 'T-01', 'state' => 'Verde', 'desc' => 'Controllo ok'],
                        ['code' => 'T-02', 'state' => 'Rosso', 'desc' => 'Cattura elevata'],
                        ['code' => 'T-03', 'state' => 'Giallo', 'desc' => 'Monitorare'],
                        ['code' => 'T-04', 'state' => 'Verde', 'desc' => 'Ok'],
                    ] as $trap)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $trap['code'] }}</p>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $trap['state'] === 'Rosso' ? 'bg-rose-100 text-rose-800 dark:bg-rose-400/20 dark:text-rose-100' : ($trap['state'] === 'Giallo' ? 'bg-amber-100 text-amber-800 dark:bg-amber-400/20 dark:text-amber-100' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100') }}">
                                    {{ $trap['state'] }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $trap['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Cruscotto avanzato</h3>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        ['label' => 'Rischio reinfestazione', 'value' => 'Medio-alto su 3 siti'],
                        ['label' => 'Scorte sotto soglia', 'value' => '6 prodotti'],
                        ['label' => 'Tenant attivi', 'value' => '14 aziende / 22 partite IVA'],
                        ['label' => 'Anomalie RENTRI', 'value' => '1 invio da riconciliare'],
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
