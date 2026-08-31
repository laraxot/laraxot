<x-filament-panels::page>
    @php
        $queueByAreaCount = $units->count();
        $longQueueHint = $queueByAreaCount > 12;
    @endphp

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-05 - Giacenza operativa WTS</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        Vista giacenza per reparto: i riferimenti principali sono sempre ST, CER/EER e descrizione scheda. Lotto e QR restano dati tecnici secondari di tracciabilita.
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="grid gap-4 xl:grid-cols-3">
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Vista magazzino
                        <select wire:model.live="viewFilter" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @foreach ($stockViews as $code => $label)
                                <option value="{{ $code }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Cerca ST, CER/EER o descrizione
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Es. ST-2026-00944 o 15 01 10*"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Ricerca tecnica (lotto, collo, QR)
                        <input wire:model.live.debounce.300ms="technicalSearch" type="text" placeholder="Es. LOT-2026-0001, collo o QR"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                </div>
                <div class="grid gap-4 grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Giacenze: <span class="font-semibold">{{ $queueByAreaCount }}</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Riferimenti: <span class="font-semibold">ST, CER/EER e descrizione</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Dati secondari: <span class="font-semibold">lotto, collo e QR</span>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Vista: <span class="font-semibold">solo colli disponibili</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-300 bg-amber-50 px-6 py-4 text-amber-900 shadow-sm dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100">
            <p class="text-sm font-black uppercase tracking-wide">DATI APPLICATIVI LOCALI</p>
            <p class="mt-1 text-sm font-semibold">Questa vista non interroga ancora ECOS live: mostra i colli generati nell'applicativo WTS.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Giacenze per lavorazione</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">La schermata resta leggibile anche con molte giacenze: davanti mettiamo ST, CER/EER e descrizione, mentre i riferimenti lotto/QR restano di supporto.</p>
                    </div>
                    @if ($longQueueHint)
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 dark:bg-sky-500/10 dark:text-sky-100">Molte giacenze: usa filtri e ricerca per ST/CER</span>
                    @endif
                </div>
            </div>

            @forelse ($units as $unit)
                @php
                    $lot = $unit->inboundLot;
                    $record = $lot?->inboundRecord;
                    $sheet = $unit->technicalSheetSnapshot ?? $record?->technicalSheetSnapshot;
                    $lastStep = $unit->processingSteps->sortByDesc('executed_at')->first();
                    $assignedFlow = $lastStep !== null
                        ? ($stepLabels[$lastStep->step_type_code] ?? $lastStep->step_type_code)
                        : 'Da definire in base a scheda tecnica e CER/EER';
                    $assignmentReason = $lastStep !== null
                        ? 'Gia instradata tramite ultima lavorazione registrata'
                        : 'Instradamento operativo da confermare con scheda tecnica e CER/EER';
                @endphp

                <div class="grid gap-4 border-t border-slate-100 px-6 py-4 first:border-t-0 xl:grid-cols-[minmax(320px,2fr)_minmax(200px,0.7fr)_minmax(200px,0.8fr)_auto] xl:items-center dark:border-slate-800">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $sheet?->sheet_code ?? 'Scheda assente' }}</p>
                            <span class="text-sm text-slate-500 dark:text-slate-400">CER {{ $sheet?->waste_code ?? 'N/D' }}</span>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 dark:bg-amber-400/20 dark:text-amber-100">
                                {{ $stockViews[$unit->current_area_code] ?? $unit->current_area_code }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $sheet?->production_description ?? $sheet?->packaging_description ?? 'Descrizione scheda non disponibile' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $sheet?->producer_name ?? $record?->source_company_name ?? 'Produttore assente' }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Lotto {{ $lot?->lot_code ?? 'Assente' }} • Collo {{ $unit->unit_code }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 text-sm dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Lavorazione disponibile</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-slate-100">{{ $assignedFlow }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 text-sm dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Motivo assegnazione</p>
                        <p class="mt-2 text-slate-700 dark:text-slate-200">{{ $assignmentReason }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3 xl:justify-end">
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts', parameters: ['lot' => $lot?->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                            Apri lavorazione assegnata
                        </a>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsMaterialDetailPage::getUrl(panel: 'wts', parameters: ['unit' => $unit->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800">
                            Apri scheda collo
                        </a>
                        <button type="button" wire:click="moveUnit({{ $unit->id }}, 'in_process', '{{ $unit->location_label ?? 'Reparto lavorazione' }}')"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Passa in lavorazione
                        </button>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot?->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Dettaglio lotto e colli
                        </a>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsInventoryPage::getUrl(panel: 'wts') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Stampa giacenza
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                    Nessuna giacenza da lavorare al momento.
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
