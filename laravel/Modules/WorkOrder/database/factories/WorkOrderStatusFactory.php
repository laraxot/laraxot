<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WorkOrder\Models\WorkOrderStatus;

/**
 * @extends Factory<WorkOrderStatus>
 */
class WorkOrderStatusFactory extends Factory
{
    /** @var class-string<WorkOrderStatus> */
    protected $model = WorkOrderStatus::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->slug(2),
            'name' => $this->faker->words(2, true),
            'color' => $this->faker->optional()->hexColor(),
            'is_terminal' => false,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
