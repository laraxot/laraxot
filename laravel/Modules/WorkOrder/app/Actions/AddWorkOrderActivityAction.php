<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Spatie\QueueableAction\QueueableAction;

final class AddWorkOrderActivityAction
{
    use QueueableAction;

    public function execute(int $workOrderId, string $type, string $body, ?int $createdByUserId = null, ?CarbonImmutable $occurredAt = null): WorkOrderActivity
    {
        $occurredAt = $occurredAt ?? CarbonImmutable::now();

        /** @var WorkOrderActivity $activity */
        $activity = WorkOrderActivity::query()->create([
            'work_order_id' => $workOrderId,
            'created_by_user_id' => $createdByUserId,
            'type' => $type,
            'body' => $body,
            'occurred_at' => $occurredAt,
        ]);

        return $activity;
    }
}
