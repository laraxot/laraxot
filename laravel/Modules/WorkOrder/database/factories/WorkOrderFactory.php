<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @extends Factory<WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /** @var class-string<WorkOrder> */
    protected $model = WorkOrder::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'WO-'.$this->faker->unique()->numerify('####'),
            'customer_id' => Customer::factory(),
            'customer_site_id' => null,
            'primary_contact_id' => null,
            'status_id' => null,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'notes' => null,
            'opened_at' => now(),
        ];
    }
}
