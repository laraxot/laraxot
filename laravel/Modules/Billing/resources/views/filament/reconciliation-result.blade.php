{{--
    Esito della conciliazione fattura fornitore / DDT.
    Le differenze sono mostrate per tipo, con quantita' e prezzi affiancati:
    l'operatore deve poter dire al fornitore esattamente cosa non torna.
--}}
@php
    /** @var \Modules\Billing\Models\SupplierInvoice $invoice */
    /** @var array<int, \Modules\Billing\Datas\ReconciliationDiscrepancy> $discrepancies */
    $statusCode = (string) $invoice->status_code;
    $overcharge = collect($discrepancies)
        ->filter(fn ($discrepancy) => $discrepancy->amountDifference > 0)
        ->sum(fn ($discrepancy) => $discrepancy->amountDifference);
    $typeClasses = [
        'not_delivered' => 'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-500/10 dark:text-danger-300 dark:ring-danger-400/30',
        'quantity_over' => 'bg-danger-50 text-danger-700 ring-danger-600/20 dark:bg-danger-500/10 dark:text-danger-300 dark:ring-danger-400/30',
        'price_mismatch' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-500/10 dark:text-warning-300 dark:ring-warning-400/30',
        'not_invoiced' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-300 dark:ring-gray-400/30',
    ];
@endphp

<div class="space-y-4">
    <div @class([
        'rounded-lg p-4 ring-1',
        'bg-success-50 text-success-800 ring-success-600/20 dark:bg-success-500/10 dark:text-success-200 dark:ring-success-400/30' => $statusCode === 'reconciled',
        'bg-danger-50 text-danger-800 ring-danger-600/20 dark:bg-danger-500/10 dark:text-danger-200 dark:ring-danger-400/30' => $statusCode !== 'reconciled',
    ])>
        <p class="font-semibold">
            {{ $statusCode === 'reconciled'
                ? __('billing::suppliers.reconciliation.clean_title')
                : __('billing::suppliers.reconciliation.disputed_title') }}
        </p>
        <p class="mt-1 text-sm">
            {{ $statusCode === 'reconciled'
                ? __('billing::suppliers.reconciliation.clean_body')
                : __('billing::suppliers.reconciliation.disputed_body', ['count' => count($discrepancies)]) }}
        </p>
    </div>

    @if (count($discrepancies) > 0)
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('billing::suppliers.reconciliation.summary', [
                'count' => count($discrepancies),
                'amount' => '€ ' . number_format((float) $overcharge, 2, ',', '.'),
            ]) }}
        </p>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[52rem] text-sm">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr class="border-b border-gray-200 dark:border-white/10">
                        <th class="py-2 pr-3">{{ __('billing::suppliers.reconciliation.columns.type') }}</th>
                        <th class="py-2 pr-3">{{ __('billing::suppliers.reconciliation.columns.description') }}</th>
                        <th class="py-2 pr-3 text-right">{{ __('billing::suppliers.reconciliation.columns.delivered_quantity') }}</th>
                        <th class="py-2 pr-3 text-right">{{ __('billing::suppliers.reconciliation.columns.invoiced_quantity') }}</th>
                        <th class="py-2 pr-3 text-right">{{ __('billing::suppliers.reconciliation.columns.delivered_unit_price') }}</th>
                        <th class="py-2 pr-3 text-right">{{ __('billing::suppliers.reconciliation.columns.invoiced_unit_price') }}</th>
                        <th class="py-2 text-right">{{ __('billing::suppliers.reconciliation.columns.amount_difference') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($discrepancies as $discrepancy)
                        <tr>
                            <td class="py-2 pr-3 align-top">
                                <span @class([
                                    'inline-flex whitespace-nowrap rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                    $typeClasses[$discrepancy->typeCode] ?? $typeClasses['not_invoiced'],
                                ])>
                                    {{ __('billing::suppliers.reconciliation.types.' . $discrepancy->typeCode) }}
                                </span>
                                <p class="mt-1 max-w-[16rem] text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('billing::suppliers.reconciliation.explanations.' . $discrepancy->typeCode) }}
                                </p>
                            </td>
                            <td class="py-2 pr-3 align-top font-medium text-gray-900 dark:text-white">
                                {{ $discrepancy->description }}
                            </td>
                            <td class="py-2 pr-3 text-right align-top tabular-nums">
                                {{ number_format($discrepancy->deliveredQuantity, 2, ',', '.') }}
                            </td>
                            <td class="py-2 pr-3 text-right align-top tabular-nums">
                                {{ number_format($discrepancy->invoicedQuantity, 2, ',', '.') }}
                            </td>
                            <td class="py-2 pr-3 text-right align-top tabular-nums">
                                € {{ number_format($discrepancy->deliveredUnitPrice, 2, ',', '.') }}
                            </td>
                            <td class="py-2 pr-3 text-right align-top tabular-nums">
                                € {{ number_format($discrepancy->invoicedUnitPrice, 2, ',', '.') }}
                            </td>
                            <td @class([
                                'py-2 text-right align-top font-semibold tabular-nums',
                                'text-danger-600 dark:text-danger-400' => $discrepancy->amountDifference > 0,
                                'text-gray-500 dark:text-gray-400' => $discrepancy->amountDifference <= 0,
                            ])>
                                € {{ number_format($discrepancy->amountDifference, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
