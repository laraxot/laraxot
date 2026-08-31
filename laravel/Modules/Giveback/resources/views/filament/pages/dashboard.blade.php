<x-filament-panels::page>
    <div class="space-y-6">
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-6 shadow-sm dark:border-emerald-500/20 dark:from-slate-900 dark:via-slate-900 dark:to-emerald-950/40">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-100">
                        GiveBack Platform mockup
                    </span>
                    <div class="space-y-2">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Demo del portale GiveBack, passo dopo passo</h2>
                        <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-300">
                            Qui vedi come funzionerebbe il sito da costruire: il cliente prova la fortuna con 1 euro, il widget lavora in
                            pre-acquisto con split payment e il premio resta gestito dalla piattaforma.
                        </p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-4">
                        @foreach ([
                            ['label' => 'Negozi prova', 'value' => '3-5'],
                            ['label' => 'Tempo stimato', 'value' => '5 mesi'],
                            ['label' => 'Importo fisso', 'value' => '1 euro'],
                            ['label' => 'Schermate demo', 'value' => '9'],
                        ] as $item)
                            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-emerald-100 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/85">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Chi usa il portale</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            ['role' => 'Gestore GiveBack', 'desc' => 'Controlla premio piattaforma, documenti, split payment e problemi operativi.'],
                            ['role' => 'Negoziante', 'desc' => 'Prepara sconto, widget e campagna, senza gestire il premio finale.'],
                            ['role' => 'Cliente finale', 'desc' => 'Con 1 euro tenta la fortuna e riceve una promo immediata.'],
                        ] as $role)
                            <div class="rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/80">
                                <p class="font-semibold text-slate-950 dark:text-white">{{ $role['role'] }}</p>
                                <p class="mt-1 text-xs leading-5">{{ $role['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Cosa puoi aprire nella demo</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Ogni riquadro apre una schermata con esempi di campi, pulsanti e passaggi.
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Nota: <span class="font-semibold">tutti i pulsanti sono finti</span>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackValueFlowPage::getUrl(panel: 'giveback-platform'), 'title' => '1. Prova cliente', 'description' => 'Simula il cliente che vede Oggi sono fortunato, paga 1 euro e tenta la fortuna.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackWidgetEmbedPage::getUrl(panel: 'giveback-platform'), 'title' => '2. Box sul sito', 'description' => 'Vedi il widget in pre-acquisto con split payment e premio piattaforma.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackMerchantOnboardingPage::getUrl(panel: 'giveback-platform'), 'title' => '3. Preparazione negozio', 'description' => 'Form per inserire negozio, sconto, codici promo e setup widget.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackMerchantDashboardPage::getUrl(panel: 'giveback-platform'), 'title' => '4. Scrivania negozio', 'description' => 'Area semplice dove il negozio vede performance, promo e avvisi di campagna.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackAdminPayoutsPage::getUrl(panel: 'giveback-platform'), 'title' => '5. Controlli interni', 'description' => 'Split payment e riconciliazione, con merchant donation solo come ipotesi futura.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackLotteryCompliancePage::getUrl(panel: 'giveback-platform'), 'title' => '6. Premio del mese', 'description' => 'Premio piattaforma, compliance e promemoria sulla fideiussione.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackRoadmapPage::getUrl(panel: 'giveback-platform'), 'title' => '7. Cosa si fa ora e dopo', 'description' => 'Versione iniziale, servizi da quotare a parte ed evoluzioni future.'],
                        ['url' => \Modules\Giveback\Filament\Pages\GivebackDocumentationPage::getUrl(panel: 'giveback-platform'), 'title' => '8. Appunti collegati', 'description' => 'Specifiche, preventivo, bandi e nota accessi dentro al pannello.'],
                    ] as $page)
                        <a href="{{ $page['url'] }}"
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-emerald-500/40 dark:hover:bg-emerald-500/10">
                            <p class="text-base font-semibold text-slate-950 dark:text-white">{{ $page['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Stato demo</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-950 dark:bg-emerald-500/10 dark:text-emerald-100">Widget: pre-acquisto con split payment</div>
                        <div class="rounded-xl bg-amber-50 px-4 py-3 text-amber-950 dark:bg-amber-500/10 dark:text-amber-100">Premio: parte legale da preparare</div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">Merchant donation: da valutare in un secondo tempo</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Regole da ricordare</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Parlare semplice: Oggi sono fortunato, versi 1 euro e tenti la fortuna.',
                            'Niente punti e niente buona causa nella narrativa MVP.',
                            'Il premio e della piattaforma, non del merchant.',
                            'Split payment: un pagamento solo, poi divisione automatica degli incassi.',
                            'CRM, corso e campagne vanno quotati a parte dal prodotto GiveBack.',
                            'La demo mostra il percorso, non salva dati veri.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
