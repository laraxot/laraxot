<x-filament-panels::page>
    @php $isWtsAdmin = \App\Support\AccountFeatures::isWtsAdmin(auth()->user()); @endphp

    @if (! $isWtsAdmin)
        {{-- Modalità Brondi: partita + colli selezionabili + 4 tasti lavorazione grandi. --}}
        <div class="mx-auto max-w-3xl space-y-4">
            {{-- Partita individuata --}}
            <label class="block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="text-lg font-bold text-slate-950 dark:text-slate-100">Partita individuata</span>
                <select wire:model.live="lotId"
                    class="mt-2 h-14 w-full rounded-xl border border-slate-300 bg-white px-4 text-lg font-semibold text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    @foreach ($lots as $lot)
                        <option value="{{ $lot->id }}">{{ $lot->lot_code }} - {{ $lot->inboundRecord?->technicalSheetSnapshot?->sheet_code ?? 'Scheda assente' }}</option>
                    @endforeach
                </select>
            </label>

            {{-- Colli da mandare in lavorazione (tap per selezionare/deselezionare) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-lg font-bold text-slate-950 dark:text-slate-100">Colli da mandare in lavorazione</span>
                    <button type="button" wire:click="selectAllUnits"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-base font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Tutti</button>
                </div>
                @if ($selectedUnits->isEmpty())
                    <p class="mt-3 text-base text-slate-500 dark:text-slate-400">Nessun collo disponibile per questa partita.</p>
                @else
                    <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach ($selectedUnits as $unit)
                            @php $isSelected = in_array($unit->id, $selectedUnitIds); @endphp
                            <button type="button" wire:click="toggleUnit({{ $unit->id }})"
                                class="flex items-center justify-between gap-3 rounded-2xl border-2 p-4 text-left transition @if ($isSelected) border-emerald-500 bg-emerald-50 dark:border-emerald-500/50 dark:bg-emerald-500/10 @else border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800 @endif">
                                <span class="text-base font-bold text-slate-950 dark:text-slate-100">{{ $unit->unit_code }}</span>
                                @if ($isSelected)
                                    @svg('heroicon-s-check-circle', 'h-8 w-8 shrink-0 text-emerald-600')
                                @else
                                    @svg('heroicon-o-plus-circle', 'h-8 w-8 shrink-0 text-slate-400')
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 4 tasti lavorazione: portano partita + colli scelti --}}
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                @foreach ([
                    ['page' => \Modules\Wts\Filament\Pages\WtsSortingPage::class, 'label' => 'Selezione e cernita', 'icon' => 'heroicon-o-squares-2x2', 'color' => 'bg-blue-600 hover:bg-blue-500'],
                    ['page' => \Modules\Wts\Filament\Pages\WtsUnpackagingPage::class, 'label' => 'Sconfezionamento', 'icon' => 'heroicon-o-archive-box-x-mark', 'color' => 'bg-emerald-600 hover:bg-emerald-500'],
                    ['page' => \Modules\Wts\Filament\Pages\WtsGrindingWashingPage::class, 'label' => 'Triturazione e lavaggio', 'icon' => 'heroicon-o-beaker', 'color' => 'bg-amber-600 hover:bg-amber-500'],
                    ['page' => \Modules\Wts\Filament\Pages\WtsPressingPage::class, 'label' => 'Pressatura', 'icon' => 'heroicon-o-arrows-pointing-in', 'color' => 'bg-rose-600 hover:bg-rose-500'],
                ] as $tile)
                    <a href="{{ $tile['page']::getUrl(panel: 'wts', parameters: ['lot' => $lotId, 'units' => implode(',', $selectedUnitIds)]) }}"
                        class="flex min-h-[9rem] min-w-0 flex-col items-center justify-center gap-2 overflow-hidden rounded-3xl {{ $tile['color'] }} p-4 text-center text-white shadow-lg transition active:scale-[0.98] sm:min-h-[11rem] sm:gap-3">
                        @svg($tile['icon'], 'h-10 w-10 shrink-0 sm:h-14 sm:w-14')
                        <span class="w-full break-words px-1 text-base font-bold leading-tight sm:text-xl md:text-2xl">{{ $tile['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @else
    <div class="grid gap-6 xl:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-07 - Seleziona i colli e apri la lavorazione</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Questa schermata resta uno snodo operativo: l'operatore parte da QR/collo, individua la partita corretta, sceglie quanti colli della partita mandare e poi apre la pagina specifica della lavorazione.
                        </p>
                    </div>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Partita individuata
                        <select wire:model.live="lotId" class="h-11 min-w-72 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            @foreach ($lots as $lot)
                                <option value="{{ $lot->id }}">{{ $lot->lot_code }} - {{ $lot->inboundRecord?->technicalSheetSnapshot?->sheet_code ?? 'Scheda assente' }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Contesto materiale</h3>
                    @if ($selectedLot !== null)
                        @php $sheet = $selectedLot->inboundRecord?->technicalSheetSnapshot; @endphp
                        <div class="mt-4 grid gap-4">
                            @foreach ([
                                ['label' => 'Scheda tecnica', 'value' => $sheet?->sheet_code ?? 'N/D'],
                                ['label' => 'CER / EER', 'value' => $sheet?->waste_code ?? 'N/D'],
                                ['label' => 'Descrizione scheda tecnica', 'value' => $sheet?->production_description ?? $sheet?->packaging_description ?? 'N/D'],
                                ['label' => 'Produttore', 'value' => $sheet?->producer_name ?? 'N/D'],
                                ['label' => 'Partita / lotto', 'value' => $selectedLot->lot_code],
                                ['label' => 'Colli disponibili', 'value' => (string) $selectedUnits->count()],
                            ] as $item)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Nessun lotto disponibile.</p>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Logica di utilizzo</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            Punto di partenza: <span class="font-semibold text-slate-950 dark:text-slate-100">QR o collo della partita</span>.
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            Riferimenti primari: <span class="font-semibold text-slate-950 dark:text-slate-100">ST, CER/EER e descrizione scheda</span>.
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            Qui scegli <span class="font-semibold text-slate-950 dark:text-slate-100">quanti colli della partita</span> mandare alla lavorazione.
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                            Le pagine sotto hanno campi diversi: triturazione, pressatura, selezione e sconfezionamento non condividono la stessa maschera finale.
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Colli selezionabili della partita</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Seleziona solo i colli della partita che vuoi mandare alla lavorazione scelta sotto. I colli gia lavorati escono dalla giacenza WTS1.</p>
                    </div>
                    <button wire:click="selectAllUnits" type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Seleziona tutti
                    </button>
                </div>

                @if ($selectedUnits->isEmpty())
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Nessun collo disponibile da lavorare.</p>
                @endif

                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @foreach ($selectedUnits as $unit)
                        @php $isSelected = in_array($unit->id, $selectedUnitIds); @endphp
                        <div class="rounded-2xl border p-4 transition @if ($isSelected) border-blue-300 bg-blue-50 dark:border-blue-500/40 dark:bg-blue-500/10 @else border-slate-200 dark:border-slate-700 @endif">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $unit->unit_code }}</p>
                                        @if ($isSelected)
                                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/20 dark:text-blue-100">Selezionato</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Sotto-numero {{ str_pad((string) $unit->generated_sequence, 2, '0', STR_PAD_LEFT) }} • {{ $unit->qr_code }}</p>
                                </div>
                                <button wire:click="deselectUnit({{ $unit->id }})" type="button"
                                    class="inline-flex items-center rounded-lg border px-3 py-1 text-xs font-medium transition @if ($isSelected) border-blue-300 text-blue-700 hover:bg-blue-100 dark:border-blue-500/40 dark:text-blue-100 dark:hover:bg-blue-500/10 @else border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 @endif">
                                    Deseleziona
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Apri la lavorazione corretta</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Dopo aver selezionato i colli, apri la lavorazione giusta. Le pagine operative sotto sono separate apposta perche i campi cambiano da caso a caso.</p>
                </div>
                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    @foreach ([
                        ['title' => 'Selezione e cernita', 'body' => 'Per cernita rapida, scelta colli e output come fusti, fusti sporchi e bancali.', 'url' => \Modules\Wts\Filament\Pages\WtsSortingPage::getUrl(panel: 'wts', parameters: ['lot' => $lotId]), 'cta' => 'Seleziona colli e apri selezione'],
                        ['title' => 'Sconfezionamento', 'body' => 'Per aprire il collo e registrare materiali prodotti e rimanenza.', 'url' => \Modules\Wts\Filament\Pages\WtsUnpackagingPage::getUrl(panel: 'wts', parameters: ['lot' => $lotId]), 'cta' => 'Seleziona colli e apri sconfezionamento'],
                        ['title' => 'Triturazione e lavaggio', 'body' => 'Per scegliere il materiale ottenuto dalla triturazione, gestire lavaggio on/off e pesi generati.', 'url' => \Modules\Wts\Filament\Pages\WtsGrindingWashingPage::getUrl(panel: 'wts', parameters: ['lot' => $lotId]), 'cta' => 'Seleziona colli e apri triturazione'],
                        ['title' => 'Pressatura', 'body' => 'Per gestire pressa, balle prodotte e casi multi-partita con foglio dedicato.', 'url' => \Modules\Wts\Filament\Pages\WtsPressingPage::getUrl(panel: 'wts', parameters: ['lot' => $lotId]), 'cta' => 'Seleziona colli e apri pressatura'],
                    ] as $flow)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <p class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $flow['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $flow['body'] }}</p>
                            <a href="{{ $flow['url'] }}"
                                class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                                {{ $flow['cta'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Riepilogo rapido</h3>
                <div class="mt-4 space-y-3">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Colli selezionati</p>
                        <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ count($selectedUnitIds) }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Lavorazioni disponibili</p>
                        <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">4</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Parametri standard</p>
                        <div class="mt-2 space-y-1 text-sm text-slate-700 dark:text-slate-200">
                            @foreach ($operationalParameters as $parameter)
                                @if (($parameter['unit'] ?? '') === 'kg')
                                    <p>{{ $parameter['label'] }}: <span class="font-semibold">{{ $parameter['value'] }} {{ $parameter['unit'] }}</span></p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @php
                // In modalita Brondi l'operatore vede solo i quattro tasti delle
                // lavorazioni: i passi successivi restano a chi puo davvero aprirli.
                $canOpenSheet = \Modules\Wts\Filament\Pages\WtsProcessingSheetPage::canAccess();
                $canOpenReports = \Modules\Wts\Filament\Pages\WtsReportsPage::canAccess();
            @endphp
            @if ($canOpenSheet || $canOpenReports)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Passi successivi</h3>
                    <div class="mt-4 flex flex-col gap-3">
                        @if ($canOpenSheet)
                            <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingSheetPage::getUrl(panel: 'wts') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Apri foglio lavorazione
                            </a>
                        @endif
                        @if ($canOpenReports)
                            <a href="{{ \Modules\Wts\Filament\Pages\WtsReportsPage::getUrl(panel: 'wts') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Apri report finale
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</x-filament-panels::page>
