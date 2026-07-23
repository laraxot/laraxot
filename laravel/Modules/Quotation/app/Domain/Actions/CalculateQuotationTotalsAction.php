<?php

declare(strict_types=1);

namespace Modules\Quotation\Domain\Actions;

use Modules\Quotation\Domain\DTO\QuotationItemData;
use Modules\Quotation\Domain\DTO\QuotationTotals;
use Modules\Quotation\Domain\ValueObjects\Money;

final class CalculateQuotationTotalsAction
{
    /**
     * @param  array<int, QuotationItemData>  $items
     */
    public function execute(array $items, float $taxPercent): QuotationTotals
    {
        $subtotal = Money::zero();
        $discountAmount = Money::zero();
        $taxableAmount = Money::zero();

        $lineTotals = [];

        foreach ($items as $idx => $item) {
            $gross = Money::fromFloat($item->quantity * $item->unitPrice);
            $discounted = Money::fromFloat($gross->asFloat() * (1 - ($item->discountPercent / 100)));

            $subtotal = $subtotal->add($gross);
            $taxableAmount = $taxableAmount->add($discounted);
            $discountAmount = $discountAmount->add($gross->sub($discounted));

            $lineTotals[$idx] = $discounted;
        }

        $taxAmount = Money::fromFloat($taxableAmount->asFloat() * ($taxPercent / 100));
        $total = $taxableAmount->add($taxAmount);

        return new QuotationTotals(
            subtotal: $subtotal,
            discountAmount: $discountAmount,
            taxableAmount: $taxableAmount,
            taxPercent: $taxPercent,
            taxAmount: $taxAmount,
            total: $total,
            lineTotals: $lineTotals,
        );
    }
}
