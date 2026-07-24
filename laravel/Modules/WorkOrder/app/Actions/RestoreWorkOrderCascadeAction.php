<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Illuminate\Support\Facades\Event;
use Modules\WorkOrder\Domain\Events\WorkOrderRestored;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Spatie\QueueableAction\QueueableAction;

final class RestoreWorkOrderCascadeAction
{
    use QueueableAction;

    public function execute(WorkOrder $workOrder): void
    {
        if (! $workOrder->trashed()) {
            return;
        }

        $workOrder->restore();

        WorkOrderActivity::query()
            ->onlyTrashed()
            ->where('work_order_id', $workOrder->id)
            ->restore();

        Event::dispatch(new WorkOrderRestored((int) $workOrder->id));
    }
}
