<x-filament-panels::page>
    @if ($lot)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-start gap-3">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-widest text-teal-700 dark:text-teal-400">Selezione e cernita</p>
                    <h2 class="mt-1 truncate text-2xl font-bold text-[#212529] dark:text-slate-100">{{ $lot->lot_code }}</h2>
                </div>
                <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts') }}"
                    class="flex h-12 shrink-0 touch-manipulation items-center rounded-2xl border border-slate-300 px-4 text-sm font-semibold text-slate-600 active:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:active:bg-slate-800">
                    ← Snodo
                </a>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    CER: {{ $lot->inboundRecord?->technicalSheetSnapshot?->waste_code ?? '—' }}
                </span>
                <span class="max-w-[220px] truncate rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    {{ $lot->inboundRecord?->source_company_name ?? '—' }}
                </span>
                <span class="rounded-xl bg-teal-100 px-3 py-2 text-sm font-bold text-teal-800 dark:bg-teal-500/20 dark:text-teal-300">
                    {{ $units->count() }} colli
                </span>
            </div>
        </div>
    @else
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-500/30 dark:bg-amber-500/10">
            <p class="text-lg font-bold text-amber-900 dark:text-amber-100">Nessun lotto selezionato</p>
            <p class="mt-1 text-sm text-amber-800 dark:text-amber-200">Apri questa pagina dallo snodo scegliendo un lotto.</p>
            <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts') }}"
                class="mt-4 flex h-14 touch-manipulation items-center justify-center rounded-2xl border border-amber-300 text-base font-semibold text-amber-900 active:bg-amber-100 dark:border-amber-500/40 dark:text-amber-100">
                Vai allo snodo lavorazioni
            </a>
        </div>
    @endif

    <div>
        <div class="mb-4 flex items-center justify-between gap-3">
            <div class="flex items-baseline gap-3">
                <h3 class="text-lg font-bold text-[#212529] dark:text-slate-100">Colli da lavorare</h3>
                @if ($units->isNotEmpty())
                    <span class="text-lg font-bold text-blue-700 dark:text-blue-400">{{ count($selectedUnitIds) }}/{{ $units->count() }}</span>
                @endif
            </div>
            @if ($units->isNotEmpty())
                <button type="button" wire:click="selectAllUnits"
                    class="flex h-12 touch-manipulation shrink-0 items-center rounded-2xl border border-slate-300 px-5 text-sm font-semibold text-slate-600 active:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:active:bg-slate-800">
                    Tutti
                </button>
            @endif
        </div>

        @if ($units->isEmpty())
            <p class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm font-semibold text-slate-400 dark:border-slate-700">
                {{ $lot ? 'Nessun collo disponibile per questo lotto.' : 'Seleziona un lotto per vedere i colli.' }}
            </p>
        @else
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($units as $unit)
                    <label class="flex touch-manipulation cursor-pointer items-center gap-4 rounded-2xl border p-5 transition
                        {{ in_array($unit->id, $selectedUnitIds)
                            ? 'border-blue-400 bg-blue-50 dark:border-blue-500 dark:bg-blue-500/10'
                            : 'border-slate-200 bg-white active:border-slate-300 dark:border-slate-700 dark:bg-slate-900' }}">
                        <input type="checkbox" wire:model.live="selectedUnitIds" value="{{ $unit->id }}" class="sr-only" />
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2
                            {{ in_array($unit->id, $selectedUnitIds)
                                ? 'border-blue-600 bg-blue-600'
                                : 'border-slate-300 dark:border-slate-500' }}">
                            @if (in_array($unit->id, $selectedUnitIds))
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-base font-bold text-[#212529] dark:text-slate-100">{{ $unit->unit_code }}</p>
                            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                                {{ config('wts.unit_types.' . $unit->unit_type_code, $unit->unit_type_code) }}
                                @if ($unit->gross_weight) · {{ $unit->gross_weight }} kg @endif
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        @endif
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <h3 class="text-lg font-bold text-[#212529] dark:text-slate-100">Dati cernita</h3>
        <div class="mt-5 grid gap-6">

            <div>
                <p class="mb-2 text-base font-semibold text-[#333333] dark:text-slate-200">Peso lavorato (kg)</p>
                <input wire:model.live.debounce.400ms="workedWeight" type="text" inputmode="decimal" placeholder="es. 120,5"
                    class="h-14 w-full rounded-2xl border border-slate-300 bg-[#F8F9FA] px-4 text-xl font-semibold text-[#212529] placeholder:font-normal placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                <p class="mt-2 text-xs font-normal text-slate-400 dark:text-slate-500">Calcolato in automatico dai colli selezionati (tutti = peso totale del lotto). Modificabile a mano.</p>
            </div>

            @php
                $weightByLabel = collect($outputWeights)->keyBy('label');
                $fmtKg = static fn ($w): string => number_format((float) $w, 2, ',', '.') . ' kg';
            @endphp

            {{-- Contatori compatti: a fianco il peso calcolato che va nel foglio lavoro. --}}
            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-base font-semibold text-[#333333] dark:text-slate-200">Fusti</p>
                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $fmtKg($weightByLabel['Fusti']['weight'] ?? 0) }}</span>
                </div>
                <div class="mt-2 space-y-2">
                    <div x-data class="grid w-full grid-cols-[4rem_1fr_4rem] overflow-hidden rounded-2xl border border-slate-300 dark:border-slate-600">
                        <button type="button" @click="$wire.set('drumsCount', Math.max(0, ($wire.drumsCount ?? 0) - 1))"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">−</button>
                        <input wire:model.live="drumsCount" type="number" min="0" inputmode="numeric"
                            class="h-14 w-full border-x border-slate-300 bg-[#F8F9FA] text-center text-2xl font-bold text-[#212529] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                        <button type="button" @click="$wire.set('drumsCount', ($wire.drumsCount ?? 0) + 1)"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">+</button>
                    </div>
                    <input wire:model.live.debounce.400ms="drumsWeightOverride" type="text" inputmode="decimal"
                        placeholder="Peso manuale (kg) — opz."
                        class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm font-medium text-[#212529] placeholder:font-normal placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-base font-semibold text-[#333333] dark:text-slate-200">Fusti sporchi</p>
                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $fmtKg($weightByLabel['Fusti sporchi']['weight'] ?? 0) }}</span>
                </div>
                <div class="mt-2 space-y-2">
                    <div x-data class="grid w-full grid-cols-[4rem_1fr_4rem] overflow-hidden rounded-2xl border border-slate-300 dark:border-slate-600">
                        <button type="button" @click="$wire.set('dirtyDrumsCount', Math.max(0, ($wire.dirtyDrumsCount ?? 0) - 1))"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">−</button>
                        <input wire:model.live="dirtyDrumsCount" type="number" min="0" inputmode="numeric"
                            class="h-14 w-full border-x border-slate-300 bg-[#F8F9FA] text-center text-2xl font-bold text-[#212529] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                        <button type="button" @click="$wire.set('dirtyDrumsCount', ($wire.dirtyDrumsCount ?? 0) + 1)"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">+</button>
                    </div>
                    <input wire:model.live.debounce.400ms="dirtyDrumsWeightOverride" type="text" inputmode="decimal"
                        placeholder="Peso manuale (kg) — opz."
                        class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm font-medium text-[#212529] placeholder:font-normal placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-base font-semibold text-[#333333] dark:text-slate-200">Bancali</p>
                    <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $fmtKg($weightByLabel['Bancali']['weight'] ?? 0) }}</span>
                </div>
                <div class="mt-2 space-y-2">
                    <div x-data class="grid w-full grid-cols-[4rem_1fr_4rem] overflow-hidden rounded-2xl border border-slate-300 dark:border-slate-600">
                        <button type="button" @click="$wire.set('palletsCount', Math.max(0, ($wire.palletsCount ?? 0) - 1))"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">−</button>
                        <input wire:model.live="palletsCount" type="number" min="0" inputmode="numeric"
                            class="h-14 w-full border-x border-slate-300 bg-[#F8F9FA] text-center text-2xl font-bold text-[#212529] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                        <button type="button" @click="$wire.set('palletsCount', ($wire.palletsCount ?? 0) + 1)"
                            class="flex h-14 touch-manipulation items-center justify-center bg-slate-100 text-3xl font-bold text-slate-600 active:bg-slate-200 dark:bg-slate-800 dark:text-slate-300">+</button>
                    </div>
                    <input wire:model.live.debounce.400ms="palletsWeightOverride" type="text" inputmode="decimal"
                        placeholder="Peso manuale (kg) — opz."
                        class="h-11 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm font-medium text-[#212529] placeholder:font-normal placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between gap-3">
                    <p class="text-base font-semibold text-[#333333] dark:text-slate-200">CER rimanenza</p>
                    @if ($remainderWeight !== null)
                        <span class="rounded-lg bg-emerald-100 px-3 py-1 text-sm font-bold text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200">Resta sul rifiuto: {{ $fmtKg($remainderWeight) }}</span>
                    @endif
                </div>
                <p class="mb-2 mt-1 text-xs text-slate-500 dark:text-slate-400">Peso lavorato meno fusti/bancali. Lascia vuoto il CER per ereditare dal lotto.</p>
                <input wire:model="remainderWasteCode" type="text"
                    placeholder="{{ $lot?->inboundRecord?->technicalSheetSnapshot?->waste_code ?? '' }}"
                    class="h-14 w-full rounded-2xl border border-slate-300 bg-[#F8F9FA] px-4 text-lg font-semibold text-[#212529] placeholder:font-normal placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" />
            </div>

            <div>
                <p class="mb-2 text-base font-semibold text-[#333333] dark:text-slate-200">Note</p>
                <textarea wire:model="processingNotes" rows="3" placeholder="Note opzionali"
                    class="w-full rounded-2xl border border-slate-300 bg-[#F8F9FA] p-4 text-base text-[#212529] placeholder:text-slate-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"></textarea>
            </div>
        </div>
    </div>

    <button type="button" wire:click="registerProcessing"
        wire:loading.attr="disabled" wire:target="registerProcessing"
        @disabled(!$lot || $units->isEmpty())
        class="w-full touch-manipulation rounded-3xl bg-blue-700 py-5 text-xl font-bold text-white shadow-md active:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-40">
        <span wire:loading.remove wire:target="registerProcessing">Registra selezione e cernita</span>
        <span wire:loading wire:target="registerProcessing">Registrazione in corso...</span>
    </button>

    <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingSheetPage::getUrl(panel: 'wts') }}"
        class="block touch-manipulation rounded-3xl border border-slate-300 py-4 text-center text-base font-semibold text-slate-600 active:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:active:bg-slate-800">
        Stampa foglio lavorazione
    </a>
</x-filament-panels::page>
