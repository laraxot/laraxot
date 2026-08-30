<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use DateTimeImmutable;
use Modules\Quotation\DTO\Money;

/**
 * One dated step of a cash-flow projection: what comes in, what goes out, and
 * where the bank balance lands once both have happened.
 */
final readonly class CashFlowEntry
{
    public function __construct(
        public DateTimeImmutable $date,
        public Money $inflow,
        public Money $outflow,
        public Money $balance,
    ) {}

    public function net(): Money
    {
        return $this->inflow->sub($this->outflow);
    }

    public function isShortfall(): bool
    {
        return $this->balance->cents < 0;
    }
}
