<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;

/**
 * @extends Factory<PublicProcurementPriceItem>
 */
class PublicProcurementPriceItemFactory extends Factory
{
    /** @var class-string<PublicProcurementPriceItem> */
    protected $model = PublicProcurementPriceItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(3, 1, 500);
        $unitPrice = $this->faker->randomFloat(4, 5, 5000);

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'code' => strtoupper($this->faker->bothify('OS30.###')),
            'category' => $this->faker->randomElement(['Quadri elettrici', 'Illuminazione LED', 'Cavidotti', 'Opere murarie']),
            'description' => $this->faker->sentence(8),
            'unit' => $this->faker->randomElement(['cad', 'mq', 'ml', 'kg', 'h']),
            'planned_quantity' => $quantity,
            'unit_price' => $unitPrice,
            'planned_total' => round($quantity * $unitPrice, 2),
            'meta' => ['price_list' => $this->faker->randomElement(['regional', 'internal'])],
        ];
    }
}
