<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-03 - Planning interventi e calendario</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Vista pianificazione stile Google Calendar con squadre, cadenze, durata slot, alert SLA e carico tecnico per area geografica o cliente.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Vista settimana
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                        Crea intervento
                    </button>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                @foreach ([
                    ['label' => 'Interventi oggi', 'value' => '28'],
                    ['label' => 'Tecnici attivi', 'value' => '9'],
                    ['label' => 'Slot occupati', 'value' => '84%'],
                    ['label' => 'Urgenze aperte', 'value' => '3'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Calendario operativo</h3>
                <div class="mt-5 grid gap-3">
                    @foreach ([
                        ['time' => '08:00 - 10:00', 'team' => 'Team A', 'job' => 'Alfa Food - monitoraggio roditori', 'status' => 'Ricorrente 12/anno'],
                        ['time' => '10:30 - 12:30', 'team' => 'Team B', 'job' => 'Hotel Blu - sanificazione cucina + POS', 'status' => 'Fatturare a fine intervento'],
                        ['time' => '14:00 - 16:00', 'team' => 'Team C', 'job' => 'Condominio Aurora - urgenza vespe', 'status' => 'Urgente'],
                        ['time' => '16:30 - 18:30', 'team' => 'Team A', 'job' => 'Logistica Delta - trappole e controllo punti critici', 'status' => 'Con planimetria'],
                    ] as $slot)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $slot['time'] }} - {{ $slot['job'] }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $slot['team'] }}</p>
                                </div>
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">{{ $slot['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Regole di pianificazione</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Slot standard da 2 ore con override per urgenze o grandi siti.',
                            'Cadenze contratto: mensile, trimestrale, semestrale, 12 interventi anno.',
                            'Assegnazione su durata, zona, certificazioni tecnico e tipo infestante.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni veloci</h3>
                    <div class="mt-4 flex flex-col gap-3">
                        <button type="button" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-950 dark:hover:bg-white">
                            Riassegna tecnico
                        </button>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Duplica cadenza annuale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
