<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberCustomerPrice;
use Modules\Timber\Models\TimberProduct;

/**
 * @extends Factory<TimberCustomerPrice>
 */
final class TimberCustomerPriceFactory extends Factory
{
    protected $model = TimberCustomerPrice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => TimberCustomer::factory(),
            'product_id' => TimberProduct::factory(),
            'net_price' => $this->faker->randomFloat(4, 1, 50),
        ];
    }
}
