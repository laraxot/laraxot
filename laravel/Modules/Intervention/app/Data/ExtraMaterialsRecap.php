<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * What was used beyond the quotation in a period, split by what was decided
 * about it.
 *
 * The three buckets exist because "extra material" is not yet money: it becomes
 * money only when somebody says it should be billed. Reporting only the total
 * would flatter the number; reporting `waived` separately is the part that
 * actually changes behaviour, because it is what the company gave away.
 */
final readonly class ExtraMaterialsRecap
{
    public function __construct(
        public float $toBill = 0.0,
        public float $pending = 0.0,
        public float $waived = 0.0,
        public int $lineCount = 0,
        public int $issueCount = 0,
    ) {}

    /** Everything found outside the quotation, whatever was decided about it. */
    public function intercepted(): float
    {
        return round($this->toBill + $this->pending + $this->waived, 2);
    }

    /** Still waiting for someone to decide whether to bill it or gift it. */
    public function hasUndecided(): bool
    {
        return $this->pending > 0.0;
    }
}
