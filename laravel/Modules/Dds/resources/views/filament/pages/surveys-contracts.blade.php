<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-02 - Sopralluoghi, offerte e contratti</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Qui il commerciale o il tecnico senior raccoglie i dati tecnici, allega foto e video, genera il preventivo e lo trasforma in contratto o ordine di lavoro.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Nuovo sopralluogo
                        </button>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                            Genera preventivo
                        </button>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['title' => 'Dati tecnici raccolti', 'items' => ['Tipologia infestante', 'Livello criticita', 'Punti sensibili', 'Planimetrie e allegati']],
                        ['title' => 'Output automatici', 'items' => ['Preventivo PDF', 'Contratto con clausole', 'Ordine di lavoro', 'Check list avvio servizio']],
                    ] as $card)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $card['title'] }}</h3>
                            <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                @foreach ($card['items'] as $item)
                                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Workflow proposta -> contratto -> ordine di lavoro</h3>
                <div class="mt-5 grid gap-4 lg:grid-cols-4">
                    @foreach ([
                        ['step' => '1', 'title' => 'Sopralluogo', 'desc' => 'Checklist tecnica, media e note di rischio.'],
                        ['step' => '2', 'title' => 'Offerta', 'desc' => 'Prezzi, servizi inclusi, frequenza e condizioni.'],
                        ['step' => '3', 'title' => 'Firma', 'desc' => 'Firme cliente, ufficio o tecnico su contratto e verbali.'],
                        ['step' => '4', 'title' => 'Ordine di lavoro', 'desc' => 'Apertura interventi ricorrenti o one shot.'],
                    ] as $step)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">{{ $step['step'] }}</div>
                            <p class="mt-4 text-sm font-semibold text-slate-950 dark:text-white">{{ $step['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Firma digitale</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Firma cliente su offerta e contratto.',
                        'Firma tecnico e cliente su rapportino finale.',
                        'Controfirma ufficio per accettazione e fatturazione.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Preview sopralluogo</h3>
                <div class="mt-4 space-y-4">
                    @foreach ([
                        ['label' => 'Cliente', 'value' => 'Alfa Food S.p.A. - Stabilimento Nord'],
                        ['label' => 'Infestante', 'value' => 'Roditori + monitoraggio blatte'],
                        ['label' => 'Allegati', 'value' => '12 foto, 2 video, 1 planimetria PDF'],
                        ['label' => 'Esito', 'value' => 'Proposta annuale 12 interventi / anno'],
                    ] as $item)
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-1 text-sm font-medium text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
