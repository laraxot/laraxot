<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-end gap-3">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Specie
                    <select wire:model.live="woodSpecies" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Tutte</option>
                        @foreach ($woodSpeciesOptions as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Stato
                    <select wire:model.live="statusCode" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Tutti</option>
                        @foreach ($materialStatuses as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="button" wire:click="downloadInventoryPdf"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-amber-700 px-5 text-sm font-semibold text-white transition hover:bg-amber-600">
                    Scarica PDF giacenza
                </button>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            @foreach ($stats as $label => $value)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">{{ str_replace('_', ' ', $label) }}</p>
                    <p class="mt-2 text-xl font-semibold text-amber-950 dark:text-amber-100">{{ is_float($value) ? number_format($value, 2, ',', '.') : $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lotti</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="py-2">Lotto</th><th>Specie</th><th>Stato</th><th>Quantita</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lots as $lot)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="py-2 font-semibold text-slate-950 dark:text-slate-100">{{ $lot->lot_code }}</td>
                                <td>{{ $lot->wood_species_name }}</td>
                                <td>{{ $lot->status_code }}</td>
                                <td>{{ number_format((float) $lot->quantity_available, 2, ',', '.') }} {{ $lot->unit_of_measure }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
