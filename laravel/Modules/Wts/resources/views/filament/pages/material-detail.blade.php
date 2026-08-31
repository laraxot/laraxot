<x-filament-panels::page>
    @if ($selectedUnit !== null)
        @php
            $lot = $selectedUnit->inboundLot;
            $record = $lot?->inboundRecord;
            $sheet = $record?->technicalSheetSnapshot;
            $lastStep = $selectedUnit->processingSteps->sortByDesc('executed_at')->first();
        @endphp

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 dark:bg-sky-500/10 dark:text-sky-100">DATI REALI WTS</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $selectedUnit->status_code }}</span>
                        </div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-06 - Dettaglio collo da giacenza</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Scheda reale aperta da UI-05 o QR: identifica il collo, la posizione e la storia minima prima della lavorazione.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsWorkQueuePage::getUrl(panel: 'wts') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Torna alla coda WTS1
                        </a>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts', parameters: ['lot' => $lot?->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                            Apri lavorazione assegnata
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,1fr)]">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Identificazione rapida</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ([
                                ['label' => 'Scheda tecnica', 'value' => $sheet?->sheet_code ?? 'N/D'],
                                ['label' => 'CER / EER', 'value' => $sheet?->waste_code ?? 'N/D'],
                                ['label' => 'Produttore', 'value' => $sheet?->producer_name ?? $record?->source_company_name ?? 'N/D'],
                                ['label' => 'Collo', 'value' => $selectedUnit->unit_code],
                                ['label' => 'Lotto interno', 'value' => $lot?->lot_code ?? 'N/D'],
                                ['label' => 'Formulario', 'value' => $record?->form_number ?? 'N/D'],
                            ] as $item)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Stato e posizione del collo</h3>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @foreach ([
                                ['label' => 'Stato operativo', 'value' => $selectedUnit->status_code],
                                ['label' => 'Area logica', 'value' => $stockViews[$selectedUnit->current_area_code] ?? $selectedUnit->current_area_code],
                                ['label' => 'Ubicazione semplice', 'value' => $selectedUnit->location_label ?? 'Non assegnata'],
                                ['label' => 'Peso imputato', 'value' => $selectedUnit->gross_weight !== null ? number_format((float) $selectedUnit->gross_weight, 2, ',', '.') . ' kg' : 'N/D'],
                                ['label' => 'Ultima lavorazione registrata', 'value' => $lastStep?->work_sheet_code ?? 'Nessuna'],
                                ['label' => 'QR payload', 'value' => $selectedUnit->qr_code],
                            ] as $item)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Storico sintetico</h3>
                        </div>
                        <div class="space-y-4 px-6 py-5">
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">Collo generato e preso in carico nel lotto</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $selectedUnit->created_at?->format('d/m/Y H:i') ?? 'Data non disponibile' }}</p>
                            </div>
                            @foreach ($selectedUnit->processingSteps->sortByDesc('executed_at') as $step)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $step->work_sheet_code }} - {{ $step->step_type_code }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $step->executed_at?->format('d/m/Y H:i') ?? 'Data non disponibile' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Azioni posizione</h3>
                        <div class="mt-4 flex flex-col gap-3">
                            <button type="button" wire:click="moveToArea('to_process', '{{ $selectedUnit->location_label ?? 'WTS1' }}')"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Conferma da lavorare
                            </button>
                            <button type="button" wire:click="moveToArea('in_process', 'Reparto lavorazione')"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                                Passa in lavorazione
                            </button>
                            <button type="button" wire:click="moveToArea('finished', '{{ $selectedUnit->location_label ?? 'Finito' }}')"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                                Passa a finito
                            </button>
                            <button type="button" wire:click="reprintUnitLabel"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Ristampa etichetta
                            </button>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Uso corretto</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <p><span class="font-semibold text-slate-950 dark:text-slate-100">Entri qui</span> da UI-05 o da scansione QR.</p>
                            <p><span class="font-semibold text-slate-950 dark:text-slate-100">Controlli</span> identita del collo, stato, posizione e storico minimo.</p>
                            <p><span class="font-semibold text-slate-950 dark:text-slate-100">Esci</span> tornando alla coda o aprendo la lavorazione assegnata.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-amber-300 bg-amber-50 p-6 text-amber-900 shadow-sm dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100">
            <div class="flex flex-wrap items-center gap-2">
                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 dark:bg-sky-500/10 dark:text-sky-100">Aperto da UI-05 o da QR</span>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">DATI DEMO</span>
            </div>
            <h2 class="mt-2 text-lg font-semibold">UI-06 - Dettaglio collo da giacenza</h2>
            <p class="mt-2 text-sm">Nessun collo reale disponibile: questa pagina mostra il dettaglio reale appena si apre una riga da UI-05 o un QR valido.</p>
            <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts') }}"
                class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                Apri lavorazione assegnata
            </a>
        </div>
    @endif
</x-filament-panels::page>
