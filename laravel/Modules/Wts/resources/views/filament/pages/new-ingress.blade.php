<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,2.4fr)_minmax(320px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">UI-02 - Presa in carico, pesa e accettazione</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            L'operatore controlla il record ERP selezionato, registra i dati di pesa e conferma che il materiale del camion arrivato puo entrare nel flusso WTS.
                        </p>
                    </div>
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Apri record ERP
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Dati letti dal vecchio ERP</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Riferimento ERP legacy
                        <input type="text" wire:model="legacyReference" disabled
                            class="h-11 rounded-xl border border-slate-300 bg-slate-50 px-4 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Numero formulario
                        <input type="text" wire:model="formNumber"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Data ingresso
                        <input type="date" wire:model="receivedDate"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Conferitore
                        <input type="text" wire:model="sourceCompanyName"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Codice rifiuto / CER
                        <input type="text" wire:model="wasteCode"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Descrizione materiale
                        <input type="text" wire:model="materialDescription"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Pesa e accettazione impianto</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Quantita iniziale dichiarata
                        <input type="text" wire:model="grossQuantity"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                        <span class="text-xs font-normal text-slate-400 dark:text-slate-500">Quantita programmata in ECOS (codprgd). Se ECOS non la valorizza, viene proposto il peso pesato.</span>
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Peso in ingresso rilevato
                        <input type="text" wire:model="entryWeight"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                        <span class="text-xs font-normal text-slate-400 dark:text-slate-500">Peso reale pesato in impianto. E il valore che il lotto eredita per la giacenza (RB-42).</span>
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Esito accettazione
                        <select wire:model="acceptanceStatus"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option value="">Seleziona esito</option>
                            <option value="accepted">Accettato</option>
                            <option value="accepted_with_note">Accettato con nota</option>
                            <option value="to_verify">Da verificare</option>
                        </select>
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Operatore pesa
                        <input type="text" value="Mario Rossi"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        Baia di scarico
                        <select
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                            <option>Seleziona baia</option>
                            <option selected>Baia 2</option>
                            <option>Baia 3</option>
                        </select>
                    </label>
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 md:col-span-2 xl:col-span-1">
                        Note accettazione
                        <input type="text" wire:model="notes"
                            class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                    </label>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Stato pratica</h3>
                <div class="mt-4 space-y-3">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Record ERP</p>
                        <p class="mt-1 text-sm font-semibold text-slate-950 dark:text-slate-100">Agganciato correttamente</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Pesa</p>
                        <p class="mt-1 text-sm font-semibold text-slate-950 dark:text-slate-100">Compilata</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-500/10">
                        <p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-200">Esito</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-900 dark:text-emerald-100">Pronto a creare il lotto operativo</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Azioni</h3>
                <div class="mt-4 flex flex-col gap-3">
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Salva bozza
                    </button>
                    <button type="button" wire:click="confirmTakingCharge"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                        Conferma presa in carico e vai al lotto
                    </button>
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                        Segnala anomalia ingresso
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
