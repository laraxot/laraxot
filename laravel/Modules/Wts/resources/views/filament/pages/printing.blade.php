<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Coda di stampa --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Coda di stampa etichette</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Le etichette vengono messe in coda qui e stampate dall'agente sul PC di impianto.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-400/20 dark:text-amber-100">{{ $pendingCount }} in attesa</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-100">{{ $printedCount }} stampate</span>
                    <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800 dark:bg-rose-500/10 dark:text-rose-100">{{ $failedCount }} fallite</span>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <button type="button" wire:click="printTest"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-600">
                    Stampa di prova
                </button>
                <button type="button" wire:click="retryFailed" @disabled($failedCount === 0)
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Riprova le fallite
                </button>
                <button type="button" wire:click="clearPrinted" @disabled($printedCount === 0)
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Svuota lo storico completate
                </button>
            </div>
        </div>

        {{-- Ultime stampe --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Etichetta</th>
                            <th class="px-4 py-3">Stato</th>
                            <th class="px-4 py-3">Copie</th>
                            <th class="px-4 py-3">Quando</th>
                            <th class="px-4 py-3">Errore</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($jobs as $job)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-950 dark:text-slate-100">{{ $job->label_code ?? ('#'.$job->id) }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    @php
                                        $badge = match ($job->status_code) {
                                            'printed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-100',
                                            'failed' => 'bg-rose-100 text-rose-800 dark:bg-rose-500/10 dark:text-rose-100',
                                            default => 'bg-amber-100 text-amber-800 dark:bg-amber-400/20 dark:text-amber-100',
                                        };
                                        $label = match ($job->status_code) {
                                            'printed' => 'Stampata',
                                            'failed' => 'Fallita',
                                            default => 'In attesa',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">{{ $job->copies }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-600 dark:text-slate-300">{{ optional($job->printed_at ?? $job->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-xs text-rose-700 dark:text-rose-300">{{ $job->error }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Nessuna stampa in coda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Configurazione / come collegare l'agente --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">Configurazione stampa</h3>

            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Formato etichetta</p>
                    <p class="mt-1 text-base font-semibold text-slate-950 dark:text-slate-100">{{ $labelWidth }} × {{ $labelHeight }} mm</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Token agente</p>
                    <p class="mt-1 text-base font-semibold {{ $tokenConfigured ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">
                        {{ $tokenConfigured ? 'Configurato' : 'Da configurare' }}
                    </p>
                </div>
                <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Stampante</p>
                    <p class="mt-1 text-base font-semibold text-slate-950 dark:text-slate-100">impostata sull'agente</p>
                </div>
            </div>

            <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-slate-950 dark:text-slate-300">
                <p class="font-semibold text-slate-800 dark:text-slate-100">Come collegare la stampante</p>
                <ol class="mt-2 list-decimal space-y-1 pl-5">
                    <li>Sul server imposta <code>WTS_PRINT_AGENT_TOKEN</code> (una password a piacere) nel file <code>.env</code>.</li>
                    <li>Sul PC di impianto (quello che raggiunge la stampante) avvia l'agente con lo stesso token:<br>
                        <code>WTS_PRINT_AGENT_TOKEN=... php wts-print-agent.php</code></li>
                    <li>Se l'IP della stampante non è 192.168.11.24:9100, passalo con <code>WTS_PRINTER_HOST</code> / <code>WTS_PRINTER_PORT</code>.</li>
                    <li>Premi <strong>Stampa di prova</strong> qui sopra: se esce un'etichetta, è tutto collegato.</li>
                </ol>
            </div>
        </div>
    </div>
</x-filament-panels::page>
