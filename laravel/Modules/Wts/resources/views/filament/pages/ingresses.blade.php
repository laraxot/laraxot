<x-filament-panels::page>
    @php
        $demoIngresses = [
            [
                'progressive' => '118871',
                'sheet' => 'ST-2026-00944',
                'description' => 'Sfridi da linea stampa con imballaggi misti',
                'producer' => 'Grafica Veneta Spa',
                'carrier' => 'CAPPELLOTTO SRL',
                'waste' => '12 01 03',
                'hp' => 'HP14',
                'form' => 'FIR-2026-001',
                'vehicle' => 'AB123CD',
                'status' => 'Peso chiuso',
                'statusClass' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-100',
                'weight' => '1.238 kg',
                'units' => '10 colli',
                'ecos_units' => '6 big bag + 4 pallet ipotetici ECOS',
            ],
            [
                'progressive' => '118872',
                'sheet' => 'ST-2026-00945',
                'description' => 'Imballaggi verniciati da selezione manuale',
                'producer' => 'ISVEA Vernici Srl',
                'carrier' => 'NARDELLI SRL',
                'waste' => '15 01 10*',
                'hp' => 'HP4 HP14',
                'form' => 'FIR-2026-002',
                'vehicle' => 'CD456EF',
                'status' => 'Peso aperto',
                'statusClass' => 'bg-amber-100 text-amber-800 dark:bg-amber-400/20 dark:text-amber-100',
                'weight' => 'Da chiudere',
                'units' => 'Colli da confermare',
                'ecos_units' => '3 pallet ipotetici ECOS',
            ],
            [
                'progressive' => '118873',
                'sheet' => 'ST-2026-00946',
                'description' => 'Residui metallici con plastica e vetro in miscela',
                'producer' => 'ST Powder Coating Spa',
                'carrier' => 'MARIANI SRL',
                'waste' => '08 01 11*',
                'hp' => 'HP5 HP6 HP14',
                'form' => 'FIR-2026-003',
                'vehicle' => 'EF789GH',
                'status' => 'Peso chiuso',
                'statusClass' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-100',
                'weight' => '620 kg',
                'units' => '4 colli',
                'ecos_units' => '2 IBC + 2 pallet ipotetici ECOS',
            ],
        ];

        $selectedDateValue = $this->receivedDate !== '' ? $this->receivedDate : now()->toDateString();
        $selectedDateLabel = \Illuminate\Support\Carbon::parse($selectedDateValue)->format('d/m/Y');

        $showDemoFallback = in_array($dataSourceStatus['label'], ['DATI DEMO', 'DATI DEMO - ECOS NON RAGGIUNGIBILE'], true);
        $summaryRows = $ingresses->isEmpty() && $showDemoFallback ? collect($demoIngresses) : $ingresses;
        $closedIngressesCount = $summaryRows->filter(function ($row): bool {
            if (is_array($row)) {
                return $row['status'] === 'Peso chiuso';
            }

            // Fonte corretta: bspart.part_chiuso. Un viaggio non ancora arrivato
            // non ha peso chiuso ne' aperto, semplicemente non e' pesato.
            return $row->is_weight_closed === true;
        })->count();
        $openIngressesCount = max($summaryRows->count() - $closedIngressesCount, 0);
        $ecosConnectedButEmpty = ! $showDemoFallback && $ingresses->isEmpty() && $dataSourceStatus['label'] === 'DATI DEMO - NESSUN INGRESSO ECOS';
        // $payload e' quello del VIAGGIO (con ripiego sulla scheda tecnica per i
        // record sincronizzati prima): la scheda e' condivisa da tutti i
        // conferimenti con lo stesso ST, quindi non puo' dettare progressivo,
        // tipo documento e colli attesi della singola riga.
        $resolveEcosUnits = static function ($payload, $lot, $units): string {
            if (is_array($payload)) {
                foreach (['ecos_units', 'estimated_units', 'hypothetical_units', 'colli_ecos', 'colli_ipotetici'] as $key) {
                    $value = data_get($payload, $key);

                    if (is_scalar($value) && trim((string) $value) !== '') {
                        return (string) $value;
                    }
                }
            }

            if ($lot !== null && $lot->initial_units_count > 0) {
                return $lot->initial_units_count . ' colli stimati';
            }

            if ($units->count() > 0) {
                return $units->count() . ' colli generati';
            }

            return 'Non disponibili dal legacy';
        };
    @endphp

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-01 - Prima schermata operativa</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        La lista parte da programmazione viaggi e scheda tecnica letta in sola lettura dal legacy, poi porta subito alla generazione collarini/QCODE.
                    </p>
                </div>

                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Cerca per scheda tecnica, CER/EER, descrizione scheda, formulario o produttore
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Es. ST-2026-00944, 15 01 10* o imballaggi verniciati"
                        class="h-11 min-w-80 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none ring-0 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    />
                </label>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Conferimenti del giorno</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Vista ispirata alla schermata ricevuta: filtro giorno, viaggi previsti, arrivi gia registrati, descrizione scheda tecnica in evidenza e solo movimenti di ingresso utili all'operatore.</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Data movimento utile: prima `mte_dat_part`, poi `mte_dat_r`, infine `received_at` applicativa.</p>
                </div>
                <div class="flex flex-col gap-3 sm:items-end">
                    <label class="grid gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        Data conferimenti
                        <div class="relative">
                            <input
                                wire:model.live="receivedDate"
                                type="date"
                                class="h-14 min-w-[220px] rounded-xl border border-slate-300 bg-white px-4 pr-11 text-base font-semibold text-slate-900 shadow-sm outline-none ring-0 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            />
                            <svg class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z" />
                            </svg>
                        </div>
                    </label>
                    <button
                        type="button"
                        wire:click="$refresh"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:opacity-60 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
                        title="Rilegge da ECOS formulario, colli e peso dei viaggi arrivati"
                    >
                        <svg wire:loading.class="animate-spin" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356M3.031 20.644v-4.992h4.992m-4.665-4.5a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m-18 3.182 3.181 3.182a8.25 8.25 0 0 0 13.803-3.7" />
                        </svg>
                        Aggiorna
                    </button>
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $selectedDateLabel }}</span>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-100">{{ $closedIngressesCount }} pesi chiusi</span>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-400/20 dark:text-amber-100">{{ $openIngressesCount }} pesi aperti</span>
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-800 dark:bg-sky-500/10 dark:text-sky-100">Solo movimenti ingresso utili</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border px-6 py-4 shadow-sm {{ $dataSourceStatus['class'] }}">
            <p class="text-sm font-black uppercase tracking-wide">{{ $dataSourceStatus['label'] }}</p>
            <p class="mt-1 text-sm font-semibold">{{ $dataSourceStatus['description'] }}</p>
        </div>

        @if ($ecosConnectedButEmpty)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-5m16 0h-2.586a1 1 0 0 0-.707.293l-2.414 2.414a1 1 0 0 1-.707.293h-3.172a1 1 0 0 1-.707-.293l-2.414-2.414A1 1 0 0 0 6.586 13H4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Nessun ingresso per la data selezionata</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">La query ECOS e stata eseguita con successo, ma non ha restituito conferimenti per il giorno {{ $selectedDateLabel }}.</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Prova a cambiare data o verifica la programmazione viaggi.</p>
                </div>
            </div>
        @endif

        @if ($ingresses->isEmpty() && $showDemoFallback)
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid grid-cols-[0.55fr_0.9fr_1.2fr_1fr_0.7fr_0.7fr_0.8fr_1fr] gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                    <span>Prog.</span>
                    <span>Scheda tecnica</span>
                    <span>Produttore</span>
                    <span>Trasportatore</span>
                    <span>CER</span>
                    <span>Peso</span>
                    <span>Stato</span>
                    <span>Porta stampa</span>
                </div>
                @foreach ($demoIngresses as $demo)
                    <div class="grid grid-cols-[0.55fr_0.9fr_1.2fr_1fr_0.7fr_0.7fr_0.8fr_1fr] gap-3 border-b border-slate-100 px-4 py-4 text-sm last:border-b-0 dark:border-slate-800">
                        <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $demo['progressive'] }}</span>
                        <span>
                            <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $demo['sheet'] }}</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $demo['form'] }} / {{ $demo['vehicle'] }}</span>
                            <span class="mt-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Descrizione scheda</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $demo['description'] }}</span>
                        </span>
                        <span class="text-slate-700 dark:text-slate-200">{{ $demo['producer'] }}</span>
                        <span class="text-slate-600 dark:text-slate-300">{{ $demo['carrier'] }}</span>
                        <span>
                            <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $demo['waste'] }}</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $demo['hp'] }}</span>
                            <span class="mt-1 block text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Colli ECOS stimati</span>
                            <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $demo['ecos_units'] }}</span>
                        </span>
                        <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $demo['weight'] }}</span>
                        <span><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $demo['statusClass'] }}">{{ $demo['status'] }}</span></span>
                        <span class="flex flex-col gap-2">
                            <span class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-900 dark:bg-amber-500/10 dark:text-amber-100">
                                Esempio demo: con ECOS attivo compare il pulsante reale.
                            </span>
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $rows = $ingresses->map(function ($ingress) use ($resolveEcosUnits) {
                $sheet = $ingress->technicalSheetSnapshot;
                $lot = $ingress->lots->first();
                $units = $lot?->units ?? collect();
                $payload = is_array($ingress->legacy_payload) ? $ingress->legacy_payload : $sheet?->legacy_payload;

                $hasRealUnits = $ingress->ecos_units_count !== null;
                $hasRealWeight = $ingress->entry_weight !== null;

                return [
                    'model' => $ingress,
                    'lot' => $lot,
                    'unitsCount' => $units->count(),
                    'progressive' => \Modules\Wts\Support\WtsLegacyPayload::firstString($payload, ['program_number'])
                        ?? $ingress->legacy_reference ?? 'N/D',
                    'documentType' => \Modules\Wts\Support\WtsLegacyPayload::firstString($payload, ['document_type']) ?? 'ING',
                    'sheetCode' => $sheet?->sheet_code,
                    'sheetDescription' => $sheet?->production_description ?? $sheet?->packaging_description,
                    'producer' => $sheet?->producer_name ?? $ingress->source_company_name ?? 'N/D',
                    'commercial' => $ingress->commercial_company_name,
                    'carrier' => $ingress->vehicle_reference,
                    'hp' => $sheet?->hp_codes,
                    'waste' => $sheet?->waste_code,
                    'units' => $hasRealUnits ? (string) $ingress->ecos_units_count : $resolveEcosUnits($payload, $lot, $units),
                    'unitsAreReal' => $hasRealUnits,
                    'weight' => $hasRealWeight
                        ? number_format((float) $ingress->entry_weight, 0, ',', '.')
                        : ($ingress->gross_quantity > 0 ? number_format((float) $ingress->gross_quantity, 0, ',', '.') : null),
                    'weightIsReal' => $hasRealWeight,
                    'formNumber' => $ingress->form_number,
                    'batchNumber' => $ingress->batch_number,
                    // Lo stato peso arriva da bspart.part_chiuso: se il viaggio non e'
                    // ancora arrivato in ECOS resta null e non mostriamo alcuno stato.
                    'weightClosed' => $ingress->is_weight_closed,
                ];
            });
        @endphp

        {{-- DESKTOP: tabella incolonnata come la schermata bbros --}}
        {{-- Compatta (text-xs, padding ridotto, colonne testo lungo troncate con --}}
        {{-- ellissi + tooltip) per far stare tutte le 13 colonne senza scroll. --}}
        <div class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm md:block dark:border-slate-800 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead class="bg-slate-50 text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                        <tr>
                            <th class="px-2 py-2.5">Progr.</th>
                            <th class="px-2 py-2.5">Tipo</th>
                            <th class="px-2 py-2.5">Scheda tecnica</th>
                            <th class="px-2 py-2.5">Produttore</th>
                            <th class="px-2 py-2.5">Commerciale</th>
                            <th class="px-2 py-2.5">Trasportatore</th>
                            <th class="px-2 py-2.5">Cl. pericolo</th>
                            <th class="px-2 py-2.5">CER</th>
                            <th class="px-2 py-2.5 text-right">Colli</th>
                            <th class="px-2 py-2.5 text-right">Peso</th>
                            <th class="px-2 py-2.5">Formulario</th>
                            <th class="px-2 py-2.5">Partita</th>
                            <th class="px-2 py-2.5">Stampa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($rows as $row)
                            <tr class="align-top hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="whitespace-nowrap px-2 py-2.5 font-semibold text-slate-950 dark:text-slate-100">{{ $row['progressive'] }}</td>
                                <td class="whitespace-nowrap px-2 py-2.5 text-slate-600 dark:text-slate-300">{{ $row['documentType'] }}</td>
                                <td class="px-2 py-2.5">
                                    <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $row['sheetCode'] ?? 'N/D' }}</span>
                                    @if ($row['sheetDescription'] !== null)
                                        <span class="mt-0.5 block max-w-[9rem] truncate text-[0.7rem] text-slate-500 dark:text-slate-400" title="{{ $row['sheetDescription'] }}">{{ $row['sheetDescription'] }}</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2.5 text-slate-700 dark:text-slate-200">
                                    <span class="block max-w-[6.5rem] truncate" title="{{ $row['producer'] }}">{{ $row['producer'] }}</span>
                                </td>
                                <td class="px-2 py-2.5 text-slate-600 dark:text-slate-300">
                                    <span class="block max-w-[6.5rem] truncate" title="{{ $row['commercial'] ?? '' }}">{{ $row['commercial'] ?? '—' }}</span>
                                </td>
                                <td class="px-2 py-2.5 text-slate-600 dark:text-slate-300">
                                    <span class="block max-w-[6.5rem] truncate" title="{{ $row['carrier'] ?? '' }}">{{ $row['carrier'] ?? '—' }}</span>
                                </td>
                                <td class="px-2 py-2.5">
                                    @if ($row['hp'] !== null)
                                        <span class="inline-block rounded bg-rose-50 px-1.5 py-0.5 text-[0.65rem] font-semibold text-rose-800 dark:bg-rose-500/10 dark:text-rose-200">{{ $row['hp'] }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-2 py-2.5 font-medium text-slate-950 dark:text-slate-100">{{ $row['waste'] ?? '—' }}</td>
                                <td class="whitespace-nowrap px-2 py-2.5 text-right {{ $row['unitsAreReal'] ? 'font-semibold text-slate-950 dark:text-slate-100' : 'text-slate-400' }}">{{ $row['units'] }}</td>
                                <td class="whitespace-nowrap px-2 py-2.5 text-right {{ $row['weightIsReal'] ? 'font-semibold text-slate-950 dark:text-slate-100' : 'text-slate-400' }}">{{ $row['weight'] ?? '—' }}</td>
                                <td class="whitespace-nowrap px-2 py-2.5">
                                    @if ($row['formNumber'] !== null)
                                        <span class="font-medium text-blue-700 dark:text-blue-300">{{ $row['formNumber'] }}</span>
                                    @else
                                        <span class="text-slate-400" title="Il viaggio non e' ancora arrivato: ECOS non ha il formulario">—</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-2 py-2.5 text-slate-700 dark:text-slate-200">{{ $row['batchNumber'] ?? '—' }}</td>
                                <td class="px-2 py-2.5">
                                    @include('wts::filament.pages.partials.ingress-actions', ['ingress' => $row['model'], 'lot' => $row['lot'], 'unitsCount' => $row['unitsCount'], 'compact' => true])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE: card leggibili senza scroll orizzontale --}}
        <div class="grid gap-4 md:hidden">
            @foreach ($rows as $row)
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $row['progressive'] }} · {{ $row['documentType'] }}</p>
                            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $row['sheetCode'] ?? 'N/D' }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $row['producer'] }}</p>
                        </div>
                        @if ($row['hp'] !== null)
                            <span class="rounded bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-800 dark:bg-rose-500/10 dark:text-rose-200">{{ $row['hp'] }}</span>
                        @endif
                    </div>

                    <dl class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">CER</dt>
                            <dd class="font-medium text-slate-950 dark:text-slate-100">{{ $row['waste'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Trasportatore</dt>
                            <dd class="text-slate-700 dark:text-slate-200">{{ $row['carrier'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Colli</dt>
                            <dd class="{{ $row['unitsAreReal'] ? 'font-semibold text-slate-950 dark:text-slate-100' : 'text-slate-400' }}">{{ $row['units'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Peso</dt>
                            <dd class="{{ $row['weightIsReal'] ? 'font-semibold text-slate-950 dark:text-slate-100' : 'text-slate-400' }}">{{ $row['weight'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Formulario</dt>
                            <dd class="font-medium text-blue-700 dark:text-blue-300">{{ $row['formNumber'] ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Partita</dt>
                            <dd class="text-slate-700 dark:text-slate-200">{{ $row['batchNumber'] ?? '—' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        @include('wts::filament.pages.partials.ingress-actions', ['ingress' => $row['model'], 'lot' => $row['lot'], 'unitsCount' => $row['unitsCount'], 'compact' => false])
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
