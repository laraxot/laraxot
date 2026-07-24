<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\DTO\QuotationItemData;
use Modules\Quotation\Models\Quotation;
use Spatie\QueueableAction\QueueableAction;

final class RecalculateQuotationTotalsAction
{
    use QueueableAction;

    public function __construct(
        private CalculateQuotationTotalsAction $calculator,
    ) {}

    public function execute(Quotation $quotation): Quotation
    {
        $items = [];

        foreach ($quotation->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            $items[] = new QuotationItemData(
                quantity: (float) $item->quantity,
                unitPrice: (float) $item->unit_price,
                discountPercent: (float) $item->discount_percent,
            );
        }

        $totals = $this->calculator->execute($items, (float) $quotation->tax_percent);

        $quotation->subtotal = $totals->subtotal->asDecimalString();
        $quotation->discount_amount = $totals->discountAmount->asDecimalString();
        $quotation->taxable_amount = $totals->taxableAmount->asDecimalString();
        $quotation->tax_amount = $totals->taxAmount->asDecimalString();
        $quotation->total = $totals->total->asDecimalString();
        $quotation->save();

        foreach ($quotation->items()->orderBy('sort_order')->orderBy('id')->get() as $idx => $item) {
            $item->line_total = $totals->lineTotals[$idx]->asDecimalString();
            $item->save();
        }

        return $quotation;
    }
}
