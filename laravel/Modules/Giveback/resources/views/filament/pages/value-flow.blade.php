<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-300">Schermata cliente</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">Oggi sono fortunato</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    Esempio di quello che vede il cliente. I campi e i pulsanti servono solo a far capire il percorso del tenti la fortuna.
                </p>

                <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-500/30 dark:bg-emerald-500/10">
                    <p class="text-sm font-semibold text-slate-950 dark:text-white">Tenti la fortuna con 1 euro e sblocchi subito la promo checkout</p>
                    <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">Il premio mensile e messo in palio dalla piattaforma. Il merchant offre la promo immediata e il pagamento resta unico.</p>
                    <div class="mt-4 grid gap-3 md:grid-cols-3">
                        <div class="rounded-2xl bg-white p-4 text-sm shadow-sm dark:bg-slate-900">
                            <p class="font-semibold text-slate-950 dark:text-white">Importo</p>
                            <p class="mt-1 text-slate-600 dark:text-slate-300">1 euro fisso</p>
                        </div>
                        <div class="rounded-2xl bg-white p-4 text-sm shadow-sm dark:bg-slate-900">
                            <p class="font-semibold text-slate-950 dark:text-white">Promo</p>
                            <p class="mt-1 text-slate-600 dark:text-slate-300">10% sul checkout in corso</p>
                        </div>
                        <div class="rounded-2xl bg-white p-4 text-sm shadow-sm dark:bg-slate-900">
                            <p class="font-semibold text-slate-950 dark:text-white">Premio</p>
                            <p class="mt-1 text-slate-600 dark:text-slate-300">Estratto dalla piattaforma</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">Modulo di prova</h3>
                <div class="mt-5 space-y-4">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">La tua email</span>
                        <input type="email" value="cliente@example.com" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Importo confermato</span>
                        <input type="text" value="1 euro" readonly class="mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Dopo il click: split payment, promo checkout applicata, ticket premio creato e conferma inviata via email.
                    </div>
                    <button type="button" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white">Oggi sono fortunato</button>
                    <button type="button" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Tenti la fortuna</button>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
            <p class="font-semibold">Promemoria semplice</p>
            <p class="mt-2">Per il mockup la posizione consigliata resta il pre-acquisto. Se il box vive fuori checkout, bisogna chiedere sempre l email prima del pagamento.</p>
        </div>
    </div>
</x-filament-panels::page>
