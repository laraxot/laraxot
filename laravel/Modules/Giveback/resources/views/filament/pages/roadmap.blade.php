<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Cosa si fa ora e cosa si fa dopo</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                Questa pagina serve a non promettere tutto subito. Prima si prova se negozi e clienti usano davvero GiveBack.
            </p>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                @foreach ([
                    ['plan' => 'Starter', 'price' => 'da 99 euro/mese', 'target' => 'Fino a 200 tentativi/mese'],
                    ['plan' => 'Growth', 'price' => 'da 199 euro/mese', 'target' => 'Fino a 1.000 tentativi/mese'],
                    ['plan' => 'Pro', 'price' => 'da 499 euro/mese', 'target' => 'Volumi illimitati'],
                    ['plan' => 'Enterprise', 'price' => 'Da concordare', 'target' => 'Grandi gruppi'],
                ] as $plan)
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="font-semibold text-slate-950 dark:text-white">{{ $plan['plan'] }}</p>
                        <p class="mt-2 text-sm text-emerald-700 dark:text-emerald-300">{{ $plan['price'] }}</p>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $plan['target'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Prima versione - 5 mesi</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach (['Disegno schermate e regole', 'Pagamenti e codici promo', 'Widget pre-acquisto e area negozio', 'Premio piattaforma e compliance', 'Prova con 3-5 negozi'] as $phase)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $phase }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Dopo, solo se la prova funziona</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Pagamenti e bonifici automatici.',
                        'Merchant donation come linea opzionale.',
                        'Collegamento diretto con Shopify e WooCommerce.',
                        'Versione per grandi aziende.',
                        'App mobile solo se serve davvero.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Come lo costruiamo</h3>
                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                    @foreach ([
                        ['area' => 'Sistema', 'choice' => 'Laravel', 'reason' => 'E lo strumento che conosciamo meglio.'],
                        ['area' => 'Schermate', 'choice' => 'Filament', 'reason' => 'Permette di creare velocemente aree gestionali.'],
                        ['area' => 'Pagamenti', 'choice' => 'Stripe', 'reason' => 'Per far pagare 1 euro in modo ordinato.'],
                        ['area' => 'Box negozio', 'choice' => 'Widget', 'reason' => 'Il pezzetto da mettere sul sito del negozio in pre-acquisto con split payment.'],
                    ] as $row)
                        <div class="grid gap-2 border-t border-slate-200 px-4 py-4 text-sm first:border-t-0 dark:border-slate-700 md:grid-cols-[180px_1fr_1.4fr]">
                            <div class="font-semibold text-slate-950 dark:text-white">{{ $row['area'] }}</div>
                            <div class="text-emerald-700 dark:text-emerald-300">{{ $row['choice'] }}</div>
                            <div class="text-slate-600 dark:text-slate-300">{{ $row['reason'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Fuori configurazione iniziale</h3>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        'Dashboard bonifici operativa, se non confermata.',
                        'Stripe Connect e Stripe Billing.',
                        'CRM gia in uso da Giulia, corso CRM e gestione campagne.',
                        'Plugin Shopify/WooCommerce.',
                        'White label, SSO e app mobile.',
                    ] as $item)
                        <div class="rounded-xl bg-white/70 px-4 py-3 dark:bg-slate-900/50">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">Persone da coinvolgere prima del lancio</h3>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['name' => 'Giuliano Florian', 'role' => 'Nota strategia pagamento per Giulia Tonello, 19/05/2026.'],
                    ['name' => 'Dr. Stefano Notturno', 'role' => 'Operazioni a premio, fideiussione e adempimenti premio.'],
                    ['name' => 'Commercialista / bando Veneto', 'role' => 'Contatto da confermare e documento bando da reinviare a Giuliano.'],
                ] as $person)
                    <div class="rounded-xl bg-slate-50 p-4 text-sm dark:bg-slate-800">
                        <p class="font-semibold text-slate-950 dark:text-white">{{ $person['name'] }}</p>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">{{ $person['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
