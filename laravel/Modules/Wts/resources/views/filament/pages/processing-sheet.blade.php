<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-wide text-emerald-700 dark:text-emerald-200">DATI REALI WTS</p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-950 dark:text-slate-100">Foglio lavorazione</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                        Elenco reale dei fogli registrati e dettaglio del passaggio selezionato.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[340px_minmax(0,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Fogli disponibili</h3>
                <div class="mt-4 space-y-3">
                    @foreach ($steps as $step)
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsProcessingSheetPage::getUrl(panel: 'wts', parameters: ['step' => $step->id]) }}"
                            class="block rounded-xl border border-slate-200 px-4 py-3 text-sm transition hover:border-blue-300 hover:bg-blue-50 dark:border-slate-700 dark:hover:border-blue-500/40 dark:hover:bg-blue-600/10">
                            <p class="font-semibold text-slate-950 dark:text-slate-100">{{ $step->work_sheet_code }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $step->step_type_code }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                @if ($selectedStep !== null)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-4 dark:border-slate-800 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $selectedStep->work_sheet_code }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $selectedStep->step_type_code }} • {{ optional($selectedStep->executed_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <button type="button" wire:click="downloadSheetPdf"
                                class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                                Scarica PDF foglio lavorazione
                            </button>
                        </div>
                        <div class="space-y-4 px-6 py-5">
                            @foreach ($selectedStep->selectedUnits as $unit)
                                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $unit->unit_code }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $unit->inboundLot?->lot_code ?? 'N/D' }} • {{ $unit->inboundLot?->inboundRecord?->form_number ?? 'N/D' }} • {{ $unit->inboundLot?->inboundRecord?->technicalSheetSnapshot?->waste_code ?? 'N/D' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
