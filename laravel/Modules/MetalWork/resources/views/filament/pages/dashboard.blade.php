<x-filament-panels::page>
    @php
        $money = static fn (?float $value): string => $value === null ? '—' : number_format($value, 2, ',', '.') . ' €';
        $cardClass = 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900';
        $thClass = 'px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400';
        $tdClass = 'px-3 py-2 text-sm text-slate-700 dark:text-slate-200';
    @endphp

    <div class="space-y-6">
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                'drawings' => __('metalwork::metalwork.drawings'),
                'estimates' => __('metalwork::metalwork.part_estimates'),
                'materials' => __('metalwork::metalwork.raw_materials'),
                'prices' => __('metalwork::metalwork.supplier_prices'),
            ] as $key => $label)
                <div class="{{ $cardClass }}">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</div>
                    <div class="mt-1 text-3xl font-semibold text-slate-950 dark:text-slate-100">{{ $stats[$key] }}</div>
                </div>
            @endforeach
        </div>

        @if ($lastPriceUpdate !== null)
            <p class="text-xs text-slate-500 dark:text-slate-400">
                {{ __('metalwork::metalwork.panel.material_prices') }}: {{ $lastPriceUpdate }}
            </p>
        @endif

        @if ($estimates->isEmpty() && $drawings->isEmpty())
            <div class="{{ $cardClass }} text-sm text-slate-500 dark:text-slate-400">
                {{ __('metalwork::metalwork.panel.no_data') }}
            </div>
        @endif

        @if ($estimates->isNotEmpty())
            <div class="{{ $cardClass }}">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ __('metalwork::metalwork.panel.latest_estimates') }}</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                        <thead>
                            <tr>
                                <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.part_code') }}</th>
                                <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.name') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.quantity') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.gross_weight_kg') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.total_cost') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.sale_price') }}</th>
                                <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            @foreach ($estimates as $estimate)
                                <tr>
                                    <td class="{{ $tdClass }} font-semibold">{{ $estimate->part_code }}</td>
                                    <td class="{{ $tdClass }}">{{ \Illuminate\Support\Str::limit($estimate->name, 45) }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $estimate->quantity }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ number_format((float) $estimate->gross_weight_kg, 2, ',', '.') }}</td>
                                    <td class="{{ $tdClass }} text-right">{{ $money((float) $estimate->total_cost) }}</td>
                                    <td class="{{ $tdClass }} text-right font-semibold">{{ $money((float) $estimate->sale_price) }}</td>
                                    <td class="{{ $tdClass }} text-xs">{{ $estimate->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($drawings->isNotEmpty())
            <div class="{{ $cardClass }}">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ __('metalwork::metalwork.panel.latest_drawings') }}</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($drawings as $item)
                        <div class="rounded-xl border border-slate-300 px-3 py-2 text-xs dark:border-slate-700">
                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $item->part_code }}</span>
                            <span class="block opacity-70">{{ \Illuminate\Support\Str::limit($item->name, 40) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
