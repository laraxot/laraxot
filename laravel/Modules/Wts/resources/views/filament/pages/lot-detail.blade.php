<x-filament-panels::page>
    @if ($selectedLot !== null)
        @php
            $record = $selectedLot->inboundRecord;
            $sheet = $record?->technicalSheetSnapshot;
            $units = $selectedLot->units->sortBy('generated_sequence')->values();
            $totalUnits = max($selectedLot->initial_units_count, $units->count());
            $placeholderUnits = $units->isEmpty() && $totalUnits > 0
                ? collect(range(1, $totalUnits))
                : collect();
            $weightReference = $record?->entry_weight ?? $record?->gross_quantity;
            $averageWeight = $totalUnits > 0 && $weightReference !== null ? ((float) $weightReference / $totalUnits) : null;
            $typeBreakdown = $units
                ->groupBy('unit_type_code')
                ->mapWithKeys(fn ($group, $code) => [$code => [
                    'label' => $unitTypeLabels[$code] ?? $code,
                    'count' => $group->count(),
                ]]);
            $containerCards = collect([
                'big_bag' => 'Big bag',
                'pallet' => 'Pallet',
                'ibc' => 'IBC',
            ])->map(fn ($label, $code) => [
                'code' => $code,
                'label' => $label,
                'count' => $typeBreakdown[$code]['count'] ?? 0,
            ])->values();
        @endphp

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2.3fr)_minmax(320px,1fr)]">
            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-03 - Dettaglio lotto di ingresso</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Qui l'operatore vede il lotto creato dalla presa in carico, controlla i dati principali e gestisce i colli reali del lotto.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" wire:click="reprintAllLabels" @disabled($units->isEmpty())
                                class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-50">
                                Conferma e stampa etichette
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ([
                            ['label' => 'Codice lotto', 'value' => $selectedLot->lot_code],
                            ['label' => 'Scheda tecnica', 'value' => $sheet?->sheet_code ?? 'N/D'],
                            ['label' => 'Formulario', 'value' => $record?->form_number ?? 'N/D'],
                            ['label' => 'Peso preso in carico', 'value' => $weightReference !== null ? number_format((float) $weightReference, 2, ',', '.') . ' kg' : 'N/D'],
                            ['label' => 'Peso allocato', 'value' => $weightReference !== null ? number_format((float) $weightReference, 2, ',', '.') . ' kg invariato' : 'N/D'],
                            ['label' => 'Colli ECOS ipotetici', 'value' => ($record?->ecos_units_count ?? 0) > 0 ? $record->ecos_units_count . ' colli da ECOS' : 'N/D'],
                            ['label' => 'Stato lotto', 'value' => $record?->entry_weight !== null ? 'Peso chiuso' : 'Peso aperto'],
                        ] as $item)
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Dati operativi lotto</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Conferitore</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $sheet?->producer_name ?? $record?->source_company_name ?? 'N/D' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Codice rifiuto / CER</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $sheet?->waste_code ?? 'N/D' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 md:col-span-2 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Descrizione scheda tecnica</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $sheet?->production_description ?? $sheet?->packaging_description ?? 'Descrizione scheda non disponibile' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Area logica successiva</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ config('wts.stock_views.to_process') }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Note interne</p>
                            <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $record?->notes ?? 'Materiale conforme, pronto a scarico' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Gestione colli UI-03</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Aggiungi o togli colli senza modificare il peso totale gia chiuso in pesa. Le tipologie possono cambiare per lotto.</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 lg:grid-cols-3">
                        @foreach ($containerCards as $container)
                            <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $container['label'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            @if ($container['count'] > 0)
                                                {{ $container['count'] }} colli reali nel lotto
                                            @else
                                                0 colli reali nel lotto, ma tipologia sempre gestibile da mockup
                                            @endif
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $container['count'] }}</span>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <button type="button" wire:click="addUnits('{{ $container['code'] }}', 1)"
                                        class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                                        + Aggiungi {{ $container['label'] }}
                                    </button>
                                    <button type="button" wire:click="removeUnit({{ $units->where('unit_type_code', $container['code'])->where('status_code', 'available')->last()?->id ?? 0 }})"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                        - Togli {{ $container['label'] }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($units->isEmpty())
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                            Nessun collo reale ancora generato. La sezione resta comunque visibile come mockup operativo, mentre UI-04 resta solo supporto tecnico per rigenerazione o ristampa.
                        </p>
                    @endif

                    <div class="mt-5 grid gap-4 md:grid-cols-3">
                        @foreach ([
                            ['label' => 'Colli attuali', 'value' => (string) $totalUnits, 'note' => 'Totale colli del lotto, indipendente dalla tipologia'],
                            ['label' => 'Peso medio stimato', 'value' => $averageWeight !== null ? number_format($averageWeight, 1, ',', '.') . ' kg' : 'N/D', 'note' => 'Calcolato da peso chiuso / colli'],
                            ['label' => 'Peso totale', 'value' => $weightReference !== null ? number_format((float) $weightReference, 2, ',', '.') . ' kg' : 'N/D', 'note' => 'Non cambia aggiungendo o togliendo colli'],
                        ] as $item)
                            <div class="rounded-2xl bg-slate-50 p-5 dark:bg-slate-800">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $item['note'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Colli</h3>
                            @if ($units->isNotEmpty())
                                <p class="text-xs text-slate-500 dark:text-slate-400">Colli reali generati per questo lotto.</p>
                            @elseif ($placeholderUnits->isNotEmpty())
                                <p class="text-xs text-slate-500 dark:text-slate-400">Anteprima non operativa: {{ $totalUnits }} colli stimati da generare con i pulsanti sopra.</p>
                            @else
                                <p class="text-xs text-slate-500 dark:text-slate-400">Nessun collo ancora generato per questo lotto.</p>
                            @endif
                        </div>
                    </div>
                    <div class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse ($units as $unit)
                            <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $unit->unit_code }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $unitTypeLabels[$unit->unit_type_code] ?? $unit->unit_type_code }} • collo {{ $unit->generated_sequence }} di {{ $totalUnits }} • peso imputato {{ $averageWeight !== null ? number_format($averageWeight, 1, ',', '.') . ' kg' : 'N/D' }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $unit->status_code === 'available' ? 'Creato' : 'Preso in carico' }}</span>

                                    <select
                                        wire:change="updateUnitType({{ $unit->id }}, $event.target.value)"
                                        class="rounded-lg border border-slate-300 px-2 py-1.5 text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
                                        aria-label="Tipo collo {{ $unit->unit_code }}"
                                    >
                                        @foreach ($unitTypeLabels as $code => $label)
                                            <option value="{{ $code }}" @selected($unit->unit_type_code === $code)>{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" wire:click="queueUnitLabelPrint({{ $unit->id }})"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-600">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659" />
                                        </svg>
                                        Stampa
                                    </button>

                                    @if ($unit->status_code === 'available')
                                        <button type="button" wire:click="removeUnit({{ $unit->id }})"
                                            class="rounded-lg border border-slate-300 px-2.5 py-2 text-xs font-medium text-slate-600 transition hover:bg-rose-50 hover:text-rose-700 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-rose-500/10"
                                            title="Togli questo collo dal lotto">
                                            Togli
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            @if ($placeholderUnits->isNotEmpty())
                                @foreach ($placeholderUnits as $sequence)
                                    <div class="flex flex-wrap items-center justify-between gap-3 border-l-4 border-dashed border-amber-300 bg-amber-50/60 px-6 py-4 dark:border-amber-500/40 dark:bg-amber-500/10">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Collo {{ $sequence }} di {{ $totalUnits }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                Da generare • tipologia da scegliere • peso imputato stimato {{ $averageWeight !== null ? number_format($averageWeight, 1, ',', '.') . ' kg' : 'N/D' }}
                                            </p>
                                        </div>
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900 dark:bg-amber-500/20 dark:text-amber-100">Anteprima</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="px-6 py-8 text-sm text-slate-500 dark:text-slate-400">
                                    Nessun collo generato. Usa <span class="font-semibold text-slate-700 dark:text-slate-200">+ Aggiungi Big bag</span>, <span class="font-semibold text-slate-700 dark:text-slate-200">Pallet</span> o <span class="font-semibold text-slate-700 dark:text-slate-200">IBC</span> per creare i colli reali del lotto.
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Passo successivo reale</h3>
                    <div class="mt-4 flex flex-col gap-3">
                        <button type="button" wire:click="queueAllLabelsPrint"
                            @disabled($units->isEmpty())
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-50">
                            Stampa tutte le etichette (etichettatrice)
                        </button>
                        <button type="button" wire:click="reprintAllLabels"
                            @disabled($units->isEmpty())
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Scarica PDF etichette
                        </button>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsWorkQueuePage::getUrl(panel: 'wts') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Vai alla giacenza operativa WTS1
                        </a>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingPage::getUrl(panel: 'wts', parameters: ['lot' => $selectedLot->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Apri lavorazioni lotto
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Le etichette si stampano da qui: singolo collo dalla riga, oppure tutte insieme con il pulsante sopra. Non serve piu' passare da una schermata separata.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10">
                    <h3 class="text-base font-semibold text-amber-950 dark:text-amber-100">Segnala non conformita</h3>
                    <p class="mt-1 text-xs text-amber-800/80 dark:text-amber-200/80">Invia una segnalazione al referente qualita per questo lotto.</p>
                    <textarea wire:model="nonConformityNote" rows="3" placeholder="Descrivi l'anomalia riscontrata"
                        class="mt-3 w-full rounded-xl border border-amber-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-amber-800/40 dark:border-amber-500/40 dark:bg-slate-900 dark:text-slate-100"></textarea>
                    <button type="button" wire:click="reportNonConformity"
                        class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-500">
                        Segnala non conformita
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Nessun lotto selezionato</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Seleziona un lotto dalla lista ingressi per visualizzare il dettaglio e gestire i colli.</p>
                <a href="{{ \Modules\Wts\Filament\Pages\WtsIngressesPage::getUrl(panel: 'wts') }}"
                    class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                    Torna alla lista ingressi
                </a>
            </div>
        </div>
    @endif
</x-filament-panels::page>
