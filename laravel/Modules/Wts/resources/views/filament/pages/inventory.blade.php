<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-12 - Stampa giacenza WTS</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        Giacenza a data con filtri EER/CER e zona. Con ECOS abilitato usa la logica per partita (bspart/bscol); altrimenti i colli generati localmente.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="downloadInventoryPdf"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                        Stampa PDF giacenza
                    </button>
                    <button type="button" wire:click="downloadInventoryExcel"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                        Esporta Excel
                    </button>
                </div>
            </div>

            @php
                // Discriminanti stile ECOS (non a tendina con spunte): campo scrivibile + operatore.
                $filterOperators = [
                    'contains' => 'Contiene',
                    'starts_with' => 'Inizia con',
                    'equals' => 'Uguale a',
                    'not_equals' => 'Diverso da',
                ];
            @endphp

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Registrazione inferiore a
                    <input wire:model.live="referenceDate" type="date"
                        class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <div class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Codice EER/CER
                    <div class="flex gap-2">
                        <select wire:model.live="wasteCodeOperator"
                            class="h-11 w-28 shrink-0 rounded-xl border border-slate-300 bg-white px-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @foreach ($filterOperators as $opValue => $opLabel)
                                <option value="{{ $opValue }}">{{ $opLabel }}</option>
                            @endforeach
                        </select>
                        <input wire:model.live.debounce.400ms="wasteCode" type="text" list="wtsWasteCodes"
                            placeholder="es. 12 01 03, 15 01"
                            class="h-11 w-full min-w-0 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </div>
                    <datalist id="wtsWasteCodes">
                        @foreach ($filters['wasteCodes'] as $filterWasteCode)
                            <option value="{{ $filterWasteCode }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Zona / ubicazione
                    <div class="flex gap-2">
                        <select wire:model.live="zoneOperator"
                            class="h-11 w-28 shrink-0 rounded-xl border border-slate-300 bg-white px-2 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @foreach ($filterOperators as $opValue => $opLabel)
                                <option value="{{ $opValue }}">{{ $opLabel }}</option>
                            @endforeach
                        </select>
                        <input wire:model.live.debounce.400ms="zone" type="text" list="wtsZones"
                            placeholder="es. WTS1, ZONA A"
                            class="h-11 w-full min-w-0 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </div>
                    <datalist id="wtsZones">
                        @foreach ($filters['zones'] as $filterZone)
                            <option value="{{ $filterZone }}">{{ $usesEcosStock ? ($filterZone) : ($stockViews[$filterZone] ?? $filterZone) }}</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    Disponibili: <span class="font-semibold">{{ $stats['available'] }}</span>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    Gia lavorati: <span class="font-semibold">{{ $stats['consumed'] }}</span>
                </div>
            </div>
            <p class="mt-4 text-xs text-slate-400 dark:text-slate-500">
                Filtri stile ECOS: scrivi il valore e scegli il discriminante (contiene/inizia con/uguale/diverso). Piu valori separati da virgola.
                @if ($usesEcosStock)
                    Fotografia ECOS alla data selezionata con saldo per partita e note tecniche da scheda.
                @else
                    Fotografia applicativa alla data selezionata sui colli WTS generati localmente.
                @endif
            </p>
        </div>

        <div @class([
            'rounded-2xl border px-6 py-4 shadow-sm',
            $dataSource['class'],
        ])>
            <p class="text-sm font-black uppercase tracking-wide">{{ $dataSource['label'] }}</p>
            <p class="mt-1 text-sm font-semibold">{{ $dataSource['description'] }}</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Giac con note</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Partita, sotto-numero, peso disponibile, descrizione scheda, nota 1 e nota 2.
                </p>
            </div>

            @if ($rows->isEmpty())
                <div class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">
                    Nessuna riga nella giacenza filtrata.
                </div>
            @endif

            @php
                $totalWeight = $rows->sum(fn ($row): float => (float) ($row->weightKg ?? 0));
                $totalColli = $rows->sum(fn ($row): int => (int) ($row->colliCount ?? 0));
            @endphp

            {{-- DESKTOP: tabella --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                        <tr>
                            <th class="px-3 py-3">Partita / sub</th>
                            <th class="px-3 py-3">Scheda tecnica</th>
                            <th class="px-3 py-3">Descrizione scheda</th>
                            <th class="px-3 py-3">Produttore</th>
                            <th class="px-3 py-3">HP</th>
                            <th class="px-3 py-3">Stato fisico</th>
                            <th class="px-3 py-3">Tratt.</th>
                            <th class="px-3 py-3">Linea / Ciclo</th>
                            <th class="px-3 py-3">CER</th>
                            <th class="px-3 py-3 text-right">Colli</th>
                            <th class="px-3 py-3 text-right">Peso</th>
                            <th class="px-3 py-3">Zona</th>
                            <th class="px-3 py-3">Note</th>
                            <th class="px-3 py-3">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($rows as $row)
                            <tr class="align-top hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="whitespace-nowrap px-3 py-3">
                                    <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $row->batchCode }} / {{ $row->unitSubCode }}</span>
                                    @if ($row->formReference)
                                        <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">FIR {{ $row->formReference }}</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 font-semibold text-slate-950 dark:text-slate-100">{{ $row->sheetCode ?? 'N/D' }}</td>
                                <td class="px-3 py-3 text-slate-700 dark:text-slate-200">{{ $row->sheetDescription ?? $row->productionDescription ?? $row->packagingDescription ?? 'N/D' }}</td>
                                <td class="px-3 py-3 text-slate-700 dark:text-slate-200">{{ $row->producerName ?? '—' }}</td>
                                <td class="px-3 py-3">
                                    @if ($row->hpCodes)
                                        <span class="inline-block rounded bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-800 dark:bg-rose-500/10 dark:text-rose-200">{{ $row->hpCodes }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-slate-700 dark:text-slate-200">
                                    {{ $row->physicalStateCode ?? '—' }}
                                    @if ($row->physicalStateLabel)
                                        <span class="mt-0.5 block text-xs text-slate-500 dark:text-slate-400">{{ $row->physicalStateLabel }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-slate-700 dark:text-slate-200">
                                    {{ $row->treatmentCode ?? '—' }}
                                    @if ($row->treatmentLabel)
                                        <span class="mt-0.5 block max-w-[14rem] text-xs text-slate-500 dark:text-slate-400">{{ $row->treatmentLabel }}</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3 text-slate-700 dark:text-slate-200">{{ trim(($row->treatmentLineCode ?? '').' / '.($row->productionCycleCode ?? ''), ' /') ?: '—' }}</td>
                                <td class="whitespace-nowrap px-3 py-3 font-medium text-slate-950 dark:text-slate-100">{{ $row->wasteCode ?? 'N/D' }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-right text-slate-700 dark:text-slate-200">{{ $row->colliCount ?? '—' }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-right font-semibold text-slate-950 dark:text-slate-100">{{ $row->weightKg !== null ? number_format($row->weightKg, 2, ',', '.') : 'N/D' }}</td>
                                <td class="whitespace-nowrap px-3 py-3 text-slate-700 dark:text-slate-200">{{ $row->zoneLabel ?? $stockViews[$row->zoneCode ?? ''] ?? $row->zoneCode ?? 'N/D' }}</td>
                                <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400">{{ trim(($row->note1 ?? '').' '.($row->note2 ?? '')) ?: '—' }}</td>
                                <td class="whitespace-nowrap px-3 py-3">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                        'bg-emerald-100 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-100' => $row->isAvailable(),
                                        'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' => ! $row->isAvailable(),
                                    ])>
                                        {{ $row->isAvailable() ? 'Disponibile WTS1' : 'Gia lavorato' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($rows->isNotEmpty())
                        <tfoot class="bg-slate-50 text-sm font-bold dark:bg-slate-950">
                            <tr>
                                <td class="px-3 py-3" colspan="9">TOTALE ({{ $rows->count() }} partite)</td>
                                <td class="px-3 py-3 text-right">{{ number_format($totalColli, 0, ',', '.') }}</td>
                                <td class="px-3 py-3 text-right">{{ number_format($totalWeight, 2, ',', '.') }} kg</td>
                                <td class="px-3 py-3" colspan="3"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            {{-- MOBILE: card --}}
            <div class="divide-y divide-slate-200 md:hidden dark:divide-slate-800">
                @foreach ($rows as $row)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $row->batchCode }} / {{ $row->unitSubCode }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $row->sheetCode ?? 'N/D' }}</p>
                            </div>
                            <span class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $row->weightKg !== null ? number_format($row->weightKg, 2, ',', '.').' kg' : 'N/D' }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">{{ $row->sheetDescription ?? $row->productionDescription ?? 'N/D' }}</p>
                        @if ($row->producerName)
                            <p class="mt-1 text-xs font-medium text-slate-600 dark:text-slate-300">{{ $row->producerName }}</p>
                        @endif
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            CER {{ $row->wasteCode ?? 'N/D' }} · {{ $row->hpCodes ?? 'nessuna HP' }} · zona {{ $row->zoneLabel ?? $row->zoneCode ?? 'N/D' }}
                            @if ($row->treatmentLineCode || $row->productionCycleCode)
                                · {{ trim(($row->treatmentLineCode ?? '').'/'.($row->productionCycleCode ?? ''), '/') }}
                            @endif
                        </p>
                    </div>
                @endforeach
                @if ($rows->isNotEmpty())
                    <div class="flex items-center justify-between bg-slate-50 px-6 py-3 text-sm font-bold dark:bg-slate-950">
                        <span>TOTALE ({{ $rows->count() }})</span>
                        <span>{{ number_format($totalWeight, 2, ',', '.') }} kg</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
