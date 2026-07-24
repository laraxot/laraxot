<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;

/**
 * @extends Factory<WorkOrderStatusEvent>
 */
class WorkOrderStatusEventFactory extends Factory
{
    /** @var class-string<WorkOrderStatusEvent> */
    protected $model = WorkOrderStatusEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'from_status_id' => null,
            'to_status_id' => WorkOrderStatus::factory(),
            'changed_by_user_id' => null,
            'note' => null,
            'changed_at' => now(),
        ];
    }
}
