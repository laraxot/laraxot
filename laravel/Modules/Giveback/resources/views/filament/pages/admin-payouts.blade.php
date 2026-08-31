<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Pre-acquisto e divisione incassi</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        Nota 19/05/2026, Giuliano Florian per Giulia Tonello: lo split payment e la scelta strategica per governare il widget pre-acquisto e dividere automaticamente gli incassi.
                    </p>
                </div>
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-400/20 dark:text-emerald-100">Split payment</span>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Cliente paga', 'value' => 'Un solo checkout', 'desc' => 'Il cliente non deve fare piu pagamenti separati.'],
                    ['title' => 'Sistema divide', 'value' => 'Automatico', 'desc' => 'La piattaforma prepara la divisione tra merchant e piattaforma.'],
                    ['title' => 'Contabilita', 'value' => 'Piu ordinata', 'desc' => 'Le transazioni sono piu facili da certificare e controllare.'],
                ] as $card)
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                        <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $card['title'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-emerald-700 dark:text-emerald-300">{{ $card['value'] }}</p>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $card['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="hidden grid-cols-[1fr_140px_140px_140px_120px] bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-800 md:grid">
                    <div>Merchant</div>
                    <div>Volume 1 euro</div>
                    <div>Quota negozio</div>
                    <div>Quota piattaforma</div>
                    <div>Stato</div>
                </div>
                @foreach ([
                    ['merchant' => 'Fattoria Verde', 'volume' => '1.240 euro', 'merchant_share' => '992 euro', 'platform_share' => '248 euro', 'status' => 'Esempio'],
                    ['merchant' => 'Beauty Club', 'volume' => '860 euro', 'merchant_share' => '688 euro', 'platform_share' => '172 euro', 'status' => 'Esempio'],
                    ['merchant' => 'Travel Box', 'volume' => '420 euro', 'merchant_share' => '336 euro', 'platform_share' => '84 euro', 'status' => 'Review'],
                ] as $row)
                    <div class="grid gap-2 border-t border-slate-200 px-4 py-4 text-sm dark:border-slate-700 md:grid-cols-[1fr_140px_140px_140px_120px]">
                        <div class="font-semibold text-slate-950 dark:text-white">{{ $row['merchant'] }}</div>
                        <div class="text-slate-700 dark:text-slate-200">{{ $row['volume'] }}</div>
                        <div class="text-slate-700 dark:text-slate-200">{{ $row['merchant_share'] }}</div>
                        <div class="text-slate-700 dark:text-slate-200">{{ $row['platform_share'] }}</div>
                        <div class="text-slate-500 dark:text-slate-400">{{ $row['status'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @foreach ([
                'Un solo pagamento per il cliente.',
                'Divisione automatica dietro le quinte.',
                'Merchant donation solo come ipotesi di fase successiva.',
            ] as $item)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 text-sm text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">{{ $item }}</div>
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Controlli attivi in configurazione iniziale</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Verifica idempotenza webhook Stripe.',
                        'Blocco coupon ripetuti nelle 24 ore.',
                        'Review device fingerprint sospetti.',
                        'Log evento pagamento, promo, ticket e email inviata.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Nota di perimetro</h3>
                <p class="mt-2">
                    Nel mockup questa parte mostra l obiettivo. La configurazione reale dei pagamenti andra progettata con attenzione prima del lancio e la donazione merchant resta fuori MVP.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
