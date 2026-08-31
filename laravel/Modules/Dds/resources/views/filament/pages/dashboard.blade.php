<x-filament-panels::page>
    <div class="space-y-6">
        <div class="overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-6 shadow-sm dark:border-emerald-500/20 dark:from-slate-900 dark:via-slate-900 dark:to-emerald-950/40">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800 dark:bg-emerald-400/15 dark:text-emerald-100">
                        DDS mockup
                    </span>
                    <div class="space-y-2">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Digitalizzazione disinfestazioni, dal sopralluogo al RENTRI</h2>
                        <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-300">
                            Questo pannello mockup raccoglie MVP e full suite per aziende di disinfestazione: CRM, planning, app tecnico, portale clienti, presenze,
                            fatturazione, magazzino, formulari e automazioni AI.
                        </p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ([
                            ['label' => 'Moduli MVP', 'value' => '4'],
                            ['label' => 'Portali per ruolo', 'value' => '3'],
                            ['label' => 'Moduli full suite', 'value' => '4'],
                        ] as $item)
                            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-emerald-100 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/85">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Ruoli e utenti dedicati</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            ['role' => 'dds_admin', 'desc' => 'Back office, commerciale, amministrazione e setup completo del cliente DDS.'],
                            ['role' => 'dds_technician', 'desc' => 'Tecnici sul campo con accesso a planning, rapportini, POS, firme e timbrature.'],
                            ['role' => 'dds_customer', 'desc' => 'Clienti finali con consultazione documenti, contratti, storico e ticketing leggero.'],
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
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Mappa pannello DDS</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Ogni pagina rappresenta un blocco funzionale coerente con il perimetro richiesto e con i portali dedicati per ufficio, tecnico e cliente.
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Documentazione: <span class="font-semibold">docs/dds/01-mockup-dds.md</span>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['url' => \Modules\Dds\Filament\Pages\DdsCrmPage::getUrl(panel: 'dds'), 'title' => 'UI-01 - CRM, importazione e anagrafiche', 'description' => 'Import CSV/Excel, schema campi stile Mailchimp, clienti, sedi, contatti e pipeline commerciale.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsSurveysContractsPage::getUrl(panel: 'dds'), 'title' => 'UI-02 - Sopralluoghi, offerte e contratti', 'description' => 'Rilievo tecnico con allegati, preventivi, contratti, ordini di lavoro e firme digitali.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsPlanningPage::getUrl(panel: 'dds'), 'title' => 'UI-03 - Planning interventi e calendario', 'description' => 'Vista stile Google Calendar con slot, ricorrenze, squadre, cadenze e urgenze.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsFieldAppPage::getUrl(panel: 'dds'), 'title' => 'UI-04 - App tecnico e operativita sul campo', 'description' => 'Mobile portal per rapportini, POS, firme, prodotti usati, foto/video e timbrature.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsCustomerPortalPage::getUrl(panel: 'dds'), 'title' => 'UI-05 - Portale clienti e documenti', 'description' => 'Area riservata web con contratti, report, conformita, planimetrie e ticket.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsAdministrationPage::getUrl(panel: 'dds'), 'title' => 'UI-06 - Amministrazione, fatture e presenze', 'description' => 'Fatture In Cloud, orari, foglio ore, export formulario Excel e cassa interventi.'],
                        ['url' => \Modules\Dds\Filament\Pages\DdsAdvancedSuitePage::getUrl(panel: 'dds'), 'title' => 'UI-07 - Full suite: magazzino, RENTRI, AI e multi-tenant', 'description' => 'Lotti, barcode, trappole, mappe, AI, KPI predittivi e gestione multi-azienda per rivendita.'],
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
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Comandi rapidi utenti</h3>
                    <div class="mt-4 space-y-3 text-xs text-slate-600 dark:text-slate-300">
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-dds-user dds.office@test.local "DDS Office" --password=secret --role=dds_admin
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-dds-user tech.dds@test.local "DDS Tech" --password=secret --role=dds_technician
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-dds-user cliente.dds@test.local "DDS Cliente" --password=secret --role=dds_customer
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Copertura funzionale</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'MVP: CRM, sopralluoghi, offerte, interventi, rapportini, magazzino base, portali dedicati.',
                            'Finanza e tempi: POS, avviso fatturazione, Fatture in Cloud, timbrature e foglio ore mensile.',
                            'Full suite: logistica avanzata, RENTRI Aruba, trappole su planimetria, AI e multi-tenancy.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
