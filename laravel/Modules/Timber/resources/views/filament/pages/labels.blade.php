<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.8fr)_minmax(260px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Lotti</h3>
                <div class="mt-3 space-y-2">
                    @foreach ($lots as $lot)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 text-sm dark:bg-slate-800">
                            <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $lot->lot_code }}</span>
                            <button type="button" wire:click="downloadLotLabel({{ $lot->id }})"
                                class="rounded-lg border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-white dark:border-slate-700 dark:text-slate-200">
                                Scarica etichetta
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Materiali derivati</h3>
                <div class="mt-3 space-y-2">
                    @foreach ($materials as $material)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 text-sm dark:bg-slate-800">
                            <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $material->output_code }}</span>
                            <button type="button" wire:click="downloadMaterialLabel({{ $material->id }})"
                                class="rounded-lg border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-white dark:border-slate-700 dark:text-slate-200">
                                Scarica etichetta
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Confezioni</h3>
                <div class="mt-3 space-y-2">
                    @foreach ($packagedUnits as $unit)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 text-sm dark:bg-slate-800">
                            <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $unit->package_code }}</span>
                            <button type="button" wire:click="downloadPackagedUnitLabel({{ $unit->id }})"
                                class="rounded-lg border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-white dark:border-slate-700 dark:text-slate-200">
                                Scarica etichetta
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Anteprima QR</h3>
            <img src="{{ $qrPreview }}" alt="QR demo" class="mt-4 w-full max-w-[220px]" />
        </div>
    </div>
</x-filament-panels::page>
