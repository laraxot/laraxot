<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignPriceList;

/**
 * @extends Factory<SignPriceList>
 */
final class SignPriceListFactory extends Factory
{
    protected $model = SignPriceList::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('??_????')),
            'name' => $this->faker->words(2, true),
            'brand' => $this->faker->optional()->company(),
            'customer_id' => null,
            'default_markup_percent' => null,
            'is_active' => true,
        ];
    }
}
