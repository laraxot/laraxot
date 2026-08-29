<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:w-64">
                Filtra per stato
                <select wire:model.live="statusFilter" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Tutti gli stati</option>
                    @foreach ($materialStatuses as $code => $label)
                        <option value="{{ $code }}">{{ $label }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiali derivati</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="py-2">Codice</th>
                            <th>Specie</th>
                            <th>Quantita</th>
                            <th>Stato</th>
                            <th>Area</th>
                            <th class="text-right">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="py-2 font-semibold text-slate-950 dark:text-slate-100">{{ $material->output_code }}</td>
                                <td>{{ $material->wood_species_name }}</td>
                                <td>{{ number_format((float) $material->quantity, 2, ',', '.') }} {{ $material->unit_of_measure }}</td>
                                <td>{{ $materialStatuses[$material->status_code] ?? $material->status_code }}</td>
                                <td>{{ $material->current_area_code }}</td>
                                <td class="text-right">
                                    @if (! $material->is_waste && (float) $material->quantity > 0)
                                        <a href="{{ \Modules\Timber\Filament\Pages\TimberDryingPage::getUrl(panel: 'timber::admin', parameters: ['material' => $material->id]) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-amber-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">
                                            Essiccazione
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-slate-500 dark:text-slate-400">Nessun materiale.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Prodotti confezionati</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase text-slate-500 dark:text-slate-400">
                            <th class="py-2">Confezione</th>
                            <th>Tipo</th>
                            <th>Quantita</th>
                            <th>Stato</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packagedUnits as $unit)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="py-2 font-semibold text-slate-950 dark:text-slate-100">{{ $unit->package_code }}</td>
                                <td>{{ $unit->package_type_code }}</td>
                                <td>{{ number_format((float) $unit->quantity, 2, ',', '.') }} {{ $unit->unit_of_measure }}</td>
                                <td>{{ $unit->status_code }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-slate-500 dark:text-slate-400">Nessuna confezione.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
