<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            @foreach ([
                ['label' => 'Ingressi oggi', 'value' => $stats['ingresses_today'] ?? 0],
                ['label' => 'Colli da lavorare', 'value' => $stats['units_to_process'] ?? 0],
                ['label' => 'Lavorazioni oggi', 'value' => $stats['processings_today'] ?? 0],
            ] as $stat)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-500/30 dark:bg-emerald-500/10">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-200">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-3xl font-semibold text-emerald-950 dark:text-emerald-100">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,1fr)]">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-700 dark:text-teal-300">Mockup WTS</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950 dark:text-slate-100">Flusso operativo da programmazione a WTS1</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                        Demo ispirata alle schermate ricevute: conferimenti programmati, chiusura peso, generazione QCODE, ristampa da UI-01 e passaggio alle lavorazioni separate.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsIngressesPage::getUrl(panel: 'wts') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                            Apri viaggi in ingresso
                        </a>
                        <a href="{{ \Modules\Wts\Filament\Pages\WtsPressingPage::getUrl(panel: 'wts') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-800 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800">
                            Vai a pressatura
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800">
                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">Viaggio demo del giorno</p>
                    <div class="mt-4 grid gap-3 text-sm">
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white px-4 py-3 dark:bg-slate-950">
                            <span class="text-slate-500 dark:text-slate-400">Formulario</span>
                            <span class="font-semibold text-slate-950 dark:text-slate-100">FIR-2026-001</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white px-4 py-3 dark:bg-slate-950">
                            <span class="text-slate-500 dark:text-slate-400">Stato</span>
                            <span class="font-semibold text-emerald-700 dark:text-emerald-200">Peso chiuso</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-white px-4 py-3 dark:bg-slate-950">
                            <span class="text-slate-500 dark:text-slate-400">Colli previsti</span>
                            <span class="font-semibold text-slate-950 dark:text-slate-100">10 big bag</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            @foreach ([
                ['step' => '01', 'title' => 'Programmazione', 'body' => 'Filtro giorno, FIR, targa e produttore come nella lista conferimenti.'],
                ['step' => '02', 'title' => 'Accettazione e pesa', 'body' => 'Il peso viene chiuso e il rifiuto entra nel database WTS.'],
                ['step' => '03', 'title' => 'QCODE e colli', 'body' => 'Ogni collo riceve partita, sotto-numero e QR grande.'],
                ['step' => '04', 'title' => 'WTS1 e lavorazioni', 'body' => 'Lavorazioni separate per scansione, pesi, colli e invio.'],
            ] as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-950 text-sm font-semibold text-white dark:bg-slate-100 dark:text-slate-950">{{ $item['step'] }}</span>
                    <p class="mt-4 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $item['title'] }}</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $item['body'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Mappa completa pannello e documentazione</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ([
                    ['url' => \Modules\Wts\Filament\Pages\WtsIngressesPage::getUrl(panel: 'wts'), 'title' => 'UI-01 - Lista materiale in entrata da ERP', 'description' => 'Viaggi in programma, arrivi registrati, peso chiuso e ristampa etichette.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsNewIngressPage::getUrl(panel: 'wts'), 'title' => 'UI-02 - Presa in carico, pesa e accettazione', 'description' => 'Schermata mantenuta come supporto al passaggio accettazione e pesa.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsLotDetailPage::getUrl(panel: 'wts'), 'title' => 'UI-03 - Dettaglio lotto di ingresso', 'description' => 'Gestione lotto, colli e stampa diretta dal lotto.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsWorkQueuePage::getUrl(panel: 'wts'), 'title' => 'UI-05 - Giacenza operativa WTS', 'description' => 'Vista giacenza con ST, CER/EER e descrizione scheda in evidenza.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsSortingPage::getUrl(panel: 'wts'), 'title' => 'Selezione e cernita', 'description' => 'Scansiona QR, seleziona colli, registra pesi e output.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsUnpackagingPage::getUrl(panel: 'wts'), 'title' => 'Sconfezionamento', 'description' => 'Gestisce fusti, bancali e rifiuto lavorato rimanente.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsGrindingWashingPage::getUrl(panel: 'wts'), 'title' => 'Triturazione e lavaggio', 'description' => 'Lavaggio on/off, ferro deferizzato e liquido generato.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsPressingPage::getUrl(panel: 'wts'), 'title' => 'Pressatura', 'description' => 'Più partite in ingresso e balle prodotte.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsProcessingSheetPage::getUrl(panel: 'wts'), 'title' => 'Foglio lavorazione', 'description' => 'Stampa giornaliera delle lavorazioni e tracciabilità.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsOutputsPage::getUrl(panel: 'wts'), 'title' => 'UI-08 - Generazione materiali derivati', 'description' => 'Registra e stampa le etichette dei materiali prodotti.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsReportsPage::getUrl(panel: 'wts'), 'title' => 'UI-11 - Report lavorazioni', 'description' => 'Distinta completa di lavorazioni, ubicazioni e materiali.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsAuditPage::getUrl(panel: 'wts'), 'title' => 'UI-10 - Ricerca audit e tracciabilita', 'description' => 'Cerca formulario, lotto, collo o materiale finito.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsInventoryPage::getUrl(panel: 'wts'), 'title' => 'UI-12 - Stampa giacenza WTS', 'description' => 'Giacenza stampabile con note tecniche.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsOperationalParametersPage::getUrl(panel: 'wts'), 'title' => 'Parametri operativi WTS', 'description' => 'Pesi standard, email non conformita e valori operativi modificabili.'],
                    ['url' => \Modules\Wts\Filament\Pages\WtsDocumentationPage::getUrl(panel: 'wts'), 'title' => 'Documentazione WTS', 'description' => 'Pacchetto Markdown di specifiche e accettazione.'],
                ] as $page)
                    <a href="{{ $page['url'] }}"
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500/40 dark:hover:bg-blue-600/10">
                        <p class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $page['title'] }}</p>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</x-filament-panels::page>
