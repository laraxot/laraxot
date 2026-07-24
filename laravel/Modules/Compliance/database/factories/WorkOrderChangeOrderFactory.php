<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @extends Factory<WorkOrderChangeOrder>
 */
class WorkOrderChangeOrderFactory extends Factory
{
    /** @var class-string<WorkOrderChangeOrder> */
    protected $model = WorkOrderChangeOrder::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'requested_by_user_id' => null,
            'approved_by_user_id' => null,
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'estimated_amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['proposed', 'approved', 'executed', 'billed', 'rejected']),
            'approved_at' => null,
            'executed_at' => null,
            'billed_at' => null,
            'signature_media_id' => null,
            'meta' => null,
        ];
    }
}
