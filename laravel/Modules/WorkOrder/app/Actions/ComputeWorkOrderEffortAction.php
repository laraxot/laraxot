<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Domain\DTO\WorkOrderEffort;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Calcola il consuntivo di una commessa sommando i minuti degli interventi e lo
 * confronta con i minuti previsti.
 */
final class ComputeWorkOrderEffortAction
{
    public function execute(WorkOrder $workOrder): WorkOrderEffort
    {
        $actualMinutes = (int) $workOrder->interventions()->sum('work_minutes');

        return new WorkOrderEffort(
            plannedMinutes: $workOrder->planned_minutes,
            actualMinutes: $actualMinutes,
        );
    }
}
