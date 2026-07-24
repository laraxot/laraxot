<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Modules\WorkOrder\Models\WorkOrderAssignment;
use Spatie\QueueableAction\QueueableAction;

final class AssignUserToWorkOrderAction
{
    use QueueableAction;

    public function execute(
        int $workOrderId,
        int $userId,
        ?string $role = null,
        ?int $assignedByUserId = null,
        ?CarbonImmutable $assignedAt = null,
    ): WorkOrderAssignment {
        $assignedAt = $assignedAt ?? CarbonImmutable::now();

        /** @var WorkOrderAssignment $assignment */
        $assignment = WorkOrderAssignment::query()->updateOrCreate(
            [
                'work_order_id' => $workOrderId,
                'user_id' => $userId,
            ],
            [
                'role' => $role,
                'assigned_by_user_id' => $assignedByUserId,
                'assigned_at' => $assignedAt,
            ],
        );

        return $assignment;
    }
}
