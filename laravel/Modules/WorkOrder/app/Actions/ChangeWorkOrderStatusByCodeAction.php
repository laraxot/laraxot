<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;

final class ChangeWorkOrderStatusByCodeAction
{
    public function execute(int $workOrderId, string $toStatusCode, ?int $changedByUserId = null, ?string $note = null): void
    {
        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);

        /** @var WorkOrderStatus $toStatus */
        $toStatus = WorkOrderStatus::query()->where('code', $toStatusCode)->firstOrFail();

        $fromStatusId = $workOrder->status_id;

        $workOrder->forceFill([
            'status_id' => $toStatus->id,
        ])->save();

        WorkOrderStatusEvent::query()->create([
            'work_order_id' => $workOrder->id,
            'from_status_id' => $fromStatusId,
            'to_status_id' => $toStatus->id,
            'changed_by_user_id' => $changedByUserId,
            'note' => $note,
            'changed_at' => CarbonImmutable::now(),
        ]);
    }
}

