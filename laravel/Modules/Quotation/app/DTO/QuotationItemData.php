<?php

declare(strict_types=1);

namespace Modules\Quotation\DTO;

final readonly class QuotationItemData
{
    public function __construct(
        public float $quantity,
        public float $unitPrice,
        public float $discountPercent,
    ) {}
}
