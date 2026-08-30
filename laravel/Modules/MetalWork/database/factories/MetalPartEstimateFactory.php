<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalPartEstimate;

/**
 * @extends Factory<MetalPartEstimate>
 */
final class MetalPartEstimateFactory extends Factory
{
    protected $model = MetalPartEstimate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'drawing_id' => MetalDrawing::factory(),
            'part_code' => $this->faker->unique()->numerify('35#######-#'),
            'name' => 'VASCA SCHIUMATRICE',
            'quantity' => 1,
            'net_weight_kg' => 10.0,
            'gross_weight_kg' => 11.2,
            'total_cost' => 100.0,
            'sale_price' => 125.0,
        ];
    }
}
