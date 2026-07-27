<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementGuarantee;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

/**
 * @extends Factory<PublicProcurementGuarantee>
 */
class PublicProcurementGuaranteeFactory extends Factory
{
    /** @var class-string<PublicProcurementGuarantee> */
    protected $model = PublicProcurementGuarantee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issued = $this->faker->dateTimeBetween('-6 months', '-1 month');

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'media_id' => null,
            'type' => $this->faker->randomElement(['provisional', 'definitive', 'advance_payment']),
            'title' => 'Cauzione '.SafeStringCastAction::cast($this->faker->words(2, true)),
            'provider' => $this->faker->company().' Assicurazioni',
            'policy_number' => $this->faker->bothify('POL-########'),
            'status' => $this->faker->randomElement(['valid', 'expired', 'released']),
            'amount' => $this->faker->randomFloat(2, 5000, 100000),
            'issued_on' => $issued,
            'expires_on' => $this->faker->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
