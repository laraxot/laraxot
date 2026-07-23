<?php

declare(strict_types=1);

namespace Modules\Quotation\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

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
