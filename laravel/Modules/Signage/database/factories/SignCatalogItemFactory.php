<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;

/**
 * @extends Factory<SignCatalogItem>
 */
final class SignCatalogItemFactory extends Factory
{
    protected $model = SignCatalogItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sign_price_list_id' => SignPriceList::factory(),
            'code' => strtoupper($this->faker->unique()->bothify('??_?????_######')),
            'name' => $this->faker->words(3, true),
            'description' => null,
            'unit_code' => 'nr',
            'markup_percent' => null,
            'manual_sale_price' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
