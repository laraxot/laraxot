<x-filament-panels::page>
    @php
        $money = static fn (float $value): string => number_format($value, 2, ',', '.') . ' €';
        $hoursLabel = static fn (int $minutes): string => intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'min';
        $noCustomer = (string) __('workorder::work_orders.periodic_summary.no_customer');
        $genericMaterial = (string) __('workorder::work_orders.periodic_summary.generic_material');
        $outcomeLabel = static function (string $code): string {
            $key = 'interventions.outcomes.' . $code;
            $translated = __($key);

            if (is_string($translated) && $translated !== $key) {
                return $translated;
            }

            return $code === 'unknown'
                ? (string) __('workorder::work_orders.periodic_summary.outcomes.unknown')
                : $code;
        };
        $cardClass = 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900';
        $thClass = 'px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400';
        $tdClass = 'px-3 py-2 text-sm text-slate-700 dark:text-slate-200';
    @endphp

    <div class="space-y-6">
        {{-- Period picker + PDF --}}
        <div class="{{ $cardClass }}">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                        {{ __('workorder::work_orders.periodic_summary.title') }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('workorder::work_orders.periodic_summary.subtitle') }}
                    </p>
                </div>
                <button type="button" wire:click="downloadPdf"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-600">
                    {{ __('workorder::work_orders.periodic_summary.download') }}
                </button>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('workorder::work_orders.fields.report_from') }}
                    <input wire:model.live="from" type="date"
                        class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('workorder::work_orders.fields.report_to') }}
                    <input wire:model.live="to" type="date"
                        class="h-11 rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                </label>
                <div class="md:col-span-2 flex flex-wrap items-end gap-2">
                    @foreach (['this_month', 'last_month', 'this_quarter', 'this_year'] as $preset)
                        <button type="button" wire:click="applyPreset('{{ $preset }}')"
                            class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                            {{ __('workorder::work_orders.periodic_summary.presets.' . $preset) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick figures --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $tiles = [
                    ['label' => __('workorder::work_orders.periodic_summary.totals.customers'), 'value' => $totals['customers']],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.work_orders'), 'value' => $totals['work_orders']],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.work_orders_opened'), 'value' => $totals['work_orders_opened']],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.work_orders_closed'), 'value' => $totals['work_orders_closed']],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.interventions'), 'value' => $totals['interventions']],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.hours'), 'value' => $hoursLabel($totals['minutes'])],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.materials'), 'value' => $money($totals['materials'])],
                    ['label' => __('workorder::work_orders.periodic_summary.totals.total'), 'value' => $money($totals['total'])],
                ];
            @endphp
            @foreach ($tiles as $tile)
                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $tile['label'] }}</div>
                    <div class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ $tile['value'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- Per customer --}}
        <div class="{{ $cardClass }}">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">
                {{ __('workorder::work_orders.periodic_summary.sections.customers') }}
            </h3>
            @if ($customers === [])
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('workorder::work_orders.periodic_summary.empty') }}</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[720px] divide-y divide-slate-200 dark:divide-slate-800">
                        <thead>
                            <tr>
                                <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.customer') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.interventions') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.hours') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.labor') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.materials') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($customers as $row)
                                <tr>
                                    <td class="{{ $tdClass }}">{{ $row['name'] !== '' ? $row['name'] : $noCustomer }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $row['interventions'] }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $hoursLabel($row['minutes']) }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $money($row['labor']) }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $money($row['materials']) }}</td>
                                    <td class="{{ $tdClass }} text-right font-semibold">{{ $money($row['total']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Per work order --}}
        <div class="{{ $cardClass }}">
            <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">
                {{ __('workorder::work_orders.periodic_summary.sections.work_orders') }}
            </h3>
            @if ($work_orders === [])
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('workorder::work_orders.periodic_summary.empty') }}</p>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[720px] divide-y divide-slate-200 dark:divide-slate-800">
                        <thead>
                            <tr>
                                <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.code') }}</th>
                                <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.work_order') }}</th>
                                <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.customer') }}</th>
                                <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.status') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.interventions') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.hours') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($work_orders as $row)
                                <tr>
                                    <td class="{{ $tdClass }}">{{ $row['code'] !== '' ? $row['code'] : '—' }}</td>
                                    <td class="{{ $tdClass }}">{{ $row['title'] }}</td>
                                    <td class="{{ $tdClass }}">{{ $row['customer'] !== '' ? $row['customer'] : $noCustomer }}</td>
                                    <td class="{{ $tdClass }}">{{ $row['status'] !== '' ? $row['status'] : '—' }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $row['interventions'] }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $hoursLabel($row['minutes']) }}</td>
                                    <td class="{{ $tdClass }} text-right font-semibold">{{ $money($row['total']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            {{-- Materials --}}
            <div class="{{ $cardClass }}">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('workorder::work_orders.periodic_summary.sections.materials') }}
                </h3>
                @if ($materials === [])
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('workorder::work_orders.periodic_summary.empty') }}</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead>
                                <tr>
                                    <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.material') }}</th>
                                    <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.source') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.quantity') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($materials as $row)
                                    <tr>
                                        <td class="{{ $tdClass }}">{{ $row['label'] !== '' ? $row['label'] : $genericMaterial }}</td>
                                        <td class="{{ $tdClass }}">{{ __('workorder::work_orders.periodic_summary.sources.' . $row['source']) }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ number_format($row['quantity'], 2, ',', '.') }}</td>
                                        <td class="{{ $tdClass }} text-right font-semibold">{{ $money($row['amount']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        {{ __('workorder::work_orders.periodic_summary.totals.total') }}: {{ $money($materials_total) }}
                    </p>
                @endif
            </div>

            {{-- Quotations + outcomes --}}
            <div class="{{ $cardClass }}">
                <h3 class="text-base font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('workorder::work_orders.periodic_summary.sections.quotations') }}
                </h3>
                @if ($quotations['rows'] === [])
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('workorder::work_orders.periodic_summary.empty') }}</p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead>
                                <tr>
                                    <th class="{{ $thClass }}">{{ __('workorder::work_orders.periodic_summary.columns.status') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.count') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('workorder::work_orders.periodic_summary.columns.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach ($quotations['rows'] as $row)
                                    <tr>
                                        <td class="{{ $tdClass }}">{{ __('quotations.statuses.' . $row['status']) }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ $row['count'] }}</td>
                                        <td class="{{ $tdClass }} text-right font-semibold">{{ $money($row['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <h3 class="mt-6 text-base font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('workorder::work_orders.periodic_summary.sections.outcomes') }}
                </h3>
                @if ($outcomes === [])
                    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('workorder::work_orders.periodic_summary.empty') }}</p>
                @else
                    <ul class="mt-3 space-y-1 text-sm text-slate-700 dark:text-slate-200">
                        @foreach ($outcomes as $code => $count)
                            <li class="flex justify-between gap-4">
                                <span>{{ $outcomeLabel((string) $code) }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
