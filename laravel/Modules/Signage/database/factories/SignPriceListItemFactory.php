<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignPriceListItem;

/**
 * @extends Factory<SignPriceListItem>
 */
final class SignPriceListItemFactory extends Factory
{
    protected $model = SignPriceListItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('??-####')),
            'name' => $this->faker->words(3, true),
            'category_code' => $this->faker->randomElement(['material', 'machining', 'labor', 'led', 'cable', 'fixing']),
            'supplier_name' => $this->faker->optional()->company(),
            'supplier_code' => $this->faker->optional()->bothify('####'),
            'unit_code' => $this->faker->randomElement(['mq', 'mt', 'min', 'nr', 'kg']),
            'unit_cost' => $this->faker->randomFloat(4, 0.5, 500),
            'currency' => 'EUR',
            'is_active' => true,
        ];
    }
}
