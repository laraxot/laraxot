<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,0.7fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Preparazione negozio</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Esempio di form che il negoziante compila per partire. I campi sono dimostrativi.</p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Nome negozio</span>
                        <input type="text" value="Fattoria Verde Shop" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Sito web</span>
                        <input type="text" value="www.fattoriaverde.it" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Posizionamento widget</span>
                        <select class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option>Pre-acquisto con split payment</option>
                            <option>Pagina dedicata secondaria</option>
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Sconto da dare</span>
                        <select class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option>10% sul checkout in corso</option>
                            <option>5% sul prossimo ordine</option>
                            <option>Spedizione gratuita</option>
                        </select>
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Messaggio promo nel widget</span>
                        <input type="text" value="Oggi sono fortunato - aggiungi 1 euro e prova la fortuna" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Carica codici promo</span>
                        <div class="mt-1 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            Trascina qui il file CSV oppure clicca "Carica file"
                        </div>
                    </label>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="button" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Salva bozza</button>
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Anteprima widget</button>
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Scarica guida</button>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-semibold text-slate-950 dark:text-white">Passi da completare</h3>
                    <div class="mt-4 space-y-3 text-sm">
                        @foreach ([
                            ['label' => 'Dati negozio', 'status' => 'Fatto'],
                            ['label' => 'Posizionamento pre-acquisto', 'status' => 'Fatto'],
                            ['label' => 'Codici promo', 'status' => 'Da caricare'],
                            ['label' => 'Premio piattaforma e fideiussione', 'status' => 'Da controllare'],
                            ['label' => 'Widget sul sito', 'status' => 'Da copiare'],
                        ] as $step)
                            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                                <span class="text-slate-700 dark:text-slate-200">{{ $step['label'] }}</span>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $step['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                    <h3 class="text-base font-semibold">Avvisi automatici</h3>
                    <p class="mt-2">Nella versione vera il sistema avvisa se finiscono i coupon, se manca il premio piattaforma o se nessuno usa il widget.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
