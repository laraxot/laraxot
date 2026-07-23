<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\Domain\Actions\CalculateQuotationTotalsAction;
use Modules\Quotation\Domain\DTO\QuotationItemData;
use Modules\Quotation\Models\Quotation;

final readonly class RecalculateQuotationTotalsAction
{
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

        $quotation->subtotal = (float) $totals->subtotal->asDecimalString();
        $quotation->discount_amount = (float) $totals->discountAmount->asDecimalString();
        $quotation->taxable_amount = (float) $totals->taxableAmount->asDecimalString();
        $quotation->tax_amount = (float) $totals->taxAmount->asDecimalString();
        $quotation->total = (float) $totals->total->asDecimalString();
        $quotation->save();

        foreach ($quotation->items()->orderBy('sort_order')->orderBy('id')->get() as $idx => $item) {
            $item->line_total = (float) $totals->lineTotals[$idx]->asDecimalString();
            $item->save();
        }

        return $quotation;
    }
}
