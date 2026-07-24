<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberPriceListItem;
use Modules\TimberBilling\Models\TimberProduct;

/**
 * @extends Factory<TimberPriceListItem>
 */
final class TimberPriceListItemFactory extends Factory
{
    protected $model = TimberPriceListItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price_list_id' => TimberPriceList::factory(),
            'product_id' => TimberProduct::factory(),
            'net_price' => $this->faker->randomFloat(4, 1, 50),
        ];
    }
}
