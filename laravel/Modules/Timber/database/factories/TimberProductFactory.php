<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberProduct;

/**
 * @extends Factory<TimberProduct>
 */
final class TimberProductFactory extends Factory
{
    protected $model = TimberProduct::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('PF-####')),
            'description' => $this->faker->words(3, true),
            'product_type_code' => 'lot_managed',
            'category' => $this->faker->randomElement(['Sboli legnami', 'Smokey Ben']),
            'unit_of_measure' => 'quintali',
            'list_prices' => [
                ['price_list_code' => 'ben_b2c', 'net_price' => 2.35],
            ],
            'barcode' => $this->faker->unique()->numerify('#############'),
            'vat_rate_code' => '10',
            'supplier_net_price' => $this->faker->randomFloat(4, 1, 50),
            'is_active' => true,
        ];
    }
}
