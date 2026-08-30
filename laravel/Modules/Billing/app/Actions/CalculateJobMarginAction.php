<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Datas\JobMargin;
use Modules\Quotation\DTO\Money;

/**
 * Turns the amounts already recorded against a work order into its margin.
 *
 * Pure arithmetic on purpose: the numbers come from interventions, material
 * lines and supplier invoices, and each panel fetches its own. Keeping the
 * calculation free of persistence is also what makes it stable — job costing
 * does not change when tax rules do.
 */
final class CalculateJobMarginAction
{
    public function execute(
        int $workOrderId,
        string $workOrderTitle,
        float $revenue,
        float $labourCost,
        float $materialCost,
        float $externalCost,
    ): JobMargin {
        return new JobMargin(
            workOrderId: $workOrderId,
            workOrderTitle: $workOrderTitle,
            revenue: Money::fromFloat($revenue),
            labourCost: Money::fromFloat($labourCost),
            materialCost: Money::fromFloat($materialCost),
            externalCost: Money::fromFloat($externalCost),
        );
    }

    /**
     * Sorts jobs worst margin first, so the ones bleeding money surface without
     * anyone having to look for them.
     *
     * @param  list<JobMargin>  $margins
     * @return list<JobMargin>
     */
    public function worstFirst(array $margins): array
    {
        usort(
            $margins,
            static fn (JobMargin $a, JobMargin $b): int => $a->margin()->cents <=> $b->margin()->cents,
        );

        return $margins;
    }
}
