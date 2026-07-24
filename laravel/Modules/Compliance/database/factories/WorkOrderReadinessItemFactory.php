<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\WorkOrderReadinessItem;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @extends Factory<WorkOrderReadinessItem>
 */
class WorkOrderReadinessItemFactory extends Factory
{
    /** @var class-string<WorkOrderReadinessItem> */
    protected $model = WorkOrderReadinessItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'code' => $this->faker->unique()->slug(2),
            'label' => $this->faker->sentence(3),
            'severity' => $this->faker->randomElement(['info', 'warning', 'critical']),
            'status' => $this->faker->randomElement(['missing', 'satisfied', 'expired']),
            'message' => $this->faker->optional()->sentence(),
            'source_type' => null,
            'source_id' => null,
            'due_on' => $this->faker->optional()->dateTimeBetween('now', '+3 months'),
            'meta' => null,
        ];
    }
}
