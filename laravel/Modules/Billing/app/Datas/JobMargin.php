<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use Modules\Quotation\DTO\Money;

/**
 * What a single work order earned against what it cost to run.
 *
 * Costs are kept split because the three behave differently when a job goes
 * wrong: labour overruns, materials get over-ordered, and subcontractors
 * invoice late. A single total would hide which of the three happened.
 */
final readonly class JobMargin
{
    public function __construct(
        public int $workOrderId,
        public string $workOrderTitle,
        public Money $revenue,
        public Money $labourCost,
        public Money $materialCost,
        public Money $externalCost,
    ) {}

    public function totalCost(): Money
    {
        return $this->labourCost
            ->add($this->materialCost)
            ->add($this->externalCost);
    }

    public function margin(): Money
    {
        return $this->revenue->sub($this->totalCost());
    }

    /**
     * Margin as a share of revenue. Null when there is no revenue yet: the job
     * has costs but nothing to measure them against, and a percentage would
     * invent a number.
     */
    public function marginPercent(): ?float
    {
        if ($this->revenue->cents === 0) {
            return null;
        }

        return round(($this->margin()->cents / $this->revenue->cents) * 100, 2);
    }

    public function isLoss(): bool
    {
        return $this->margin()->cents < 0;
    }
}
