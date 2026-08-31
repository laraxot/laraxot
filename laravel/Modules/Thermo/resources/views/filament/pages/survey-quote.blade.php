<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-white">UI-04 - Sopralluoghi, preventivi e accettazione</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                    Il sopralluogo e la base del preventivo: dati tecnici, foto, voce e misure vengono trascinati nel preventivo con margine ed extra.
                </p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-slate-950 p-5 shadow-sm dark:border-slate-700">
                <div class="mx-auto max-w-sm rounded-[2rem] bg-white p-5 shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Sopralluogo</p>
                            <p class="text-sm font-semibold text-slate-950">Sostituzione scaldabagno</p>
                        </div>
                        <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800">Bozza</span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ([
                            ['label' => 'Tipo caldaia/scaldabagno', 'value' => 'Da selezionare'],
                            ['label' => 'Canna fumaria', 'value' => 'Foto + nota tecnica'],
                            ['label' => 'Scarichi e passaggi tubi', 'value' => 'Annotazione su foto'],
                            ['label' => 'Misure principali', 'value' => '3 campi mancanti'],
                        ] as $row)
                            <div class="rounded-2xl border border-slate-200 px-4 py-3">
                                <p class="text-xs uppercase tracking-wide text-slate-500">{{ $row['label'] }}</p>
                                <p class="mt-1 text-sm font-semibold text-slate-950">{{ $row['value'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 grid grid-cols-3 gap-2 text-center text-xs font-semibold text-slate-700">
                        <button type="button" class="rounded-xl bg-slate-100 px-3 py-3">Foto</button>
                        <button type="button" class="rounded-xl bg-slate-100 px-3 py-3">Voce</button>
                        <button type="button" class="rounded-xl bg-slate-100 px-3 py-3">Firma</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Preventivo intelligente</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        ['label' => 'Kit suggerito', 'value' => 'Sostituzione Scaldabagno 80L'],
                        ['label' => 'Materiali', 'value' => 'Prodotto + raccordi + smaltimento'],
                        ['label' => 'Ore stimate', 'value' => '4,5 ore'],
                            ['label' => 'Extra Preventivo', 'value' => 'Voce extra per lavorazioni/materiali fuori pacchetto'],
                        ['label' => 'Margine stimato', 'value' => '32%'],
                    ] as $row)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $row['label'] }}</p>
                            <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ $row['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Dal sopralluogo al prezzo</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Conversione sopralluogo-preventivo con dati tecnici gia compilati.',
                        'Accesso rapido a listini materiali, Metel e kit installazione.',
                        'Calcolo margine in tempo reale su materiali, ore stimate ed extra preventivo.',
                    ] as $item)
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">Accettazione</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                    @foreach ([
                        'Firma grafometrica sul posto da tablet.',
                        'Firma remota opzionale via link e OTP.',
                        'PDF non modificabile salvato in cloud.',
                        'Distinta base materiali per ordine fornitore.',
                    ] as $item)
                        <div class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
