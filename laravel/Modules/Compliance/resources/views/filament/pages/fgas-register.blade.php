<x-filament-panels::page>
    @php($circuits = $this->circuits())
    @php($pending = $this->pendingRegistry())

    {{-- ------------------------------------------------------------- F-Gas --}}
    <section class="space-y-3">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                {{ __('compliance::fgas.circuits') }}
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">Reg. (UE) 517/2014, art. 4</span>
        </div>

        @if (count($circuits) === 0)
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                {{ __('compliance::fgas.circuits') }} — 0
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.customer') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.description') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.refrigerant_code') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('compliance::fgas.fields.charge_kg') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('compliance::fgas.fields.co2_equivalent_tonnes') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('compliance::fgas.fields.check_interval_months') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.next_leak_check_due_on') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($circuits as $circuit)
                            <tr class="border-b border-gray-50 dark:border-white/5 {{ $circuit['overdue'] ? 'bg-danger-50 dark:bg-danger-500/10' : '' }}">
                                <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $circuit['customer'] }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                                    {{ $circuit['description'] }}
                                    <span class="block text-xs text-gray-400">{{ $circuit['code'] }}</span>
                                </td>
                                <td class="px-4 py-2 font-mono text-xs text-gray-700 dark:text-gray-200">{{ $circuit['refrigerant'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $circuit['charge_kg'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $circuit['co2'] ?? '—' }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">
                                    {{ $circuit['interval'] ?? '—' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if ($circuit['requires_checks'])
                                        <span class="tabular-nums {{ $circuit['overdue'] ? 'font-semibold text-danger-700 dark:text-danger-200' : 'text-gray-700 dark:text-gray-200' }}">
                                            {{ $circuit['next_check'] ?? '—' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('compliance::fgas.exemptions.' . $circuit['exemption']) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- ---------------------------------------------------------- register --}}
    <section class="space-y-3">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
            {{ __('compliance::fgas.fields.registry') }}
        </h2>

        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
            {{ __('compliance::fgas.registry.manual_notice') }}
        </div>

        @if (count($pending) === 0)
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                {{ __('compliance::fgas.registry.submitted') }} — 0 {{ __('compliance::fgas.registry.not_submitted') }}
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.customer') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.heating_system') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.performed_on') }}</th>
                            <th class="px-4 py-2">{{ __('compliance::fgas.fields.registry') }}</th>
                            <th class="px-4 py-2">Bollino</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pending as $row)
                            <tr class="border-b border-gray-50 dark:border-white/5">
                                <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $row['customer'] }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $row['system'] }}</td>
                                <td class="px-4 py-2 tabular-nums text-gray-600 dark:text-gray-300">{{ $row['performed_on'] }}</td>
                                <td class="px-4 py-2">
                                    <span class="rounded-full bg-primary-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-primary-700 dark:bg-primary-500/20 dark:text-primary-300">
                                        {{ $row['registry'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $row['bollino_code'] ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-filament-panels::page>
