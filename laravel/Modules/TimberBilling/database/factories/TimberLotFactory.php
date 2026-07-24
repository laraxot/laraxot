<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPurchaseRecord;

/**
 * @extends Factory<TimberLot>
 */
final class TimberLotFactory extends Factory
{
    protected $model = TimberLot::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_record_id' => TimberPurchaseRecord::factory(),
            'lot_code' => 'LOT-'.$this->faker->unique()->numerify('2026-####'),
            'status_code' => 'available',
            'current_area_code' => 'piazzale',
            'quantity_available' => $this->faker->randomFloat(2, 500, 5000),
            'unit_of_measure' => 'kg',
            'wood_species' => $this->faker->randomElement(['abete', 'faggio', 'quercia', 'pioppo']),
        ];
    }
}
