<x-filament-panels::page>
    <div class="space-y-6">
        <div class="overflow-hidden rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-6 shadow-sm dark:border-amber-500/20 dark:from-slate-900 dark:via-slate-900 dark:to-amber-950/30">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-800 dark:bg-amber-400/15 dark:text-amber-100">
                        Mockup ASD Soci
                    </span>
                    <div class="space-y-2">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Demo del gestionale soci Ramdivision</h2>
                        <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-300">
                            Qui vedi il mockup navigabile del progetto: iscrizione online, delibera, libro soci, quote, eventi e area socio,
                            impostati come pannello semplice da mostrare al cliente.
                        </p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-4">
                        @foreach ([
                            ['label' => 'Ruoli demo', 'value' => '5'],
                            ['label' => 'Schermate mockup', 'value' => '6'],
                            ['label' => 'Focus MVP', 'value' => 'Libro soci'],
                            ['label' => 'Uso mobile', 'value' => 'Alto'],
                        ] as $item)
                            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-amber-100 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/85">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Chi usa il pannello</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            ['role' => 'Segreteria', 'desc' => 'Controlla domande, delibere, libro soci, ricevute e assistenza agli utenti.'],
                            ['role' => 'Tesoreria', 'desc' => 'Registra quote, movimenti economici di base, sponsorizzazioni e uscite.'],
                            ['role' => 'Socio', 'desc' => 'Si iscrive, rinnova, aggiorna dati ammessi e aderisce agli eventi da telefono.'],
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
                            Ogni riquadro apre una schermata mockup con esempi di campi, tabelle, badge di stato e azioni.
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Nota: <span class="font-semibold">i dati sono demo</span>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociEnrollmentPage::getUrl(panel: 'asd-soci'), 'title' => '1. Iscrizione e rinnovo', 'description' => 'Landing e form mobile-first per nuova iscrizione, rinnovo e verifica dati.'],
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociRegisterPage::getUrl(panel: 'asd-soci'), 'title' => '2. Libro soci e delibere', 'description' => 'Backoffice con coda domande, progressivo, delibera e registro soci attivi o cessati.'],
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociAccountingPage::getUrl(panel: 'asd-soci'), 'title' => '3. Tesoreria base', 'description' => 'Quote, ricevute, sponsorizzazioni, versamenti liberali, merchandising e uscite principali.'],
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociEventsPage::getUrl(panel: 'asd-soci'), 'title' => '4. Eventi e adesioni', 'description' => 'Calendario eventi, lista partecipanti, quote evento e check-in organizzativo.'],
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociMemberAreaPage::getUrl(panel: 'asd-soci'), 'title' => '5. Area socio', 'description' => 'Vista personale con stato quota, ricevute, dati profilo ed eventi prenotati.'],
                        ['url' => \Modules\AsdSoci\Filament\Pages\AsdSociDocumentationPage::getUrl(panel: 'asd-soci'), 'title' => '6. Documentazione collegata', 'description' => 'Le specifiche Markdown del progetto direttamente dentro al mockup.'],
                    ] as $page)
                        <a href="{{ $page['url'] }}"
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-amber-300 hover:bg-amber-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-amber-500/40 dark:hover:bg-amber-500/10">
                            <p class="text-base font-semibold text-slate-950 dark:text-white">{{ $page['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Stato mockup</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <div class="rounded-xl bg-emerald-50 px-4 py-3 text-emerald-950 dark:bg-emerald-500/10 dark:text-emerald-100">Incluso: iscrizione, rinnovo, libro soci, eventi, entrate e uscite base</div>
                        <div class="rounded-xl bg-amber-50 px-4 py-3 text-amber-950 dark:bg-amber-500/10 dark:text-amber-100">Da validare: livello reale di integrazione con Fatture in Cloud</div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">Fuori MVP: contabilita avanzata, app nativa, integrazioni federali complete</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Regole da ricordare</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Il libro soci e il centro del dominio applicativo.',
                            'Nessun socio attivo senza domanda e delibera registrate.',
                            'La UX mobile deve funzionare bene per iscrizione, rinnovo ed eventi.',
                            'Entrate e uscite sono operative, non sostituiscono la contabilita professionale.',
                            'Le esportazioni devono restare semplici da riutilizzare fuori dal sistema.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
