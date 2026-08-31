<x-filament-panels::page>
    <div class="space-y-6">
        <div class="overflow-hidden rounded-3xl border border-orange-200 bg-gradient-to-br from-orange-50 via-white to-amber-50 p-6 shadow-sm dark:border-orange-500/20 dark:from-slate-900 dark:via-slate-900 dark:to-orange-950/40">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-orange-800 dark:bg-orange-400/15 dark:text-orange-100">
                        Termoidraulica mockup
                    </span>
                    <div class="space-y-2">
                        <h2 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Dal sopralluogo al cantiere, con storico impianto e cicli Bluetooth</h2>
                        <p class="max-w-3xl text-sm text-slate-600 dark:text-slate-300">
                            Mockup navigabile per il caso Minerva Energy Solution: calendario, CRM cliente, sopralluoghi, preventivi,
                            app tecnico, avanzamento lavori, documenti, fatturazione e integrazione pompa Bluetooth da validare.
                        </p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach ([
                            ['label' => 'Schermate mockup', 'value' => '8'],
                            ['label' => 'Ruoli dedicati', 'value' => '4'],
                            ['label' => 'Integrazione da analizzare', 'value' => 'Bluetooth'],
                        ] as $item)
                            <div class="rounded-2xl border border-white/70 bg-white/80 p-4 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-900/80">
                                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-orange-100 bg-white/90 p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900/85">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Ruoli previsti</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            ['role' => 'thermo_admin', 'desc' => 'Titolare o ufficio: CRM, calendario, preventivi, fatture, documenti e controllo margini.'],
                            ['role' => 'thermo_technician', 'desc' => 'Tecnico sul campo: agenda, sopralluoghi, rapportini, foto, firme e cicli pompa.'],
                            ['role' => 'thermo_supplier_call_center', 'desc' => 'Call center esterno del fornitore caldaie: inserisce richieste e lavori nel calendario, senza vedere dati amministrativi.'],
                            ['role' => 'thermo_customer', 'desc' => 'Cliente finale: area documenti, conformita, preventivi firmati e storico essenziale.'],
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
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Mappa pannello termoidraulica</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Ogni pagina rappresenta un blocco funzionale del preventivo: dalla richiesta di intervento alla chiusura tecnica e amministrativa.
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Documentazione: <span class="font-semibold">docs/termoidraulica/01-mockup-termoidraulica.md</span>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ([
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoCalendarPage::getUrl(panel: 'thermo'), 'title' => 'UI-01 - Calendario, urgenze e assegnazioni', 'description' => 'Piano giornaliero, call center, priorita, raggio km, promemoria cliente e navigatore.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoCrmPage::getUrl(panel: 'thermo'), 'title' => 'UI-02 - CRM, clienti, impianti e documenti', 'description' => 'Anagrafica, ricerca rapida, sedi, impianti, area cliente, conformita e storico.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoAccountAccessPage::getUrl(panel: 'thermo'), 'title' => 'UI-03 - Accessi account, registrazione e MFA', 'description' => 'Email vere, registrazione obbligatoria, codice a due fattori e recupero password.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoSurveyQuotePage::getUrl(panel: 'thermo'), 'title' => 'UI-04 - Sopralluoghi, preventivi e accettazione', 'description' => 'Il sopralluogo e la base del preventivo: checklist, foto, voce, kit, margine, extra e firma.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoFieldAppPage::getUrl(panel: 'thermo'), 'title' => 'UI-05 - App tecnico, SAL e rapportino', 'description' => 'Intervento mobile, check-list cantiere, materiali extra, ore, percentuale SAL e rapportino.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoComplianceBillingPage::getUrl(panel: 'thermo'), 'title' => 'UI-06 - DiCo, foto finali, fatture e pagamenti', 'description' => 'Documentazione fine lavori, generatore DiCo, prefattura e integrazione Fatture in Cloud.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoPumpPage::getUrl(panel: 'thermo'), 'title' => 'UI-07 - Pompa Bluetooth e cicli anticalcare', 'description' => 'Scheda requisito per collegare cicli di pulizia anticalcare a cliente, impianto e intervento.'],
                        ['url' => \Modules\Thermo\Filament\Pages\ThermoAiDocumentsPage::getUrl(panel: 'thermo'), 'title' => 'UI-08 - AI, fatture ricevute e documenti PDF', 'description' => 'Caricamento PDF per fatture ricevute, DDT, libretti e documenti tecnici con proposta AI.'],
                    ] as $page)
                        <a href="{{ $page['url'] }}"
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-orange-300 hover:bg-orange-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-orange-500/40 dark:hover:bg-orange-500/10">
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
                            php artisan auth:create-thermo-user ufficio@minerva.local "Minerva Office" --password=secret --role=thermo_admin
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-thermo-user tecnico@minerva.local "Tecnico Minerva" --password=secret --role=thermo_technician
                        </div>
                        <div class="rounded-xl bg-slate-50 px-4 py-3 font-mono dark:bg-slate-800">
                            php artisan auth:create-thermo-user callcenter@fornitore.local "Call Center Fornitore" --password=secret --role=thermo_supplier_call_center
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Nota di perimetro</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        @foreach ([
                            'Il preventivo PDF copre formalmente analisi e mockup, non lo sviluppo completo in produzione.',
                            'La pompa Bluetooth e trattata come integrazione da validare: marca, protocollo, app esistente e dati disponibili cambiano il costo reale.',
                            'Le funzionalita esterne come SMS, WhatsApp, firma OTP, Metel e Fatture in Cloud richiedono credenziali, API e accordo tecnico separato.',
                        ] as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
