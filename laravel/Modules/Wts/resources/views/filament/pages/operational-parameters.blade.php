<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-wide text-teal-700 dark:text-teal-300">Parametri reali WTS</p>
            <h2 class="mt-2 text-lg font-semibold text-slate-950 dark:text-slate-100">Parametri operativi WTS</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Questi valori guidano i calcoli operativi di fusti, bancali, rimanenze e segnalazioni. I default arrivano da configurazione, ma da qui restano modificabili senza deploy.
            </p>
        </div>

        <div class="grid gap-4">
            @foreach ($parameters as $code => $parameter)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_220px_auto] lg:items-end">
                        <div>
                            <p class="text-base font-semibold text-slate-950 dark:text-slate-100">{{ $parameter->label }}</p>
                            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">{{ $code }}</p>
                            @if ($parameter->description)
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $parameter->description }}</p>
                            @endif
                        </div>

                        <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            Valore {{ $parameter->unit ? '(' . $parameter->unit . ')' : '' }}
                            <input
                                wire:model="parameterValues.{{ $code }}"
                                type="text"
                                class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm outline-none ring-0 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            />
                        </label>

                        <button
                            type="button"
                            wire:click="saveParameter('{{ $code }}')"
                            class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-700 px-4 text-sm font-semibold text-white transition hover:bg-blue-600"
                        >
                            Salva parametro
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
