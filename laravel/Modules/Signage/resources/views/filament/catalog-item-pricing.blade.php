@php
    /** @var \Modules\Signage\Datas\SignCatalogItemPrice $price */
    $money = fn (?float $v): string => $v === null ? '—' : '€ ' . number_format($v, 2, ',', '.');
    $percent = fn (?float $v): string => $v === null ? '—' : number_format($v, 2, ',', '.') . '%';
@endphp

<div class="space-y-4">
    @if ($price->isManualPrice)
        <p class="text-sm text-warning-600 dark:text-warning-400">
            {{ __('signage::signage.pricing.manual_notice') }}
        </p>
    @endif

    @if ($price->lines === [])
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.empty') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-gray-500 dark:text-gray-400">
                    <tr class="border-b border-gray-200 dark:border-white/10">
                        <th class="py-2 pr-3 text-left font-medium">{{ __('signage::signage.catalog_fields.position') }}</th>
                        <th class="py-2 pr-3 text-left font-medium">{{ __('signage::signage.catalog_fields.work_center_code') }}</th>
                        <th class="py-2 pr-3 text-left font-medium">{{ __('signage::signage.fields.supplier_name') }}</th>
                        <th class="py-2 pr-3 text-left font-medium">{{ __('signage::signage.catalog_fields.description') }}</th>
                        <th class="py-2 pr-3 text-left font-medium">{{ __('signage::signage.fields.unit_code') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.catalog_fields.quantity') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.catalog_fields.width_mm') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.catalog_fields.height_mm') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.fields.unit_cost') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.catalog_fields.line_cost') }}</th>
                        <th class="py-2 pr-3 text-right font-medium">{{ __('signage::signage.catalog_fields.markup_percent') }}</th>
                        <th class="py-2 text-right font-medium">{{ __('signage::signage.catalog_fields.line_price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($price->lines as $line)
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <td class="py-2 pr-3 text-gray-500 dark:text-gray-400">{{ $line->position }}</td>
                            <td class="py-2 pr-3">{{ $line->workCenterCode }}</td>
                            <td class="py-2 pr-3 text-gray-500 dark:text-gray-400">
                                {{ $line->supplierName ?? '—' }}
                                @if ($line->supplierItemCode)
                                    <span class="block text-xs">{{ $line->supplierItemCode }}</span>
                                @endif
                            </td>
                            <td class="py-2 pr-3">{{ $line->description }}</td>
                            <td class="py-2 pr-3">{{ $line->unitCode }}</td>
                            <td class="py-2 pr-3 text-right">{{ number_format($line->quantity, 3, ',', '.') }}</td>
                            <td class="py-2 pr-3 text-right">{{ $line->widthMm === null ? '—' : number_format($line->widthMm, 0, ',', '.') }}</td>
                            <td class="py-2 pr-3 text-right">{{ $line->heightMm === null ? '—' : number_format($line->heightMm, 0, ',', '.') }}</td>
                            <td class="py-2 pr-3 text-right">{{ number_format($line->unitCost, 4, ',', '.') }}</td>
                            <td class="py-2 pr-3 text-right">{{ $money($line->lineCost) }}</td>
                            <td class="py-2 pr-3 text-right">
                                {{ $percent($line->markupPercent) }}
                                <span class="block text-xs text-gray-400">{{ __('signage::signage.markup_sources.' . $line->markupSourceCode) }}</span>
                            </td>
                            <td class="py-2 text-right font-medium">{{ $money($line->linePrice) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        <div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.total_cost') }}</div>
            <div class="text-base font-semibold text-danger-600 dark:text-danger-400">{{ $money($price->totalCost) }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.sale_price') }}</div>
            <div class="text-base font-semibold">{{ $money($price->salePrice) }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.margin') }}</div>
            <div class="text-base font-semibold text-success-600 dark:text-success-400">{{ $money($price->margin()) }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.margin_percent') }}</div>
            <div class="text-base font-semibold">{{ $percent($price->marginPercent()) }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('signage::signage.pricing.markup_on_cost_percent') }}</div>
            <div class="text-base font-semibold">{{ $percent($price->markupOnCostPercent()) }}</div>
        </div>
    </div>
</div>
