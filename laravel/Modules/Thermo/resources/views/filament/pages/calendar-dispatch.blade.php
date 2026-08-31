<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-01 - Calendario, urgenze e assegnazioni</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Vista ufficio e call center dei fornitori caldaie per inserire richieste e assegnare interventi in base a zona, disponibilita, urgenza e raggio operativo.
                        </p>
                    </div>
                    <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800 dark:bg-orange-400/20 dark:text-orange-100">Ruoli: thermo_admin, thermo_supplier_call_center</span>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-950 dark:text-white">Settimana operativa 5 giorni</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Vista lunedi-venerdi, senza sabato e domenica.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">Lun-Ven</span>
                </div>
                <div class="grid gap-3 md:grid-cols-5">
                    @foreach (['Lun 8', 'Mar 9', 'Mer 10', 'Gio 11', 'Ven 12'] as $day)
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $day }}</p>
                            <div class="mt-3 space-y-3">
                                @foreach ([
                                    ['time' => '08:30', 'label' => 'Sopralluogo caldaia', 'tone' => 'bg-blue-100 text-blue-800'],
                                    ['time' => '11:00', 'label' => 'Urgenza perdita', 'tone' => 'bg-red-100 text-red-800'],
                                    ['time' => '14:30', 'label' => 'Manutenzione programmata', 'tone' => 'bg-emerald-100 text-emerald-800'],
                                ] as $slot)
                                    <div class="rounded-xl bg-white p-3 shadow-sm dark:bg-slate-900">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-semibold text-slate-500">{{ $slot['time'] }}</span>
                                            <span class="rounded-full px-2 py-1 text-[0.65rem] font-semibold {{ $slot['tone'] }}">3 km</span>
                                        </div>
                                        <p class="mt-2 text-xs font-medium text-slate-800 dark:text-slate-100">{{ $slot['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Nuova richiesta</h3>
                <div class="mt-4 space-y-3 text-sm">
                    @foreach ([
                        ['label' => 'Origine', 'value' => 'Call center fornitore caldaie'],
                        ['label' => 'Fornitore', 'value' => 'Centro Assistenza Caldaie Nord'],
                        ['label' => 'Cliente', 'value' => 'Rossi Mario - Scorze'],
                        ['label' => 'Tipo', 'value' => 'Lavaggio impianto / calcare'],
                        ['label' => 'Priorita', 'value' => 'Urgente entro 24h'],
                        ['label' => 'Tecnico suggerito', 'value' => 'Marco - 3,4 km'],
                    ] as $field)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $field['label'] }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $field['value'] }}</p>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="mt-4 w-full rounded-xl bg-orange-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-orange-500">
                    Inserisci nel calendario e invia promemoria
                </button>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Automazioni previste</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Inserimento lavoro da call center esterno del fornitore caldaie con permessi limitati.',
                        'Tracciamento origine richiesta, fornitore, operatore e note ricevute.',
                        'Promemoria SMS o WhatsApp al cliente il giorno prima.',
                        'Link diretto a Google Maps o Waze dall appuntamento.',
                        'Filtro per raggio km, disponibilita tecnico, urgenza e zona coperta.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
