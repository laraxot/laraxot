<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;

final class CreateWorkOrderAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): WorkOrder
    {
        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->create($data);

        return $workOrder;
    }
}

