<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(320px,2fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lotti</h3>
            <div class="mt-4 space-y-2">
                @foreach ($lots as $lot)
                    <a href="{{ \Modules\Timber\Filament\Pages\TimberLotDetailPage::getUrl(panel: 'timber::admin', parameters: ['lot' => $lot->id]) }}"
                        class="block rounded-xl p-3 text-sm transition {{ $selectedLot?->id === $lot->id ? 'bg-amber-100 dark:bg-amber-500/20' : 'bg-slate-50 hover:bg-amber-50 dark:bg-slate-800' }}">
                        <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $lot->lot_code }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $lot->wood_species_name }} - {{ $lot->status_code }} - {{ $lot->current_area_code }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @if ($selectedLot)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $selectedLot->lot_code }}</h2>
                    <div class="mt-4 grid gap-4 md:grid-cols-3 text-sm">
                        <div><span class="text-slate-500 dark:text-slate-400">Fornitore</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ $selectedLot->purchaseRecord?->supplier?->legal_name }}</p></div>
                        <div><span class="text-slate-500 dark:text-slate-400">Provenienza</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ $selectedLot->purchaseRecord?->origin_forest }}</p></div>
                        <div><span class="text-slate-500 dark:text-slate-400">DDT</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ $selectedLot->purchaseRecord?->ddt_number }}</p></div>
                        <div><span class="text-slate-500 dark:text-slate-400">Specie</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ $selectedLot->wood_species_name }}</p></div>
                        <div><span class="text-slate-500 dark:text-slate-400">Quantita disponibile</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ number_format((float) $selectedLot->quantity_available, 2, ',', '.') }} {{ $selectedLot->unit_of_measure }}</p></div>
                        <div><span class="text-slate-500 dark:text-slate-400">Area attuale</span><p class="font-semibold text-slate-950 dark:text-slate-100">{{ $areaCodes[$selectedLot->current_area_code] ?? $selectedLot->current_area_code }}</p></div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-end gap-3">
                        <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            Sposta in area
                            <select wire:model="moveToArea" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                <option value="">Seleziona area</option>
                                @foreach ($areaCodes as $code => $label)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <button type="button" wire:click="moveLot"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-amber-700 px-5 text-sm font-semibold text-white transition hover:bg-amber-600">
                            Sposta
                        </button>
                        <button type="button" wire:click="printLotLabel"
                            class="inline-flex h-11 items-center justify-center rounded-xl border border-amber-300 px-5 text-sm font-semibold text-amber-800 transition hover:bg-amber-50 dark:border-amber-500/40 dark:text-amber-200 dark:hover:bg-amber-500/10">
                            Stampa etichetta lotto (QR)
                        </button>
                        <a href="{{ \Modules\Timber\Filament\Pages\TimberProcessingPage::getUrl(panel: 'timber::admin', parameters: ['lot' => $selectedLot->id]) }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Avvia lavorazione
                        </a>
                        <a href="{{ \Modules\Timber\Filament\Pages\TimberDryingPage::getUrl(panel: 'timber::admin') }}"
                            class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Avvia essiccazione
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Movimenti</h3>
                    <div class="mt-3 space-y-2 text-sm">
                        @forelse ($selectedLot->movements as $movement)
                            <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800">
                                {{ $movement->from_area_code ?? 'N/D' }} &rarr; {{ $movement->to_area_code }}
                                <span class="text-xs text-slate-500 dark:text-slate-400">({{ optional($movement->moved_at)->format('d/m/Y H:i') }})</span>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400">Nessun movimento registrato.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lavorazioni e materiali derivati</h3>
                    <div class="mt-3 space-y-3 text-sm">
                        @forelse ($selectedLot->processingSteps as $step)
                            <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800">
                                <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $step->work_sheet_code }} - {{ $step->step_type_code }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Input {{ number_format((float) $step->input_quantity, 2, ',', '.') }} -
                                    Output {{ number_format((float) $step->output_quantity, 2, ',', '.') }} -
                                    Scarto {{ number_format((float) $step->waste_quantity, 2, ',', '.') }}
                                </p>
                                <ul class="mt-2 list-inside list-disc">
                                    @foreach ($step->derivedMaterials as $material)
                                        <li>{{ $material->output_code }} - {{ number_format((float) $material->quantity, 2, ',', '.') }} {{ $material->unit_of_measure }} ({{ $material->status_code }})</li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400">Nessuna lavorazione registrata.</p>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
                    Seleziona un lotto dall'elenco per vedere il dettaglio.
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
