<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;

final class GetWorkOrderDetailsAction
{
    public function execute(int $workOrderId): WorkOrder
    {
        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()
            ->with(['customer', 'site', 'primaryContact', 'status', 'activities', 'assignments', 'statusEvents'])
            ->findOrFail($workOrderId);

        return $workOrder;
    }
}

