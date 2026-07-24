<?php

declare(strict_types=1);

namespace Modules\Fiscal\Datas;

use Modules\Quotation\DTO\Money;

final readonly class VatSummaryRow
{
    public function __construct(
        public string $rate,
        public Money $taxable,
        public Money $vat,
    ) {}
}
