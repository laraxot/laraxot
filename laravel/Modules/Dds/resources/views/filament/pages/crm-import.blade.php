<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-01 - CRM, importazione e anagrafiche</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Mockup del back office commerciale: acquisizione dati legacy, mappatura campi stile Mailchimp, clienti con sedi multiple e contatti operativi.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Scarica template CSV
                    </button>
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                        Avvia importazione guidata
                    </button>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                @foreach ([
                    ['label' => 'Clienti importabili', 'value' => '2.140'],
                    ['label' => 'Sedi multiple', 'value' => '4.980'],
                    ['label' => 'Campi mappabili', 'value' => '28'],
                    ['label' => 'Errori da correggere', 'value' => '17'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(340px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-950 dark:text-white">Schema campi importazione canonical</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">I nomi sono pensati per un wizard di mapping visuale con preview e validazione riga per riga.</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">CSV / XLSX</span>
                </div>

                <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="hidden grid-cols-[1fr_1fr_1.2fr] gap-4 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800/80 dark:text-slate-300 md:grid">
                        <div>Campo</div>
                        <div>Dominio</div>
                        <div>Uso</div>
                    </div>
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach ([
                            ['field' => 'external_id', 'domain' => 'shared', 'use' => 'Chiave legacy del gestionale precedente.'],
                            ['field' => 'company_name', 'domain' => 'client', 'use' => 'Ragione sociale cliente.'],
                            ['field' => 'vat_number', 'domain' => 'client', 'use' => 'Partita IVA o codice fiscale.'],
                            ['field' => 'site_name', 'domain' => 'site', 'use' => 'Nome sede o edificio servito.'],
                            ['field' => 'site_address', 'domain' => 'site', 'use' => 'Indirizzo completo della sede operativa.'],
                            ['field' => 'primary_contact_name', 'domain' => 'contact', 'use' => 'Referente principale per commerciale o interventi.'],
                            ['field' => 'primary_contact_phone', 'domain' => 'contact', 'use' => 'Telefono diretto o mobile.'],
                            ['field' => 'service_frequency', 'domain' => 'contract', 'use' => 'Cadenza prevista: mensile, trimestrale, 12/anno.'],
                            ['field' => 'service_window_hours', 'domain' => 'work', 'use' => 'Durata standard slot intervento.'],
                            ['field' => 'billing_provider_code', 'domain' => 'billing', 'use' => 'Riferimento per Fatture in Cloud o provider esterno.'],
                        ] as $row)
                            <div class="grid gap-2 px-4 py-4 text-sm text-slate-700 dark:text-slate-200 md:grid-cols-[1fr_1fr_1.2fr] md:gap-4">
                                <div class="font-semibold text-slate-950 dark:text-white">{{ $row['field'] }}</div>
                                <div>{{ $row['domain'] }}</div>
                                <div>{{ $row['use'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Pipeline commerciale</h3>
                    <div class="mt-4 space-y-3">
                        @foreach ([
                            ['stage' => 'Lead importato', 'count' => '184'],
                            ['stage' => 'Sopralluogo pianificato', 'count' => '43'],
                            ['stage' => 'Offerta inviata', 'count' => '27'],
                            ['stage' => 'Contratto in firma', 'count' => '8'],
                        ] as $stage)
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-200">{{ $stage['stage'] }}</span>
                                <span class="text-sm font-semibold text-slate-950 dark:text-white">{{ $stage['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Entita coperte</h3>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium">
                        @foreach (['Clienti', 'Sedi', 'Contatti', 'Tag impianto', 'Cluster infestanti', 'Listini', 'Contratti attivi'] as $item)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
