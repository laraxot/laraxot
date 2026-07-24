<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;

/**
 * @extends Factory<TimberStockMovement>
 */
final class TimberStockMovementFactory extends Factory
{
    protected $model = TimberStockMovement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movement_date' => now()->toDateString(),
            'product_id' => TimberProduct::factory(),
            'direction_code' => 'load',
            'quantity' => $this->faker->randomFloat(3, 1, 500),
            'unit_of_measure' => 'quintali',
            'document_reference' => 'DDT '.$this->faker->numberBetween(100, 300).' del '.now()->format('d/m'),
            'lot_code' => 'LOT-2026-'.$this->faker->unique()->numerify('####'),
            'category' => 'Sboli legnami',
        ];
    }
}
