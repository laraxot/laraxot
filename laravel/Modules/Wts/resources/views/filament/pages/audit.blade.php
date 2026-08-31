<x-filament-panels::page>
    @php
        $record = $searchResult['record'] ?? null;
        $lot = $searchResult['lot'] ?? null;
        $unit = $searchResult['unit'] ?? null;
        $material = $searchResult['derivedMaterial'] ?? null;
        $steps = $searchResult['processingSteps'] ?? collect();
    @endphp

    <div class="grid gap-6 xl:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-black uppercase tracking-wide text-emerald-700 dark:text-emerald-200">DATI REALI WTS</p>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-10 - Ricerca audit e tracciabilita</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Ricerca reale per formulario, lotto, collo o materiale derivato con catena ingresso -> lotto -> colli -> lavorazioni -> output.
                        </p>
                    </div>
                </div>

                <form method="get" action="{{ \Modules\Wts\Filament\Pages\WtsAuditPage::getUrl(panel: 'wts') }}" class="mt-6 grid gap-4 xl:grid-cols-[1fr_auto]">
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Cerca formulario, lotto, collo o materiale finito
                        <input name="search" type="text" value="{{ $this->search }}"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center self-end rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                        Cerca
                    </button>
                </form>
            </div>

            @if ($searchResult !== null)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Risultato audit</h3>
                    <div class="mt-4 grid gap-4 md:grid-cols-2">
                        @foreach ([
                            ['label' => 'Formulario', 'value' => $record?->form_number ?? 'N/D'],
                            ['label' => 'Lotto', 'value' => $lot?->lot_code ?? 'N/D'],
                            ['label' => 'Collo', 'value' => $unit?->unit_code ?? 'N/D'],
                            ['label' => 'Materiale derivato', 'value' => $material?->output_code ?? 'N/D'],
                        ] as $item)
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Catena di lavorazione</h3>
                    </div>
                    <div class="space-y-4 px-6 py-5">
                        @foreach ($steps as $step)
                            <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                                <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $step->work_sheet_code }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $step->step_type_code }} • {{ optional($step->executed_at)->format('d/m/Y H:i') }}</p>
                                @if ($step->derivedMaterials->isNotEmpty())
                                    <div class="mt-2 space-y-1">
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Output:</p>
                                        @foreach ($step->derivedMaterials as $dm)
                                            @php
                                                $typeLabels = [
                                                    'drum' => 'Fusto',
                                                    'dirty_drum' => 'Fusto sporco',
                                                    'pallet' => 'Bancale',
                                                    'deferred_iron' => 'Ferro deferizzato',
                                                    'generated_liquid' => 'Liquido generato',
                                                ];
                                            @endphp
                                            <p class="text-xs text-slate-600 dark:text-slate-300">
                                                • {{ $typeLabels[$dm->output_type_code] ?? $dm->output_type_code }} — {{ $dm->output_code }}
                                            </p>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Nessun output</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Navigazione</h3>
                <div class="mt-4 flex flex-col gap-3">
                    @if ($material !== null)
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsFinishedMaterialPage::getUrl(panel: 'wts', parameters: ['material' => $material->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                            Apri materiale finito
                        </a>
                    @endif
                    @if ($unit !== null)
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsMaterialDetailPage::getUrl(panel: 'wts', parameters: ['unit' => $unit->id]) }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            Apri scheda collo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
