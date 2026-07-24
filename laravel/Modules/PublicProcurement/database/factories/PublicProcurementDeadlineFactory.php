<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementDeadline;

/**
 * @extends Factory<PublicProcurementDeadline>
 */
class PublicProcurementDeadlineFactory extends Factory
{
    /** @var class-string<PublicProcurementDeadline> */
    protected $model = PublicProcurementDeadline::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['open', 'completed', 'overdue']);

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'type' => $this->faker->randomElement(['site_visit', 'submission', 'document', 'milestone']),
            'title' => $this->faker->sentence(4),
            'status' => $status,
            'due_at' => $this->faker->dateTimeBetween('-1 month', '+2 months'),
            'completed_at' => 'completed' === $status ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'severity' => $this->faker->randomElement(['info', 'warning', 'blocking']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
