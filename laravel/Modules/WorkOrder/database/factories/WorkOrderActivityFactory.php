<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;

/**
 * @extends Factory<WorkOrderActivity>
 */
class WorkOrderActivityFactory extends Factory
{
    /** @var class-string<WorkOrderActivity> */
    protected $model = WorkOrderActivity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'created_by_user_id' => null,
            'type' => 'note',
            'body' => $this->faker->sentence(),
            'occurred_at' => now(),
        ];
    }
}
