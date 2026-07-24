<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementProgressReport;

/**
 * @extends Factory<PublicProcurementProgressReport>
 */
class PublicProcurementProgressReportFactory extends Factory
{
    /** @var class-string<PublicProcurementProgressReport> */
    protected $model = PublicProcurementProgressReport::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $work = $this->faker->randomFloat(2, 10000, 200000);
        $security = $this->faker->randomFloat(2, 500, $work * 0.05);
        $retention = round($work * 0.005, 2);
        $from = $this->faker->dateTimeBetween('-4 months', '-1 month');

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'number' => (string) $this->faker->unique()->numberBetween(1, 9999),
            'status' => $this->faker->randomElement(['draft', 'issued']),
            'issued_on' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'period_from' => $from,
            'period_to' => $this->faker->dateTimeBetween($from, 'now'),
            'work_amount' => $work,
            'security_amount' => $security,
            'retention_amount' => $retention,
            'certificate_amount' => round($work + $security - $retention, 2),
            'progress_percentage' => $this->faker->randomFloat(2, 0, 100),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
