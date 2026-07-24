<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementEconomicFrame;

/**
 * @extends Factory<PublicProcurementEconomicFrame>
 */
class PublicProcurementEconomicFrameFactory extends Factory
{
    /** @var class-string<PublicProcurementEconomicFrame> */
    protected $model = PublicProcurementEconomicFrame::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $works = $this->faker->randomFloat(2, 50000, 500000);
        $security = $this->faker->randomFloat(2, 1000, $works * 0.05);
        $available = $this->faker->randomFloat(2, 1000, $works * 0.1);
        $vat = round(($works + $security) * 0.22, 2);

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'label' => $this->faker->randomElement(['Quadro economico base', 'Quadro economico rimodulato', 'Variante']),
            'works_amount' => $works,
            'security_amount' => $security,
            'available_sums' => $available,
            'vat_amount' => $vat,
            'total_amount' => round($works + $security + $available + $vat, 2),
            'meta' => ['revision' => $this->faker->numberBetween(0, 3)],
        ];
    }
}
