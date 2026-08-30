<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use DateTimeImmutable;
use Modules\Quotation\DTO\Money;

/**
 * Where the bank balance goes over a horizon, given what is owed in both
 * directions. The point of the projection is not the closing figure but the
 * lowest point along the way: that is the day the money runs out.
 */
final readonly class CashFlowProjection
{
    /**
     * @param  list<CashFlowEntry>  $entries  Ordered by date, one per day on which money moves.
     */
    public function __construct(
        public Money $openingBalance,
        public array $entries,
    ) {}

    public function closingBalance(): Money
    {
        $lastKey = array_key_last($this->entries);

        return $lastKey === null ? $this->openingBalance : $this->entries[$lastKey]->balance;
    }

    /**
     * The worst the balance gets, opening included — nothing moving at all
     * still leaves today's balance as the low point.
     */
    public function lowestBalance(): Money
    {
        $lowest = $this->openingBalance;

        foreach ($this->entries as $entry) {
            if ($entry->balance->cents < $lowest->cents) {
                $lowest = $entry->balance;
            }
        }

        return $lowest;
    }

    /**
     * First day the balance goes negative, or null if it never does.
     */
    public function firstShortfallOn(): ?DateTimeImmutable
    {
        foreach ($this->entries as $entry) {
            if ($entry->isShortfall()) {
                return $entry->date;
            }
        }

        return null;
    }

    public function hasShortfall(): bool
    {
        return $this->firstShortfallOn() !== null;
    }
}
