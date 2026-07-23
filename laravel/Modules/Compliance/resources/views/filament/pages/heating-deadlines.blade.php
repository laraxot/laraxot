<x-filament-panels::page>
    @php($rows = $this->rows())
    @php($labels = [
        'overdue' => ['t' => __('heating.status.overdue'), 'c' => 'bg-danger-100 text-danger-700 dark:bg-danger-500/20 dark:text-danger-300'],
        'expiring' => ['t' => __('heating.status.expiring'), 'c' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300'],
        'ok' => ['t' => __('heating.status.ok'), 'c' => 'bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-300'],
        'unknown' => ['t' => __('heating.status.unknown'), 'c' => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300'],
    ])

    @if (count($rows) === 0)
        <div class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500 dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
            {{ __('heating.deadlines.empty') }}
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-100 text-xs uppercase tracking-wide text-gray-400 dark:border-white/10">
                    <tr>
                        <th class="px-4 py-2">{{ __('heating.fields.customer') }}</th>
                        <th class="px-4 py-2">{{ __('heating.system') }}</th>
                        <th class="px-4 py-2">{{ __('heating.fields.boilers') }}</th>
                        <th class="px-4 py-2">{{ __('heating.fields.next_maintenance') }}</th>
                        <th class="px-4 py-2">{{ __('heating.fields.next_efficiency') }}</th>
                        <th class="px-4 py-2">{{ __('heating.fields.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr class="border-b border-gray-50 dark:border-white/5">
                            <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-100">{{ $row['customer'] }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $row['system'] }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $row['boiler'] }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $row['maintenance_due_on'] ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $row['efficiency_due_on'] ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase {{ $labels[$row['status']]['c'] }}">
                                    {{ $labels[$row['status']]['t'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament-panels::page>
