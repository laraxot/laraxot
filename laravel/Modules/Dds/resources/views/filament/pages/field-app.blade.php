<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-04 - App tecnico e operativita sul campo</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Interfaccia mobile-first per interventi, check-in, pause, prodotti utilizzati, foto/video, firme e pagamento con POS durante l uscita.
                        </p>
                    </div>
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Ruolo: dds_technician</span>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-slate-950 p-5 shadow-sm dark:border-slate-700">
                <div class="mx-auto max-w-sm rounded-[2rem] bg-white p-5 shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Intervento in corso</p>
                            <p class="text-sm font-semibold text-slate-950">Hotel Blu - cucina centrale</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">08:12</span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 text-center">
                        @foreach ([
                            ['label' => 'Inizio', 'value' => '07:58'],
                            ['label' => 'Pausa', 'value' => '12:30'],
                            ['label' => 'Fine stimata', 'value' => '10:00'],
                            ['label' => 'POS', 'value' => 'Attivo'],
                        ] as $item)
                            <div class="rounded-2xl bg-slate-50 px-4 py-4">
                                <p class="text-xs uppercase tracking-wide text-slate-500">{{ $item['label'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ([
                            'Checklist sito e dispositivi di protezione',
                            'Prodotti utilizzati con quantita e lotto',
                            'Foto prima e dopo, video e note vocali',
                            'Firma cliente, firma tecnico e avviso fatturazione',
                        ] as $item)
                            <div class="rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni operative</h3>
                <div class="mt-4 flex flex-col gap-3">
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                        Avvia intervento e timbratura
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Incassa con POS
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Chiudi rapportino e raccogli firme
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Output del tecnico</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Rapportino con esito, prodotti usati, tempo effettivo e firme.',
                        'Notifica automatica all ufficio se e richiesto avviso di fatturazione.',
                        'Aggiornamento foglio ore mensile e cronologia presenze.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
