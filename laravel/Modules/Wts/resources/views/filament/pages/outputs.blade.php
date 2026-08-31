<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,2.2fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-08 - Generazione materiali derivati</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            Ogni output può essere ricollegato nel tempo a una scheda tecnica legacy diversa, come richiesto dal reparto.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($outputs as $output)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Output</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $output->output_code }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Tipo output</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">
                                    @php
                                        $typeLabels = [
                                            'drum' => 'Fusto',
                                            'dirty_drum' => 'Fusto sporco',
                                            'pallet' => 'Bancale',
                                            'deferred_iron' => 'Ferro deferizzato',
                                            'generated_liquid' => 'Liquido generato',
                                        ];
                                    @endphp
                                    {{ $typeLabels[$output->output_type_code] ?? $output->output_type_code }}
                                </p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Quantità</p>
                                <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $output->quantity }}</p>
                            </div>
                            <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                Scheda tecnica legacy associata
                                <select wire:change="updateMaterialTechnicalSheet({{ $output->id }}, $event.target.value)"
                                    class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="">Nessuna</option>
                                    @foreach ($snapshots as $snapshot)
                                        <option value="{{ $snapshot->id }}" @selected($output->technical_sheet_snapshot_id === $snapshot->id)>{{ $snapshot->sheet_code }} - {{ $snapshot->waste_code }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" wire:click="reprintOutputLabel({{ $output->id }})"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Ristampa etichetta output
                            </button>
                            <span
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                Etichette generate: {{ $output->labelPrints->count() }}
                            </span>
                            <span class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 dark:border-slate-700 dark:text-slate-200">
                                Da {{ $output->processingStep?->work_sheet_code ?? 'N/D' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-sm text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        Nessun output registrato.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Riepilogo output</h3>
                <div class="mt-4 space-y-3">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Output prodotti</p>
                        <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ $outputs->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Peso totale output</p>
                        <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ number_format((float) $outputs->sum('weight'), 2, ',', '.') }} kg</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-500/10">
                        <p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-200">Schede tecniche collegate</p>
                        <p class="mt-1 text-xl font-semibold text-emerald-900 dark:text-emerald-100">{{ $outputs->whereNotNull('technical_sheet_snapshot_id')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Azioni finali</h3>
                <div class="mt-4 flex flex-col gap-3">
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Salva output
                    </button>
                    <a href="{{ $outputs->isNotEmpty() ? \Modules\Wts\Filament\Pages\WtsFinishedMaterialPage::getUrl(panel: 'wts', parameters: ['material' => $outputs->first()->id]) : \Modules\Wts\Filament\Pages\WtsFinishedMaterialPage::getUrl(panel: 'wts') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                        Salva output e apri UI-09 - Materiale finito
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
