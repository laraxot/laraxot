<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @extends Factory<Intervention>
 */
class InterventionFactory extends Factory
{
    /** @var class-string<Intervention> */
    protected $model = Intervention::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'customer_id' => null,
            'performed_by_user_id' => 1,
            'scheduled_at' => now()->addDay(),
            'started_at' => null,
            'ended_at' => null,
            'work_minutes' => 0,
            'travel_minutes' => 0,
            'notes' => $this->faker->optional()->sentence(),
            'outcome_code' => null,
        ];
    }
}
