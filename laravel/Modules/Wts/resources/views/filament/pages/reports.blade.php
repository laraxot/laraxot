<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,2.3fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-11 - Report lavorazioni</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Report operativo finale: fogli lavorazione recuperabili, colli sorgente, output prodotti, parametri applicati e tracciabilita padre-figlio.
                        </p>
                    </div>
                    <button type="button" wire:click="downloadFullReportPdf"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                        Stampa report completo
                    </button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Ingressi registrati', 'value' => $stats['inbound_records']],
                    ['label' => 'Passaggi di lavorazione', 'value' => $stats['processing_steps']],
                    ['label' => 'Materiali derivati', 'value' => $stats['derived_materials']],
                    ['label' => 'Colli ancora disponibili', 'value' => $stats['available_units']],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Passaggi registrati</h3>
                <div class="mt-5 space-y-3">
                    @forelse ($steps as $step)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $step->work_sheet_code }} - {{ config('wts.processing_step_types.' . $step->step_type_code, $step->step_type_code) }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $step->selected_units_count }} colli selezionati
                                        @if ($step->executedBy !== null)
                                            • operatore {{ $step->executedBy->name }}
                                        @endif
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        Uscite: {{ $step->derivedMaterials->count() }} • Rimanenza CER: {{ $step->remainder_waste_code ?? 'N/D' }}
                                    </p>
                                    @if ($step->notes !== null)
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $step->notes }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-2 lg:items-end">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                        {{ optional($step->executed_at)->format('d/m/Y H:i') }}
                                    </span>
                                    <button type="button" wire:click="downloadStepPdf({{ $step->id }})"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                        Stampa foglio
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Nessuna lavorazione registrata.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiali derivati</h3>
                <div class="mt-5 space-y-3">
                    @forelse ($materials as $material)
                        <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $material->output_code }}</p>
                                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $material->output_type_code }} • {{ $material->quantity }} pezzi
                                        @if ($material->technicalSheetSnapshot !== null)
                                            • scheda {{ $material->technicalSheetSnapshot->sheet_code }}
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-100">
                                    {{ $material->container_type_code }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Nessun materiale derivato disponibile.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Stato fase 1</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        Lettura dati di scheda tecnica dal legacy in sola lettura.
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        QR e collarini interni gestiti dal nuovo WTS.
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        Giacenza operativa affidabile per il reparto.
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        Applicativi: selezione/cernita, sconfezionamento, triturazione/lavaggio e pressatura.
                    </div>
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        Stampa giacenza WTS con note tecniche disponibile in UI-12.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
