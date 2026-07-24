<?php

declare(strict_types=1);

namespace Modules\Fiscal\Datas;

final readonly class InvoiceLineData
{
    public function __construct(
        public float $quantity,
        public float $unitPrice,
        public float $discountPercent,
        public float $vatRatePercent,
    ) {}
}
