<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberPriceListVersion;

/**
 * @extends Factory<TimberPriceListVersion>
 */
final class TimberPriceListVersionFactory extends Factory
{
    protected $model = TimberPriceListVersion::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price_list_id' => TimberPriceList::factory(),
            'items' => [
                ['product_code' => $this->faker->bothify('PF-####'), 'net_price' => $this->faker->randomFloat(4, 1, 50)],
                ['product_code' => $this->faker->bothify('PF-####'), 'net_price' => $this->faker->randomFloat(4, 1, 50)],
            ],
            'note' => $this->faker->optional()->sentence(),
            'created_by_user_id' => null,
        ];
    }
}
