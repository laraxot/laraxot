<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

final readonly class PaymentInstallmentPlan
{
    public function __construct(
        public string $dueDate,
        public Money $amount,
    ) {}
}
