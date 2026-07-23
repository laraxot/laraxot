<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrderAssignment;

final class UnassignUserFromWorkOrderAction
{
    public function execute(int $workOrderId, int $userId): void
    {
        WorkOrderAssignment::query()
            ->where('work_order_id', $workOrderId)
            ->where('user_id', $userId)
            ->delete();
    }
}

