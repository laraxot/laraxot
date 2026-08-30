<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

/**
 * Margins for a set of jobs, together with the labour rate they were costed at.
 *
 * The rate travels with the figures on purpose. An unset rate makes every job
 * look profitable, because labour simply costs nothing — so the reader has to
 * be able to tell "this job earns well" from "nobody has entered a cost yet".
 */
final readonly class JobMarginReport
{
    /**
     * @param  list<JobMargin>  $margins
     */
    public function __construct(
        public array $margins,
        public int $hourlyLabourCostCents,
        public bool $usesDefaultRate = false,
    ) {}

    public function isLabourCostConfigured(): bool
    {
        return ! $this->usesDefaultRate;
    }

    public function isEmpty(): bool
    {
        return $this->margins === [];
    }

    /**
     * @return list<JobMargin>
     */
    public function losingJobs(): array
    {
        return array_values(array_filter(
            $this->margins,
            static fn (JobMargin $margin): bool => $margin->isLoss(),
        ));
    }
}
