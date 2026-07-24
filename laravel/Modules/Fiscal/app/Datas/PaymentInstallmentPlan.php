<?php

declare(strict_types=1);

namespace Modules\Fiscal\Datas;

use Modules\Quotation\DTO\Money;

final readonly class PaymentInstallmentPlan
{
    public function __construct(
        public string $dueDate,
        public Money $amount,
    ) {}
}
