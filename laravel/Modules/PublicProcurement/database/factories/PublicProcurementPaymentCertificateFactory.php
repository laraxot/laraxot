<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementPaymentCertificate;

/**
 * @extends Factory<PublicProcurementPaymentCertificate>
 */
class PublicProcurementPaymentCertificateFactory extends Factory
{
    /** @var class-string<PublicProcurementPaymentCertificate> */
    protected $model = PublicProcurementPaymentCertificate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'progress_report_id' => null,
            'invoice_id' => null,
            'number' => (string) $this->faker->unique()->numberBetween(1, 9999),
            'status' => $this->faker->randomElement(['draft', 'issued', 'paid']),
            'issued_on' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'amount' => $this->faker->randomFloat(2, 5000, 150000),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
