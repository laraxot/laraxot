<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Modules\Fiscal\Actions\CalculateInvoiceTotalsAction;
use Modules\Fiscal\Datas\InvoiceLineData;
use Modules\TimberBilling\Models\TimberInvoice;
use Spatie\QueueableAction\QueueableAction;

/**
 * Recomputes each line total and the invoice fiscal totals (imponibile, IVA,
 * totale) using the pure Domain calculator, then persists them.
 */
final class RecalculateTimberInvoiceTotalsAction
{
    use QueueableAction;

    public function __construct(
        private readonly CalculateInvoiceTotalsAction $calculator,
    ) {}

    public function execute(TimberInvoice $invoice): TimberInvoice
    {
        $lines = $invoice->lines()->orderBy('id')->get()->values();

        $data = $lines
            ->map(fn ($line): InvoiceLineData => new InvoiceLineData(
                quantity: (float) $line->quantity,
                unitPrice: (float) $line->unit_price,
                discountPercent: (float) $line->discount_percent,
                vatRatePercent: (float) $line->vat_rate,
            ))
            ->all();

        $totals = $this->calculator->execute($data);

        foreach ($lines as $index => $line) {
            $lineTotal = $totals->lineTotals[$index] ?? null;
            if ($lineTotal !== null) {
                $line->forceFill(['line_total' => $lineTotal->asFloat()])->save();
            }
        }

        $invoice->forceFill([
            'taxable_amount' => $totals->taxable->asFloat(),
            'vat_amount' => $totals->vat->asFloat(),
            'total_amount' => $totals->total->asFloat(),
        ])->save();

        return $invoice;
    }
}
