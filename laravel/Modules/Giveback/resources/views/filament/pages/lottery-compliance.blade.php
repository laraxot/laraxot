<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-white">Premio piattaforma</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                Qui si imposta il premio che i clienti possono vincere. La pagina ricorda anche cosa serve prima di andare online e chiarisce che il premio non arriva dal merchant.
            </p>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['label' => 'Ticket mese', 'value' => '2.510'],
                    ['label' => 'Premi attivi', 'value' => '5'],
                    ['label' => 'Fideiussione', 'value' => 'Da aprire'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Form premio</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Nome premio</span>
                        <input type="text" value="Cesto prodotti locali" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Valore premio</span>
                        <input type="text" value="250 euro" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Data estrazione</span>
                        <input type="text" value="30 giugno 2026" class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-200">Stato documenti</span>
                        <select class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option>Da preparare</option>
                            <option>In verifica</option>
                            <option>Pronto</option>
                        </select>
                    </label>
                </div>
                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="button" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Salva premio</button>
                    <button type="button" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 dark:border-slate-700 dark:text-slate-200">Anteprima comunicazione piattaforma</button>
                </div>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                <h3 class="text-base font-semibold">Non andare online se manca questo</h3>
                <p class="mt-2">Prima servono conferma legale, regolamento e fideiussione della somma premio al Ministero.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-950 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
            <h3 class="text-base font-semibold">Nota legale importante</h3>
            <p class="mt-2">
                In Italia le estrazioni a premi con corrispettivo economico sono soggette a normativa specifica, D.P.R. 430/2001.
                Dal confronto con consulente giochi a premi BNI il modello risulta percorribile, ma richiede fideiussione della somma premio
                al Ministero e gestione formale degli adempimenti.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Consulente operazioni a premio</h3>
                <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                    <p class="font-semibold text-slate-950 dark:text-white">Dr. Stefano Notturno - Olojin Srl</p>
                    <p>San Fior, TV</p>
                    <p>Tel. 0438 409186</p>
                    <p>Cell. 348 8555253</p>
                    <p>Email: s.notturno@olojin.it</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Privacy, GDPR e AI Act</h3>
                <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                    <p class="font-semibold text-slate-950 dark:text-white">Avv. Erika Fischer</p>
                    <p>Consulenza GDPR Privacy & L. 132 AI Act</p>
                    <p>TV</p>
                    <p>Tel. 33784843</p>
                    <p>Email: avvocatofischer@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">Responsabilita operative</h3>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                @foreach ([
                    'GiveBack gestisce ticket, cron, log e notifiche solo dopo via libera legale.',
                    'La piattaforma definisce premio, valore e data estrazione.',
                    'La piattaforma contatta il vincitore e coordina la logistica premio.',
                    'Il committente valida normativa, fideiussione e pratiche ministeriali.',
                ] as $item)
                    <div class="rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $item }}</div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">Checklist pre-lancio premio</h3>
            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Regolamento', 'status' => 'Da validare'],
                    ['label' => 'Fideiussione', 'status' => 'Da aprire'],
                    ['label' => 'Valore premio', 'status' => 'Obbligatorio'],
                    ['label' => 'Comunicazioni', 'status' => 'Da definire'],
                ] as $item)
                    <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-1 text-sm font-semibold text-slate-950 dark:text-white">{{ $item['status'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
