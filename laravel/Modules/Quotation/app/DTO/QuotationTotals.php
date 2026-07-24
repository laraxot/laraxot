<?php

declare(strict_types=1);

namespace Modules\Quotation\DTO;

final readonly class QuotationTotals
{
    public function __construct(
        public Money $subtotal,
        public Money $discountAmount,
        public Money $taxableAmount,
        public float $taxPercent,
        public Money $taxAmount,
        public Money $total,
        /** @var array<int, Money> */
        public array $lineTotals,
    ) {}
}
