<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">Valore stimato magazzino</p>
                <p class="mt-2 text-3xl font-semibold text-amber-950 dark:text-amber-100">{{ number_format((float) $stats['estimated_stock_value'], 0, ',', '.') }} &euro;</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Resa media lavorazioni</p>
                <p class="mt-2 text-3xl font-semibold text-slate-950 dark:text-slate-100">
                    {{ $stats['yield_percent'] !== null ? number_format((float) $stats['yield_percent'], 1, ',', '.') . '%' : 'N/D' }}
                </p>
            </div>
            <div class="rounded-2xl border p-5 shadow-sm {{ $stats['lots_stuck'] > 0 ? 'border-orange-200 bg-orange-50 dark:border-orange-500/30 dark:bg-orange-500/10' : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900' }}">
                <p class="text-xs font-semibold uppercase tracking-wide {{ $stats['lots_stuck'] > 0 ? 'text-orange-700 dark:text-orange-200' : 'text-slate-500 dark:text-slate-400' }}">Lotti fermi da troppo tempo</p>
                <p class="mt-2 text-3xl font-semibold {{ $stats['lots_stuck'] > 0 ? 'text-orange-950 dark:text-orange-100' : 'text-slate-950 dark:text-slate-100' }}">{{ $stats['lots_stuck'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Cicli di essiccazione in corso</p>
                <p class="mt-2 text-3xl font-semibold text-slate-950 dark:text-slate-100">{{ $stats['drying_in_progress'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Composizione materiale per stato</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Quantita di materiale derivato per fase del ciclo di lavorazione.</p>

                @if ($statusSegments === [])
                    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Nessun materiale derivato registrato.</p>
                @else
                    <div class="mt-6 flex h-7 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800" role="img" aria-label="Composizione materiale per stato">
                        @foreach ($statusSegments as $index => $segment)
                            <div class="{{ $segment['color'] }} h-full {{ $index < count($statusSegments) - 1 ? 'mr-[2px]' : '' }}"
                                style="width: {{ $segment['percent'] }}%"
                                title="{{ $segment['label'] }}: {{ $segment['formattedValue'] }} ({{ $segment['percent'] }}%)"
                            ></div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2">
                        @foreach ($statusSegments as $segment)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $segment['color'] }}"></span>
                                <span class="text-slate-600 dark:text-slate-300">{{ $segment['label'] }}</span>
                                <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $segment['formattedValue'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Giacenza per specie legnosa</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Quantita disponibile nei lotti in piazzale, per specie.</p>

                @if ($speciesBars === [])
                    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Nessun lotto disponibile.</p>
                @else
                    <div class="mt-8 flex gap-4" style="height: 11rem;">
                        @foreach ($speciesBars as $bar)
                            <div class="flex flex-1 flex-col items-center">
                                <span class="text-xs font-semibold text-slate-950 dark:text-slate-100">{{ $bar['formattedValue'] }}</span>
                                <div class="mt-2 flex w-full flex-1 items-end justify-center">
                                    <div class="w-full max-w-[52px] rounded-t-md bg-amber-600 dark:bg-amber-400"
                                        style="height: {{ max($bar['heightPercent'], 3) }}%"
                                        title="{{ $bar['label'] }}: {{ $bar['formattedValue'] }}"
                                    ></div>
                                </div>
                                <span class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Andamento acquisti (ultime 8 settimane)</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Quantita di legname acquistata per settimana.</p>

                @if ($trendBars === [])
                    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Nessun acquisto registrato nelle ultime 8 settimane.</p>
                @else
                    <div class="mt-8 flex gap-2" style="height: 11rem;">
                        @foreach ($trendBars as $bar)
                            <div class="flex flex-1 flex-col items-center">
                                <span class="text-xs font-semibold text-slate-950 dark:text-slate-100">{{ $bar['formattedValue'] }}</span>
                                <div class="mt-2 flex w-full flex-1 items-end justify-center">
                                    <div class="w-full max-w-[36px] rounded-t-md bg-blue-600 dark:bg-blue-400"
                                        style="height: {{ max($bar['heightPercent'], 2) }}%"
                                        title="Settimana del {{ $bar['label'] }}: {{ $bar['formattedValue'] }}"
                                    ></div>
                                </div>
                                <span class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $bar['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Top fornitori per quantita acquistata</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">I 5 fornitori con il maggior quantitativo consegnato.</p>

                @if ($supplierBars === [])
                    <p class="mt-6 text-sm text-slate-500 dark:text-slate-400">Nessun acquisto registrato.</p>
                @else
                    <div class="mt-6 space-y-3">
                        @foreach ($supplierBars as $bar)
                            <div>
                                <div class="mb-1 flex items-center justify-between text-sm">
                                    <span class="text-slate-700 dark:text-slate-200">{{ $bar['label'] }}</span>
                                    <span class="font-semibold text-slate-950 dark:text-slate-100">{{ $bar['formattedValue'] }}</span>
                                </div>
                                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-violet-600 dark:bg-violet-400"
                                        style="width: {{ max($bar['widthPercent'], 3) }}%"
                                        title="{{ $bar['label'] }}: {{ $bar['formattedValue'] }}"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
