<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-end gap-3">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:w-96">
                    Cerca per codice lotto, materiale, confezione o DDT
                    <input type="text" wire:model.live.debounce.400ms="search" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                @if ($search !== '')
                    <button type="button" wire:click="downloadTraceabilityPdf"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-amber-700 px-5 text-sm font-semibold text-white transition hover:bg-amber-600">
                        Scarica PDF tracciabilita
                    </button>
                @endif
            </div>
        </div>

        @if ($searchResult)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Acquisto</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    Fornitore: {{ $searchResult['purchaseRecord']?->supplier?->legal_name ?? 'N/D' }} -
                    Provenienza: {{ $searchResult['purchaseRecord']?->origin_forest ?? 'N/D' }} -
                    DDT: {{ $searchResult['purchaseRecord']?->ddt_number ?? 'N/D' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lotto</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    {{ $searchResult['lot']?->lot_code ?? 'N/D' }} -
                    Specie {{ $searchResult['lot']?->wood_species_name ?? 'N/D' }} -
                    Stato {{ $searchResult['lot']?->status_code ?? 'N/D' }} -
                    Area {{ $searchResult['lot']?->current_area_code ?? 'N/D' }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lavorazioni</h3>
                <div class="mt-3 space-y-2 text-sm">
                    @forelse ($searchResult['processingSteps'] as $step)
                        <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-800">
                            {{ $step->work_sheet_code }} - {{ $step->step_type_code }} -
                            Output {{ number_format((float) $step->output_quantity, 2, ',', '.') }} -
                            Scarto {{ number_format((float) $step->waste_quantity, 2, ',', '.') }}
                        </div>
                    @empty
                        <p class="text-slate-500 dark:text-slate-400">Nessuna lavorazione trovata.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiale derivato e confezione</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    Materiale: {{ $searchResult['derivedMaterial']?->output_code ?? 'N/D' }} -
                    Confezione: {{ $searchResult['packagedUnit']?->package_code ?? 'N/D' }}
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
