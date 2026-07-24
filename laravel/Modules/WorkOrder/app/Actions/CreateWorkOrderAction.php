<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;

final class CreateWorkOrderAction
{
    use QueueableAction;

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
