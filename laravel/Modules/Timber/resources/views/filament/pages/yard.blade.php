<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-end gap-3">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Area
                    <select wire:model.live="areaFilter" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Tutte le aree</option>
                        @foreach ($areaCodes as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Ricerca
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Codice o specie" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lotti</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="py-2">Lotto</th>
                            <th>Specie</th>
                            <th>Quantita</th>
                            <th>Area</th>
                            <th>Sposta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lots as $lot)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="py-2 font-semibold text-slate-950 dark:text-slate-100">
                                    <a href="{{ \App\Filament\Timber\Pages\TimberLotDetailPage::getUrl(panel: 'timber', parameters: ['lot' => $lot->id]) }}">{{ $lot->lot_code }}</a>
                                </td>
                                <td>{{ $lot->wood_species_name }}</td>
                                <td>{{ number_format((float) $lot->quantity_available, 2, ',', '.') }} {{ $lot->unit_of_measure }}</td>
                                <td>{{ $areaCodes[$lot->current_area_code] ?? $lot->current_area_code }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        @foreach ($areaCodes as $code => $label)
                                            @if ($code !== $lot->current_area_code)
                                                <button type="button" wire:click="moveLot({{ $lot->id }}, '{{ $code }}')"
                                                    class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-amber-50 dark:border-slate-700 dark:text-slate-200">
                                                    {{ $label }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-slate-500 dark:text-slate-400">Nessun lotto disponibile.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiali derivati</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="py-2">Materiale</th>
                            <th>Specie</th>
                            <th>Quantita</th>
                            <th>Area</th>
                            <th>Sposta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="py-2 font-semibold text-slate-950 dark:text-slate-100">{{ $material->output_code }}</td>
                                <td>{{ $material->wood_species_name }}</td>
                                <td>{{ number_format((float) $material->quantity, 2, ',', '.') }} {{ $material->unit_of_measure }}</td>
                                <td>{{ $areaCodes[$material->current_area_code] ?? $material->current_area_code }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        @foreach ($areaCodes as $code => $label)
                                            @if ($code !== $material->current_area_code)
                                                <button type="button" wire:click="moveMaterial({{ $material->id }}, '{{ $code }}')"
                                                    class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 hover:bg-amber-50 dark:border-slate-700 dark:text-slate-200">
                                                    {{ $label }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-slate-500 dark:text-slate-400">Nessun materiale disponibile.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
