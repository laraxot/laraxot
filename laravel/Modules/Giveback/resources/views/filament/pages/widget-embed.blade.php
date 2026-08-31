<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,1fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Esempio del box sul sito del negozio</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Questa e la parte che vedrebbe il cliente. Nel mockup la versione consigliata e pre-acquisto con split payment.</p>
                <div class="mt-5 rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-5 dark:border-emerald-500/30 dark:from-emerald-500/10 dark:to-slate-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-300">GiveBack</p>
                    <p class="mt-3 text-xl font-semibold text-slate-950 dark:text-white">Oggi sono fortunato</p>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Con 1 euro tenti la fortuna, ricevi subito la promo checkout e partecipi al premio piattaforma.</p>
                    <div class="mt-4 grid gap-2 text-xs text-slate-600 dark:text-slate-300 md:grid-cols-3">
                        <div class="rounded-xl bg-white px-3 py-2 dark:bg-slate-800">1 euro fisso</div>
                        <div class="rounded-xl bg-white px-3 py-2 dark:bg-slate-800">Split payment</div>
                        <div class="rounded-xl bg-white px-3 py-2 dark:bg-slate-800">Premio piattaforma</div>
                    </div>
                    <button type="button" class="mt-5 w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white">Oggi sono fortunato</button>
                    <p class="mt-3 text-center text-xs font-medium uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Tenti la fortuna</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Dove lo metto?</h3>
                    <div class="mt-4 space-y-3">
                        <label class="flex items-start gap-3 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-950 dark:bg-emerald-500/10 dark:text-emerald-100">
                            <input type="radio" checked class="mt-1">
                            <span><strong>Pre-acquisto con split payment:</strong> consigliato per partire, perche tiene il messaggio vicino al checkout.</span>
                        </label>
                        <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            <input type="radio" class="mt-1">
                            <span><strong>Pagina dedicata:</strong> possibile, ma resta una variante secondaria e chiede email prima del pagamento.</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Azioni finte della demo</h3>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <button type="button" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Copia codice</button>
                        <button type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Controlla sito</button>
                        <button type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Invia guida al negozio</button>
                    </div>
                    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Nella versione vera questi pulsanti copieranno il codice, controlleranno il posizionamento pre-acquisto e manderanno istruzioni.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
