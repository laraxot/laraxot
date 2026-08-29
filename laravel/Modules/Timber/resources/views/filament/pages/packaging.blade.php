<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(280px,1fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Confezionamento e pallettizzazione</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Materiale da confezionare
                    <select wire:model="materialId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Seleziona materiale</option>
                        @foreach ($materials as $availableMaterial)
                            <option value="{{ $availableMaterial->id }}">{{ $availableMaterial->output_code }} - {{ $availableMaterial->wood_species_name }} ({{ number_format((float) $availableMaterial->quantity, 2, ',', '.') }} {{ $availableMaterial->unit_of_measure }})</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Tipo di confezione
                    <select wire:model="packageTypeCode" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @foreach ($packageTypes as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Numero di confezioni
                    <input type="number" min="1" wire:model="unitsCount" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Quantita per confezione
                    <input type="text" wire:model="quantityPerUnit" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
            </div>
            <div class="mt-6">
                <button type="button" wire:click="generatePackagedUnits"
                    class="inline-flex items-center justify-center rounded-xl bg-amber-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                    Genera confezioni ed etichette
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiale selezionato</h3>
            @if ($selectedMaterial)
                <p class="mt-3 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $selectedMaterial->output_code }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">Disponibile: {{ number_format((float) $selectedMaterial->quantity, 2, ',', '.') }} {{ $selectedMaterial->unit_of_measure }}</p>
            @else
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">Seleziona un materiale da confezionare.</p>
            @endif
        </div>
    </div>
</x-filament-panels::page>
