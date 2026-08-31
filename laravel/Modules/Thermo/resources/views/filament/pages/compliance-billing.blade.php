<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-06 - DiCo, foto finali, fatture e pagamenti</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Chiusura intervento con album fine lavori, documentazione tecnica, prefattura e integrazione amministrativa.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['title' => 'Generatore DiCo', 'desc' => 'Documento da dati sopralluogo, preventivo, impianto e tecnico.'],
                        ['title' => 'Album fine lavori', 'desc' => 'Foto finali collegate a cliente, impianto e intervento.'],
                        ['title' => 'Pre-fattura', 'desc' => 'Righe da preventivo piu consuntivo materiali extra.'],
                        ['title' => 'Fatture in Cloud', 'desc' => 'Integrazione opzionale per generazione documento.'],
                    ] as $card)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-slate-700">
                            <p class="text-base font-semibold text-slate-950 dark:text-white">{{ $card['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $card['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Timeline chiusura</h3>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        'SAL completato al 100%',
                        'Foto finali caricate e firmate dal tecnico',
                        'DiCo generata e revisionata dall ufficio',
                        'Fattura o ricevuta acconto proposta al responsabile',
                    ] as $index => $step)
                        <div class="flex gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm dark:bg-slate-800">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-semibold text-orange-800">{{ $index + 1 }}</span>
                            <span class="text-slate-700 dark:text-slate-200">{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Stato documenti</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        ['label' => 'DiCo', 'value' => 'Bozza pronta'],
                        ['label' => 'Foto finali', 'value' => '12 caricate'],
                        ['label' => 'Prefattura', 'value' => 'Da approvare'],
                        ['label' => 'Pagamento', 'value' => 'Acconto 30% registrato'],
                    ] as $row)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $row['label'] }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $row['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Da validare</h3>
                <p class="mt-2 text-sm">
                    Valore legale di firma remota, formato DiCo, invii WhatsApp/SMS e integrazione contabile richiedono conferma tecnica e contrattuale.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
