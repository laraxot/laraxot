<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use DateTimeImmutable;
use Modules\Quotation\DTO\Money;

/**
 * An invoice still carrying money: what is left to collect from a customer, or
 * left to pay a supplier. The same shape serves both sides, because ageing and
 * cash-flow arithmetic do not care which direction the money moves.
 */
final readonly class OpenItem
{
    public function __construct(
        public string $reference,
        public string $counterparty,
        public DateTimeImmutable $dueOn,
        public Money $outstanding,
    ) {}

    /**
     * Whole days the item is late. Zero on the due date itself, negative while
     * it is still in the future.
     */
    public function daysOverdue(DateTimeImmutable $asOf): int
    {
        $due = $this->dueOn->setTime(0, 0);
        $reference = $asOf->setTime(0, 0);

        return (int) $due->diff($reference)->format('%r%a');
    }

    public function isOverdue(DateTimeImmutable $asOf): bool
    {
        return $this->daysOverdue($asOf) > 0;
    }
}
