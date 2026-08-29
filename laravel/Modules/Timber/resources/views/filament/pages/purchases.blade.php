<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,1fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Nuovo acquisto legname</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Fornitore
                    <select wire:model.live="supplierId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Seleziona fornitore</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((int) $supplierId === (int) $supplier->id)>{{ $supplier->legal_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Trasportatore
                    <select wire:model.live="transporterId" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="">Nessuno</option>
                        @foreach ($transporters as $transporter)
                            <option value="{{ $transporter->id }}" @selected((int) $transporterId === (int) $transporter->id)>{{ $transporter->legal_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Provenienza / bosco
                    <input type="text" wire:model.blur="originForest" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Numero DDT
                    <input type="text" wire:model.blur="ddtNumber" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Data DDT
                    <input type="date" wire:model.blur="ddtDate" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Specie legnosa
                    <select wire:model.live="woodSpecies" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @foreach ($woodSpeciesOptions as $code => $label)
                            <option value="{{ $code }}" @selected((string) $code === (string) $woodSpecies)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Quantita (in quintali)
                    <input type="text" wire:model.blur="quantity" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    <span class="text-xs font-normal text-slate-400">Quantita di materia prima nell'unita selezionata a fianco.</span>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Unita di misura
                    <select wire:model.live="unitOfMeasure" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @foreach ($unitsOfMeasure as $code => $label)
                            <option value="{{ $code }}" @selected((string) $code === (string) $unitOfMeasure)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Peso lordo (kg)
                    <input type="text" wire:model.blur="grossWeight" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Peso netto (kg)
                    <input type="text" wire:model.blur="netWeight" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Prezzo unitario di acquisto
                    <input type="text" wire:model.blur="unitPurchasePrice" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Unita del prezzo
                    <select wire:model.live="purchasePriceUnit" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @foreach ($purchasePriceUnits as $code => $label)
                            <option value="{{ $code }}" @selected((string) $code === (string) $purchasePriceUnit)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    Certificazioni
                    <input type="text" wire:model.blur="certifications" placeholder="FSC, PEFC" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:col-span-2">
                    Foto
                    <input type="file" wire:model="photo" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:col-span-2">
                    Note
                    <input type="text" wire:model.blur="notes" class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
            </div>
            <div class="mt-6">
                <button type="button" wire:click="registerPurchase"
                    class="inline-flex items-center justify-center rounded-xl bg-amber-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600">
                    Registra acquisto e crea lotto
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Ultimi acquisti</h3>
            <div class="mt-4 space-y-3">
                @forelse ($purchaseRecords as $record)
                    <a href="{{ \App\Filament\Timber\Pages\TimberLotDetailPage::getUrl(panel: 'timber', parameters: ['lot' => $record->lot?->id]) }}"
                        class="block rounded-xl bg-slate-50 p-4 transition hover:bg-amber-50 dark:bg-slate-800">
                        <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ $record->lot?->lot_code ?? 'N/D' }} - {{ $record->wood_species_name }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $record->supplier?->legal_name }} - DDT {{ $record->ddt_number }}</p>
                    </a>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">Nessun acquisto registrato.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
