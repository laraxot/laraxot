<?php

declare(strict_types=1);

namespace Modules\Production\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Production\Models\ProductionPhase;

/**
 * @extends Factory<ProductionPhase>
 */
class ProductionPhaseFactory extends Factory
{
    /** @var class-string<ProductionPhase> */
    protected $model = ProductionPhase::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrderFactory::new()->createOne()->id,
            'name' => $this->faker->word(),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'status' => 'planned',
            'assigned_to_user_id' => null,
            'started_at' => null,
            'completed_at' => null,
        ];
    }
}
