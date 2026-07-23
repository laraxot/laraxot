<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

final readonly class CustomerSalesRow
{
    public function __construct(
        public string $customerName,
        public Money $total,
    ) {}
}
