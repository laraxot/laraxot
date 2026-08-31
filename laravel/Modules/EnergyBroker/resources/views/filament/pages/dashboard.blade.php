<x-filament-panels::page>
    <div class="space-y-6">
        <div class="overflow-hidden rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-6 shadow-sm dark:border-blue-500/20 dark:from-slate-900 dark:via-slate-900 dark:to-blue-950/40">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-800 dark:bg-blue-400/15 dark:text-blue-100">
                        Energy broker mockup
                    </span>
                    <div class="space-y-2">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">CRM/Gestionale per broker contratti luce e gas</h2>
                        <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-300">
                            Demo navigabile per acquisire bollette e contratti con LLM, gestire lead, pratiche, comparazione offerte,
                            backoffice, provvigioni e portale agenti.
                        </p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ([
                            ['label' => 'Schermate mockup', 'value' => '9'],
                            ['label' => 'Demo funzionante', 'value' => 'UI-03'],
                            ['label' => 'Ruoli dedicati', 'value' => '6'],
                        ] as $item)
                            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-blue-100 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/85">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Ruoli dedicati</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            ['role' => 'energy_broker_admin', 'desc' => 'Accesso completo al verticale.'],
                            ['role' => 'energy_broker_agent', 'desc' => 'Lead, upload bollette, pratiche e portale agente.'],
                            ['role' => 'energy_broker_backoffice', 'desc' => 'Validazione documenti, pipeline e compliance operativa.'],
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
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Mappa pannello Energy Broker</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Il cuore della demo e UI-03: acquisizione dati reale via LLM da bollette e contratti PDF.
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Documentazione: <span class="font-semibold">docs/energy-broker/01-mockup-energy-broker.md</span>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerDashboard::getUrl(panel: 'energy-broker'), 'title' => 'UI-01 - Dashboard direzionale', 'description' => 'KPI su pratiche, switch, provvigioni e anomalie documentali.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerCrmPage::getUrl(panel: 'energy-broker'), 'title' => 'UI-02 - CRM lead e clienti', 'description' => 'Prospect, clienti, sedi, utenze, consensi e agente assegnato.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerDocumentAcquisitionPage::getUrl(panel: 'energy-broker'), 'title' => 'UI-03 - Acquisizione LLM bolletta e contratto', 'description' => 'Upload PDF, chiamata LLM, campi estratti, confidence e conferma manuale.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerOfferComparatorPage::getUrl(panel: 'energy-broker'), 'title' => 'UI-04 - Comparatore offerte', 'description' => 'Confronto luce/gas, risparmio stimato e marginalita broker.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerPipelinePage::getUrl(panel: 'energy-broker'), 'title' => 'UI-05 - Pipeline pratiche', 'description' => 'Bozza, firma, invio fornitore, KO, switch e liquidazione.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerCompliancePage::getUrl(panel: 'energy-broker'), 'title' => 'UI-06 - Backoffice e compliance', 'description' => 'Documenti, privacy, firma, IBAN e controlli prima dell invio.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerCommissionsPage::getUrl(panel: 'energy-broker'), 'title' => 'UI-07 - Provvigioni e agenti', 'description' => 'Commissioni attese, riconciliazioni e contestazioni.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerCustomerCarePage::getUrl(panel: 'energy-broker'), 'title' => 'UI-08 - Customer care e rinnovi', 'description' => 'Ticket, scadenze, retention e rinnovi.'],
                        ['url' => \Modules\EnergyBroker\Filament\Pages\EnergyBrokerAgentPortalPage::getUrl(panel: 'energy-broker'), 'title' => 'UI-09 - Portale agente', 'description' => 'Lead, upload bollette, stato pratiche e provvigioni personali.'],
                    ] as $page)
                        <a href="{{ $page['url'] }}"
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500/40 dark:hover:bg-blue-500/10">
                            <p class="text-base font-semibold text-slate-950 dark:text-white">{{ $page['title'] }}</p>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $page['description'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Comandi demo utenti</h3>
                    <div class="mt-4 space-y-3 text-xs text-slate-600 dark:text-slate-300">
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-energy-broker-user broker@test.local "Broker Admin" --password=secret --role=energy_broker_admin
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-energy-broker-user agente@test.local "Agente Broker" --password=secret --role=energy_broker_agent
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6 text-blue-950 shadow-sm dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-100">
                    <h3 class="text-base font-semibold">Regola del mockup</h3>
                    <p class="mt-2 text-sm">
                        Il LLM legge e propone dati, ma il salvataggio finale rimane sempre una conferma umana del backoffice.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
