<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;

final class SoftDeleteWorkOrderAction
{
    public function execute(WorkOrder $workOrder): void
    {
        $workOrder->delete();
    }
}

