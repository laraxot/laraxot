<?php

declare(strict_types=1);

namespace Modules\Intervention\Listeners;

use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Domain\Events\WorkOrderRestored;

final class RestoreInterventionsForWorkOrderListener
{
    public function handle(WorkOrderRestored $event): void
    {
        Intervention::query()
            ->onlyTrashed()
            ->where('work_order_id', $event->workOrderId)
            ->restore();
    }
}
