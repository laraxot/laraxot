<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrderAssignment;
use Spatie\QueueableAction\QueueableAction;

final class UnassignUserFromWorkOrderAction
{
    use QueueableAction;

    public function execute(int $workOrderId, int|string $userId): void
    {
        WorkOrderAssignment::query()
            ->where('work_order_id', $workOrderId)
            ->where('user_id', $userId)
            ->delete();
    }
}
