<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

final readonly class VatSummaryRow
{
    public function __construct(
        public string $rate,
        public Money $taxable,
        public Money $vat,
    ) {}
}
