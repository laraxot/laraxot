<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-03 - Acquisizione bolletta e contratto</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Questa schermata funziona davvero: carica un PDF, lo invia a Google Document AI, costruisce una proposta strutturata e ti lascia la conferma finale.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Esempi pronti per la demo</h3>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Con Google Document AI configurato, anche questi PDF sintetici vengono analizzati davvero. Senza configurazione viene mostrato un fallback dichiarato.
                </p>
                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    <button type="button" wire:click="loadDemoBill" class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-left shadow-sm transition hover:border-blue-300 hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10">
                        <span class="block text-sm font-semibold text-blue-900 dark:text-blue-100">Usa bolletta luce/gas demo</span>
                        <span class="mt-1 block text-xs text-blue-800 dark:text-blue-200">POD, PDR, consumi, potenza, fornitore attuale e totale.</span>
                    </button>
                    <button type="button" wire:click="loadDemoContract" class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-left shadow-sm transition hover:border-slate-300 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                        <span class="block text-sm font-semibold text-slate-950 dark:text-white">Usa contratto fornitura demo</span>
                        <span class="mt-1 block text-xs text-slate-600 dark:text-slate-300">Offerta, durata, condizioni economiche, firma e dati fornitura.</span>
                    </button>
                </div>
            </div>

            <form wire:submit="extractDemoDocument" class="rounded-2xl border border-dashed border-blue-300 bg-blue-50 p-6 shadow-sm dark:border-blue-500/40 dark:bg-blue-500/10">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white text-2xl font-semibold text-blue-700 shadow-sm">PDF</div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Carica bolletta o contratto PDF</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            Il documento viene letto con OCR/analisi documentale e trasformato in una scheda pratica energia da verificare.
                        </p>

                        <div class="mt-5 grid gap-4 md:grid-cols-[240px_minmax(0,1fr)]">
                            <div>
                                <label for="documentType" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo documento</label>
                                <select id="documentType" wire:model="documentType" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-950 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="utility_bill">Bolletta luce/gas</option>
                                    <option value="supply_contract">Contratto fornitura</option>
                                </select>
                            </div>
                            <div>
                                <label for="documentFile" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">File PDF</label>
                                <input id="documentFile" wire:model="documentFile" type="file" accept="application/pdf,.pdf" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-950 shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-slate-950 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white focus:border-blue-500 focus:ring-blue-500" />
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
                            <button type="submit" wire:loading.attr="disabled" wire:target="documentFile,extractDemoDocument" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-500 disabled:opacity-60">
                                <span wire:loading.remove wire:target="extractDemoDocument">Acquisisci con Google Document AI</span>
                                <span wire:loading wire:target="extractDemoDocument">Acquisizione in corso...</span>
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

            @php
                $proposal = $extractedDocument ?? [
                    'type_label' => 'Esempio prima del caricamento',
                    'source_file' => 'nessun-file.pdf',
                    'confidence' => 'Demo',
                    'mode_label' => 'In attesa',
                    'fields' => [
                        ['label' => 'Cliente', 'value' => 'Demo Broker Energia Srl'],
                        ['label' => 'POD', 'value' => 'IT001E98765432'],
                        ['label' => 'PDR', 'value' => '12345678901234'],
                        ['label' => 'Consumi e potenza', 'value' => '18.400 kWh, 2.750 Smc, 15 kW'],
                    ],
                    'uncertain_fields' => ['Carica un PDF per vedere i campi incerti.'],
                    'missing_fields' => ['Carica un PDF per vedere i campi mancanti.'],
                    'line_items' => ['La proposta verra sostituita dal risultato LLM.'],
                    'recommended_action' => 'Carica una bolletta o un contratto.',
                    'raw_excerpt' => 'Questa e la proposta statica prima dell acquisizione.',
                ];
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Proposta dati da confermare</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{{ $proposal['type_label'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $proposal['confidence'] }}</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $proposal['mode_label'] }}</span>
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

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Punti riconosciuti</p>
                        <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                            @foreach ($proposal['line_items'] as $item)
                                <div class="rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800">{{ $item }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-950 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                        <p class="text-xs font-semibold uppercase tracking-wide">Campi incerti</p>
                        <div class="mt-3 space-y-2 text-sm">
                            @forelse ($proposal['uncertain_fields'] as $item)
                                <div>{{ $item }}</div>
                            @empty
                                <div>Nessun campo incerto dichiarato.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-950 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-100">
                        <p class="text-xs font-semibold uppercase tracking-wide">Campi mancanti</p>
                        <div class="mt-3 space-y-2 text-sm">
                            @forelse ($proposal['missing_fields'] as $item)
                                <div>{{ $item }}</div>
                            @empty
                                <div>Nessun campo mancante dichiarato.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Azione suggerita</p>
                    <p class="mt-2 text-sm font-semibold text-slate-950 dark:text-white">{{ $proposal['recommended_action'] }}</p>
                    <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $proposal['raw_excerpt'] }}</p>
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    @if ($energyBrokerDocumentId !== null)
                        <button type="button" wire:click="confirmImportedDocument" class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            Conferma proposta
                        </button>
                    @endif
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
                        'POD, PDR, intestatario, indirizzo fornitura e fornitore corrente.',
                        'Consumi kWh/Smc, potenza impegnata, periodo, totale e scadenze.',
                        'Codice offerta, durata, condizioni economiche e metodo pagamento.',
                        'Campi incerti o match dubbi cliente da inviare al backoffice per verifica.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Regola operativa</h3>
                <p class="mt-2 text-sm">
                    Il sistema non salva anagrafiche definitive in autonomia: analizza, propone, evidenzia dubbi e lascia al backoffice la conferma finale.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
