{{--
    Proposta di fattura per una commessa.

    Il blocco degli extra non decisi e' volutamente il primo e il piu' visibile:
    emettere la fattura lasciandoli "da decidere" significa regalarli, e dopo
    l'emissione non si torna indietro.
--}}
@php
    /** @var \Modules\Billing\Datas\PreInvoiceDraft $draft */
    $money = fn (float $amount): string => '€ ' . number_format($amount, 2, ',', '.');
    $sourceClasses = [
        'labor' => 'bg-primary-50 text-primary-700 ring-primary-600/20 dark:bg-primary-500/10 dark:text-primary-300 dark:ring-primary-400/30',
        'callout' => 'bg-info-50 text-info-700 ring-info-600/20 dark:bg-info-500/10 dark:text-info-300 dark:ring-info-400/30',
        'material' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-300 dark:ring-gray-400/30',
        'extra' => 'bg-warning-50 text-warning-700 ring-warning-600/20 dark:bg-warning-500/10 dark:text-warning-300 dark:ring-warning-400/30',
    ];
@endphp

<div class="space-y-4">
    @if ($draft->hasUndecidedExtras())
        <div class="rounded-lg bg-danger-50 p-4 ring-1 ring-danger-600/20 dark:bg-danger-500/10 dark:ring-danger-400/30">
            <p class="flex items-center gap-2 font-semibold text-danger-800 dark:text-danger-200">
                <x-filament::icon icon="heroicon-o-exclamation-triangle" class="h-5 w-5" />
                {{ __('billing.pre_invoice.undecided_title') }}
            </p>
            <p class="mt-1 text-sm text-danger-700 dark:text-danger-300">
                {{ __('billing.pre_invoice.undecided_body', ['amount' => $money($draft->pendingExtraTotal)]) }}
            </p>
        </div>
    @endif

    @if (count($draft->lines) === 0)
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('billing.pre_invoice.empty') }}
        </p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[32rem] text-sm">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr class="border-b border-gray-200 dark:border-white/10">
                        <th class="py-2 pr-3">{{ __('billing.pre_invoice.source') }}</th>
                        <th class="py-2 pr-3">{{ __('billing.pre_invoice.description') }}</th>
                        <th class="py-2 text-right">{{ __('billing.pre_invoice.amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($draft->lines as $line)
                        <tr>
                            <td class="py-2 pr-3 align-top">
                                <span @class([
                                    'inline-flex whitespace-nowrap rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                    $sourceClasses[$line->sourceCode] ?? $sourceClasses['material'],
                                ])>
                                    {{ __('billing.pre_invoice.sources.' . $line->sourceCode) }}
                                </span>
                            </td>
                            <td class="py-2 pr-3 align-top text-gray-900 dark:text-white">
                                {{ $line->description }}
                            </td>
                            <td class="py-2 text-right align-top tabular-nums text-gray-900 dark:text-white">
                                {{ $money($line->amount) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-300 dark:border-white/20">
                        <td class="py-3 pr-3 font-semibold text-gray-900 dark:text-white" colspan="2">
                            {{ __('billing.pre_invoice.total') }}
                        </td>
                        <td class="py-3 text-right text-base font-bold tabular-nums text-gray-900 dark:text-white">
                            {{ $money($draft->total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
