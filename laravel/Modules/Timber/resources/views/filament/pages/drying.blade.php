<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,1.6fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Avvia essiccazione / stagionatura</h2>
            <div class="mt-4 grid gap-4">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Tipo di materiale
                    <select wire:model.live="sourceType" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="lot" @selected($sourceType === 'lot')>Lotto grezzo</option>
                        <option value="material" @selected($sourceType === 'material')>Materiale derivato</option>
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Materiale
                    <select wire:model.live="sourceId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Seleziona</option>
                        @if ($sourceType === 'lot')
                            @foreach ($lots as $lot)
                                <option value="{{ $lot->id }}" @selected((int) $sourceId === (int) $lot->id)>{{ $lot->lot_code }} - {{ $lot->wood_species_name }}</option>
                            @endforeach
                        @else
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" @selected((int) $sourceId === (int) $material->id)>{{ $material->output_code }} - {{ $material->wood_species_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Data prevista di uscita
                    <input type="date" wire:model="expectedExitAt" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Umidita target (%)
                    <input type="text" wire:model="targetHumidityPercent" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
            </div>
            <div class="mt-6">
                <button type="button" wire:click="startDrying"
                    class="inline-flex items-center justify-center rounded-xl bg-amber-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                    Avvia ciclo di essiccazione
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Cicli in corso</h3>
            <div class="mt-4 space-y-4">
                @forelse ($activeCycles as $cycle)
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">
                            {{ class_basename($cycle->dryable_type) }} #{{ $cycle->dryable_id }} -
                            Entrato {{ optional($cycle->entered_at)->format('d/m/Y') }} -
                            Uscita prevista {{ optional($cycle->expected_exit_at)->format('d/m/Y') }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Disponibile {{ number_format((float) $cycle->available_quantity, 2, ',', '.') }} -
                            Target umidita {{ $cycle->target_humidity_percent }}%
                        </p>
                        <ul class="mt-2 space-y-1 text-xs text-slate-600 dark:text-slate-300">
                            @foreach ($cycle->humidityChecks as $check)
                                <li>{{ optional($check->checked_at)->format('d/m/Y H:i') }}: {{ $check->humidity_percent }}%</li>
                            @endforeach
                        </ul>
                        <div class="mt-3 flex flex-wrap items-end gap-2">
                            <label class="grid gap-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                                Nuovo controllo umidita (%)
                                <input type="text" wire:model="humidityPercent" class="h-9 w-32 rounded-lg border border-slate-300 bg-white px-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                            </label>
                            <button type="button" wire:click="recordHumidity({{ $cycle->id }})"
                                class="h-9 rounded-lg border border-slate-300 px-3 text-xs font-semibold text-slate-700 hover:bg-white dark:border-slate-700 dark:text-slate-200">
                                Registra controllo
                            </button>
                            <button type="button" wire:click="completeDrying({{ $cycle->id }})"
                                class="h-9 rounded-lg bg-amber-700 px-3 text-xs font-semibold text-white hover:bg-amber-600">
                                Completa ciclo
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">Nessun ciclo di essiccazione in corso.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
