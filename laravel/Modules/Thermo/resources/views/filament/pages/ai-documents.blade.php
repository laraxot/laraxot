<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-08 - AI, fatture, ordini e documenti PDF</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Punto di ingresso per caricare fatture ricevute, ordini fornitore, DDT, libretti, certificati e altri PDF: l AI propone dati strutturati da confermare.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Esempi pronti per la demo</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Se non hai un PDF reale a portata di mano, usa subito uno di questi esempi per mostrare l estrattore dati.
                </p>
                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    <button type="button" wire:click="loadDemoInvoice" class="rounded-2xl border border-orange-200 bg-orange-50 px-5 py-4 text-left shadow-sm transition hover:border-orange-300 hover:bg-orange-100 dark:border-orange-500/30 dark:bg-orange-500/10">
                        <span class="block text-sm font-semibold text-orange-900 dark:text-orange-100">Usa fattura demo</span>
                        <span class="mt-1 block text-xs text-orange-800 dark:text-orange-200">Grossista, numero fattura, data, totale e righe materiali.</span>
                    </button>
                    <button type="button" wire:click="loadDemoOrder" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-left shadow-sm transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                        <span class="block text-sm font-semibold text-slate-950 dark:text-white">Usa ordine fornitore demo</span>
                        <span class="mt-1 block text-xs text-slate-600 dark:text-slate-300">Ordine caldaia, consegna, distinta materiali e collegamento preventivo.</span>
                    </button>
                </div>
            </div>

            <form wire:submit="extractDemoDocument" class="rounded-2xl border border-dashed border-orange-300 bg-orange-50 p-6 shadow-sm dark:border-orange-500/40 dark:bg-orange-500/10">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-2xl font-semibold text-orange-700 shadow-sm">PDF</div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Carica contratto o fattura PDF</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Seleziona un PDF reale della demo: il sistema prova a leggerlo e mostra una proposta AI interna, senza usare siti esterni.
                        </p>

                        <div class="mt-5 grid gap-4 md:grid-cols-[220px_minmax(0,1fr)]">
                            <div>
                                <label for="documentType" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo documento</label>
                                <select id="documentType" wire:model="documentType" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-950 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    <option value="invoice">Fattura ricevuta</option>
                                    <option value="contract">Contratto</option>
                                    <option value="order">Ordine fornitore</option>
                                </select>
                            </div>
                            <div>
                                <label for="documentFile" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">File PDF</label>
                                <input id="documentFile" wire:model="documentFile" type="file" accept="application/pdf,.pdf" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-950 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-slate-950 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white focus:border-orange-500 focus:ring-orange-500" />
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            @error('documentType')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('documentFile')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <button type="submit" wire:loading.attr="disabled" wire:target="documentFile,extractDemoDocument" class="rounded-xl bg-orange-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-500 disabled:opacity-60">
                                <span wire:loading.remove wire:target="extractDemoDocument">Estrai dati demo</span>
                                <span wire:loading wire:target="extractDemoDocument">Estrazione in corso...</span>
                            </button>
                            @if ($extractedDocument !== null)
                                <button type="button" wire:click="resetDemoExtraction" class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                                    Nuovo documento
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Proposta AI da confermare</h3>
                @php
                    $proposal = $extractedDocument ?? [
                        'type_label' => 'Esempio prima del caricamento',
                        'source_file' => 'nessun-file.pdf',
                        'confidence' => 'Demo',
                        'fields' => [
                            ['label' => 'Fornitore', 'value' => 'Grossista Termoidraulica Srl'],
                            ['label' => 'Numero documento', 'value' => 'FT-2026-1842'],
                            ['label' => 'Totale', 'value' => '1.248,70 euro'],
                            ['label' => 'Collegamento suggerito', 'value' => 'Commessa Bianchi - caldaia'],
                        ],
                        'line_items' => ['Carica un PDF per vedere la proposta aggiornata.'],
                        'raw_excerpt' => 'Questa e la proposta statica. Dopo upload PDF viene sostituita dal risultato della demo.',
                    ];
                @endphp

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800">{{ $proposal['type_label'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $proposal['confidence'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $proposal['source_file'] }}</span>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @foreach ($proposal['fields'] as $row)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $row['label'] }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $row['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Righe / punti riconosciuti</p>
                        <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @foreach ($proposal['line_items'] as $item)
                                <div class="rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800">{{ $item }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Testo letto dal PDF</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $proposal['raw_excerpt'] }}</p>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="button" class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        Conferma proposta
                    </button>
                    <button type="button" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                        Modifica prima di salvare
                    </button>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Cosa puo estrarre</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Dati fattura ricevuta: fornitore, numero, data, totale e righe principali.',
                        'Dati ordine fornitore: numero ordine, consegna, materiali e collegamento al preventivo.',
                        'Materiali acquistati da collegare a commessa, intervento o magazzino.',
                        'Libretti, targhette e certificati da archiviare nello storico impianto.',
                        'Proposta di prefattura o controllo margine, sempre con conferma utente.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Regola del mockup</h3>
                <p class="mt-2 text-sm">
                    L AI legge e propone, ma non registra automaticamente dati contabili o tecnici senza approvazione esplicita.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
