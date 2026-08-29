<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(280px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Registra lavorazione</h2>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @if ($allowStepTypeSelection)
                        <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            Tipo di lavorazione
                            <select wire:model.live="stepTypeCode" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                @foreach ($stepTypes as $code => $label)
                                    <option value="{{ $code }}" @selected($code === $stepTypeCode)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endif

                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Lotto da lavorare
                        <select wire:model.live="lotId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">Nessuno</option>
                            @foreach ($lots as $availableLot)
                                <option value="{{ $availableLot->id }}" @selected((int) $lotId === (int) $availableLot->id)>{{ $availableLot->lot_code }} - {{ $availableLot->wood_species_name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Materiale derivato da lavorare
                        <select wire:model.live="materialId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">Nessuno</option>
                            @foreach ($materials as $availableMaterial)
                                <option value="{{ $availableMaterial->id }}" @selected((int) $materialId === (int) $availableMaterial->id)>{{ $availableMaterial->output_code }} - {{ $availableMaterial->wood_species_name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Unita di misura
                        <input type="text" wire:model.blur="unitOfMeasure" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>

                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Materiale recuperabile
                        <input type="text" wire:model.blur="recoverableQuantity" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>

                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:col-span-2">
                        Note
                        <input type="text" wire:model.blur="notes" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                </div>

                <div class="mt-6 flex flex-wrap items-baseline justify-between gap-2">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Output della lavorazione</h3>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        @if ($inputWoodSpeciesName !== '')
                            Specie in uscita: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $inputWoodSpeciesName }}</span> (ereditata dal materiale in lavorazione)
                        @else
                            Seleziona un lotto o un materiale: la specie in uscita viene ereditata automaticamente.
                        @endif
                    </span>
                </div>
                <div class="mt-3 space-y-3">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            <label class="grid gap-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                                Quantita
                                <input type="text" wire:model.blur="outputQuantities.{{ $i }}" class="h-10 rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                            <label class="mt-3 flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                                <input type="checkbox" wire:model.live="outputIsWaste.{{ $i }}" />
                                E' scarto
                            </label>
                        </div>
                    @endfor
                </div>

                <div class="mt-6">
                    <button type="button" wire:click="registerProcessing"
                        class="inline-flex items-center justify-center rounded-xl bg-amber-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                        Registra lavorazione
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Selezionato</h3>
                <div class="mt-3 space-y-2 text-sm">
                    @if ($lot)
                        <p class="font-semibold text-slate-950 dark:text-slate-100">Lotto {{ $lot->lot_code }}</p>
                        <p class="text-slate-500 dark:text-slate-400">Disponibile: {{ number_format((float) $lot->quantity_available, 2, ',', '.') }} {{ $lot->unit_of_measure }}</p>
                    @endif
                    @if ($material)
                        <p class="font-semibold text-slate-950 dark:text-slate-100">Materiale {{ $material->output_code }}</p>
                        <p class="text-slate-500 dark:text-slate-400">Disponibile: {{ number_format((float) $material->quantity, 2, ',', '.') }} {{ $material->unit_of_measure }}</p>
                    @endif
                    @if (! $lot && ! $material)
                        <p class="text-slate-500 dark:text-slate-400">Seleziona un lotto o un materiale da lavorare.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
