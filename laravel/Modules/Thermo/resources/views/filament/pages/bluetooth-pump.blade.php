<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-07 - Pompa Bluetooth e cicli anticalcare</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Schermata mockup per trattare la pompa come integrazione IoT/Industria 4.0: requisito presente ma da verificare sul dispositivo reale.
                        </p>
                    </div>
                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-400/20 dark:text-amber-100">Da analizzare</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="rounded-3xl bg-slate-950 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Dispositivo rilevato</p>
                            <h3 class="mt-1 text-xl font-semibold">Pompa anticalcare BT-01</h3>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 text-sm font-bold">BT</div>
                    </div>

                    <div class="mt-6 grid gap-3 md:grid-cols-3">
                        @foreach ([
                            ['label' => 'Connessione', 'value' => 'Bluetooth'],
                            ['label' => 'Ciclo', 'value' => 'Lavaggio radiatori'],
                            ['label' => 'Durata', 'value' => '42 min'],
                        ] as $metric)
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-wide text-slate-400">{{ $metric['label'] }}</p>
                                <p class="mt-2 text-lg font-semibold">{{ $metric['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        'Associa ciclo a cliente, impianto e intervento.',
                        'Registra data, tecnico, durata, note e risultato.',
                        'Allega foto prima/dopo e report PDF.',
                        'Salva storico trattamenti anticalcare sull impianto.',
                    ] as $item)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 dark:border-slate-700 dark:text-slate-200">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Domande tecniche da chiudere</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Marca e modello esatto della pompa.',
                        'Esiste una documentazione Bluetooth/API o solo app proprietaria?',
                        'Il dispositivo espone dati o permette solo avvio manuale?',
                        'Serve controllo remoto o basta registrare il ciclo come evidenza?',
                        'Android, iOS o web app: quale dispositivo usera il tecnico?',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-6 text-orange-950 shadow-sm dark:border-orange-500/30 dark:bg-orange-500/10 dark:text-orange-100">
                <h3 class="text-base font-semibold">Perimetro consigliato MVP</h3>
                <p class="mt-2 text-sm">
                    Prima versione: registrazione manuale guidata del ciclo e allegato report/foto. Integrazione Bluetooth reale solo dopo verifica protocollo e prova con dispositivo fisico.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
