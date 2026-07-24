<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderAssignment;

/**
 * @extends Factory<WorkOrderAssignment>
 */
class WorkOrderAssignmentFactory extends Factory
{
    /** @var class-string<WorkOrderAssignment> */
    protected $model = WorkOrderAssignment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'user_id' => 1,
            'role' => 'technician',
        ];
    }
}
