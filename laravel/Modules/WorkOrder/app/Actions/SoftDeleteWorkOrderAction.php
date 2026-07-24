<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;

final class SoftDeleteWorkOrderAction
{
    use QueueableAction;

    public function execute(WorkOrder $workOrder): void
    {
        $workOrder->delete();
    }
}
