<x-filament-panels::page>
    @php($receivables = $this->receivables())
    @php($cashFlow = $this->cashFlow())
    @php($margins = $this->jobMargins())

    {{-- ---------------------------------------------------------- receivables --}}
    <section class="space-y-3">
        <div class="flex flex-wrap items-baseline justify-between gap-2">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                {{ __('billing::management_accounting.aging.title') }}
            </h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('billing::management_accounting.aging.as_of', ['date' => $receivables['as_of']]) }}
            </span>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-400">{{ __('billing::management_accounting.aging.total') }}</div>
                <div class="mt-1 text-2xl font-semibold tabular-nums text-gray-900 dark:text-gray-100">&euro; {{ $receivables['total'] }}</div>
            </div>
            <div class="rounded-xl border border-danger-200 bg-danger-50 p-4 dark:border-danger-500/30 dark:bg-danger-500/10">
                <div class="text-xs uppercase tracking-wide text-danger-600 dark:text-danger-300">{{ __('billing::management_accounting.aging.overdue_total') }}</div>
                <div class="mt-1 text-2xl font-semibold tabular-nums text-danger-700 dark:text-danger-200">&euro; {{ $receivables['overdue_total'] }}</div>
            </div>
        </div>

        @foreach ($receivables['buckets'] as $bucket)
            @if ($bucket['count'] > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                    <div class="flex items-baseline justify-between border-b border-gray-100 px-4 py-2 dark:border-white/10">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">
                            {{ __('billing::management_accounting.buckets.' . $bucket['code']) }}
                        </span>
                        <span class="text-sm font-semibold tabular-nums text-gray-700 dark:text-gray-200">&euro; {{ $bucket['total'] }}</span>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs uppercase tracking-wide text-gray-400">
                            <tr>
                                <th class="px-4 py-2">{{ __('billing::billing.fields.customer') }}</th>
                                <th class="px-4 py-2">{{ __('billing::billing.fields.number') }}</th>
                                <th class="px-4 py-2">{{ __('billing::management_accounting.fields.due_on') }}</th>
                                <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.fields.days_overdue') }}</th>
                                <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.fields.outstanding') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bucket['items'] as $item)
                                <tr class="border-t border-gray-50 dark:border-white/5">
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $item['counterparty'] }}</td>
                                    <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $item['reference'] }}</td>
                                    <td class="px-4 py-2 tabular-nums text-gray-600 dark:text-gray-300">{{ $item['due_on'] }}</td>
                                    <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">
                                        {{ $item['days_overdue'] > 0 ? $item['days_overdue'] : '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium tabular-nums text-gray-800 dark:text-gray-100">&euro; {{ $item['outstanding'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach

        @if ($receivables['total'] === '0.00')
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                {{ __('billing::management_accounting.aging.empty') }}
            </div>
        @endif
    </section>

    {{-- ------------------------------------------------------------ cash flow --}}
    <section class="space-y-3">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
            {{ __('billing::management_accounting.cash_flow.title') }}
        </h2>

        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
            <label class="block text-xs uppercase tracking-wide text-gray-400" for="openingBalance">
                {{ __('billing::management_accounting.cash_flow.opening_balance') }}
            </label>
            <input
                id="openingBalance"
                type="number"
                step="0.01"
                wire:model.live.debounce.500ms="openingBalance"
                class="mt-1 w-48 rounded-lg border-gray-300 text-sm tabular-nums dark:border-white/10 dark:bg-gray-800 dark:text-gray-100"
            />
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-400">{{ __('billing::management_accounting.cash_flow.closing_balance') }}</div>
                <div class="mt-1 text-xl font-semibold tabular-nums text-gray-900 dark:text-gray-100">&euro; {{ $cashFlow['closing'] }}</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-400">{{ __('billing::management_accounting.cash_flow.lowest_balance') }}</div>
                <div class="mt-1 text-xl font-semibold tabular-nums text-gray-900 dark:text-gray-100">&euro; {{ $cashFlow['lowest'] }}</div>
            </div>
            <div class="rounded-xl border p-4 {{ $cashFlow['first_shortfall'] ? 'border-danger-200 bg-danger-50 dark:border-danger-500/30 dark:bg-danger-500/10' : 'border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900' }}">
                <div class="text-xs uppercase tracking-wide {{ $cashFlow['first_shortfall'] ? 'text-danger-600 dark:text-danger-300' : 'text-gray-400' }}">
                    {{ __('billing::management_accounting.cash_flow.first_shortfall') }}
                </div>
                <div class="mt-1 text-xl font-semibold tabular-nums {{ $cashFlow['first_shortfall'] ? 'text-danger-700 dark:text-danger-200' : 'text-gray-500 dark:text-gray-400' }}">
                    {{ $cashFlow['first_shortfall'] ?? '—' }}
                </div>
            </div>
        </div>

        @if (count($cashFlow['entries']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-2">{{ __('billing::management_accounting.fields.due_on') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.cash_flow.inflow') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.cash_flow.outflow') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.cash_flow.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cashFlow['entries'] as $entry)
                            <tr class="border-b border-gray-50 dark:border-white/5 {{ $entry['shortfall'] ? 'bg-danger-50 dark:bg-danger-500/10' : '' }}">
                                <td class="px-4 py-2 tabular-nums text-gray-700 dark:text-gray-200">{{ $entry['date'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-success-700 dark:text-success-300">{{ $entry['inflow'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $entry['outflow'] }}</td>
                                <td class="px-4 py-2 text-right font-semibold tabular-nums {{ $entry['shortfall'] ? 'text-danger-700 dark:text-danger-200' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $entry['balance'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                {{ __('billing::management_accounting.cash_flow.no_shortfall') }}
            </div>
        @endif
    </section>

    {{-- --------------------------------------------------------- job margins --}}
    <section class="space-y-3">
        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
            {{ __('billing::management_accounting.margin.title') }}
        </h2>

        @if ($margins['uses_default_rate'])
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                {{ __('billing::management_accounting.margin.default_rate_warning', ['rate' => $margins['hourly_rate']]) }}
            </div>
        @else
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('billing::management_accounting.margin.rate_in_use', ['rate' => $margins['hourly_rate']]) }}
            </p>
        @endif

        @if (count($margins['jobs']) > 0)
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-2">{{ __('billing::billing.fields.work_order') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.margin.revenue') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.margin.labour_cost') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.margin.material_cost') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.margin.margin') }}</th>
                            <th class="px-4 py-2 text-right">{{ __('billing::management_accounting.margin.margin_percent') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($margins['jobs'] as $job)
                            <tr class="border-b border-gray-50 dark:border-white/5 {{ $job['loss'] ? 'bg-danger-50 dark:bg-danger-500/10' : '' }}">
                                <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $job['title'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $job['revenue'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $job['labour'] }}</td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">{{ $job['materials'] }}</td>
                                <td class="px-4 py-2 text-right font-semibold tabular-nums {{ $job['loss'] ? 'text-danger-700 dark:text-danger-200' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $job['margin'] }}
                                </td>
                                <td class="px-4 py-2 text-right tabular-nums text-gray-600 dark:text-gray-300">
                                    {{ $job['margin_percent'] === null ? __('billing::management_accounting.margin.no_revenue') : number_format($job['margin_percent'], 1, ',', '.') . '%' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-filament-panels::page>
