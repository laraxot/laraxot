<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;
use Spatie\QueueableAction\QueueableAction;

final class ChangeWorkOrderStatusAction
{
    use QueueableAction;

    public function execute(
        WorkOrder $workOrder,
        int $toStatusId,
        int|string|null $changedByUserId = null,
        ?string $note = null,
        ?CarbonImmutable $changedAt = null,
        bool $alsoAddActivity = true,
    ): WorkOrderStatusEvent {
        /** @var WorkOrderStatus $toStatus */
        $toStatus = WorkOrderStatus::query()->findOrFail($toStatusId);

        $changedAt = $changedAt ?? CarbonImmutable::now();
        $fromStatusId = $workOrder->status_id;

        $workOrder->forceFill([
            'status_id' => $toStatus->id,
        ])->save();

        /** @var WorkOrderStatusEvent $event */
        $event = WorkOrderStatusEvent::query()->create([
            'work_order_id' => $workOrder->id,
            'from_status_id' => $fromStatusId,
            'to_status_id' => $toStatus->id,
            'changed_by_user_id' => $changedByUserId,
            'note' => $note,
            'changed_at' => $changedAt,
        ]);

        if ($alsoAddActivity) {
            $fromStatus = $event->fromStatus;
            $toStatusRelation = $event->toStatus;

            $fromName = $fromStatus instanceof WorkOrderStatus ? $fromStatus->name : '-';
            $toName = $toStatusRelation instanceof WorkOrderStatus ? $toStatusRelation->name : $toStatus->name;

            WorkOrderActivity::query()->create([
                'work_order_id' => $workOrder->id,
                'created_by_user_id' => $changedByUserId,
                'type' => 'status',
                'body' => sprintf('Status changed: %s → %s', $fromName, $toName),
                'occurred_at' => $changedAt,
            ]);
        }

        return $event;
    }
}
