<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Domain\Events;

final readonly class WorkOrderRestored
{
    public function __construct(public int $workOrderId)
    {
    }
}

