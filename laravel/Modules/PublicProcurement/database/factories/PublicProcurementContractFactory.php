<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;

/**
 * @extends Factory<PublicProcurementContract>
 */
class PublicProcurementContractFactory extends Factory
{
    /** @var class-string<PublicProcurementContract> */
    protected $model = PublicProcurementContract::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 50000, 800000);
        $awarded = $this->faker->dateTimeBetween('-6 months', '-1 month');

        return [
            'work_order_id' => null,
            'tender_opportunity_id' => null,
            'customer_id' => null,
            'status' => $this->faker->randomElement(['draft', 'active', 'completed']),
            'code' => strtoupper($this->faker->unique()->bothify('APP-####-??')),
            'title' => 'Contratto '.$this->faker->words(4, true),
            'contracting_authority' => 'Comune di '.$this->faker->city(),
            'procedure_type' => $this->faker->randomElement(['open', 'restricted', 'negotiated', 'direct']),
            'cig' => strtoupper($this->faker->bothify('?#########')),
            'cup' => strtoupper($this->faker->bothify('?##?######?####')),
            'rup_name' => $this->faker->name(),
            'works_director_name' => $this->faker->name(),
            'safety_coordinator_name' => $this->faker->name(),
            'awarded_on' => $awarded,
            'signed_on' => $this->faker->dateTimeBetween($awarded, 'now'),
            'site_handover_on' => $this->faker->optional()->dateTimeBetween($awarded, 'now'),
            'works_start_on' => $this->faker->optional()->dateTimeBetween($awarded, 'now'),
            'works_due_on' => $this->faker->dateTimeBetween('now', '+8 months'),
            'works_completed_on' => null,
            'contract_amount' => $amount,
            'security_costs' => $this->faker->randomFloat(2, 500, $amount * 0.05),
            'vat_rate' => 22,
            'discount_rate' => $this->faker->randomFloat(3, 0, 25),
            'retention_rate' => $this->faker->randomFloat(3, 0, 1),
            'meta' => ['funding' => $this->faker->randomElement(['pnrr', 'ordinary', 'regional'])],
            'description' => $this->faker->paragraph(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
