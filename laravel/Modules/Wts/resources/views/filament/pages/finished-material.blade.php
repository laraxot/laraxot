<x-filament-panels::page>
    @if ($selectedMaterial !== null)
        @php
            $step = $selectedMaterial->processingStep;
            $originUnits = $step?->selectedUnits ?? collect();
            $originUnit = $originUnits->first();
            $originLot = $originUnit?->inboundLot;
            $originRecord = $originLot?->inboundRecord;
        @endphp

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-black uppercase tracking-wide text-emerald-700 dark:text-emerald-200">DATI REALI WTS</p>
                            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-09 - Dettaglio materiale finito e tracciabilita</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Scheda reale del materiale derivato con catena completa di origine, lavorazione e stampa etichette.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ \Modules\Wts\Filament\Pages\WtsOutputsPage::getUrl(panel: 'wts') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Torna a UI-08
                            </a>
                            <a href="{{ \Modules\Wts\Filament\Pages\WtsAuditPage::getUrl(panel: 'wts', parameters: ['search' => $selectedMaterial->output_code]) }}"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                                Apri audit completo
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Dati materiale finito</h3>
                        <div class="mt-4 grid gap-4">
                            @foreach ([
                                ['label' => 'Codice materiale', 'value' => $selectedMaterial->output_code],
                                ['label' => 'Tipo output', 'value' => match($selectedMaterial->output_type_code) {
                                    'drum' => 'Fusto',
                                    'dirty_drum' => 'Fusto sporco',
                                    'pallet' => 'Bancale',
                                    'deferred_iron' => 'Ferro deferizzato',
                                    'generated_liquid' => 'Liquido generato',
                                    default => $selectedMaterial->output_type_code,
                                }],
                                ['label' => 'Peso', 'value' => $selectedMaterial->weight !== null ? number_format((float) $selectedMaterial->weight, 2, ',', '.') . ' kg' : 'N/D'],
                                ['label' => 'Contenitore', 'value' => $selectedMaterial->container_type_code],
                            ] as $item)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Origine e stato</h3>
                        <div class="mt-4 grid gap-4">
                            @foreach ([
                                ['label' => 'Origine', 'value' => $originUnit?->unit_code ?? 'N/D'],
                                ['label' => 'Lotto', 'value' => $originLot?->lot_code ?? 'N/D'],
                                ['label' => 'Formulario', 'value' => $originRecord?->form_number ?? 'N/D'],
                                ['label' => 'Stato', 'value' => 'Finito'],
                                ['label' => 'Operatore chiusura', 'value' => $step?->executedBy?->name ?? 'N/D'],
                            ] as $item)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Tracciabilita sintetica</h3>
                    </div>
                    <div class="space-y-3 px-6 py-5">
                        @foreach ([
                            'Formulario ' . ($originRecord?->form_number ?? 'N/D'),
                            'Lotto ' . ($originLot?->lot_code ?? 'N/D'),
                            'Collo ' . ($originUnit?->unit_code ?? 'N/D'),
                            'Lavorazione ' . ($step?->work_sheet_code ?? 'N/D'),
                            'Output ' . $selectedMaterial->output_code,
                        ] as $trace)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $trace }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Etichette output</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Etichette generate</p>
                            <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ $selectedMaterial->labelPrints->count() }}</p>
                        </div>
                        <button type="button" wire:click="reprintOutputLabel"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                            Ristampa etichetta output
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-6 text-amber-900 shadow-sm dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100">
            <p class="text-sm font-black uppercase tracking-wide">DATI DEMO</p>
            <h2 class="mt-2 text-lg font-semibold">UI-09 - Materiale finito</h2>
            <p class="mt-2 text-sm">Apri un materiale derivato reale da UI-08 per vedere la tracciabilita completa.</p>
        </div>
    @endif
</x-filament-panels::page>
