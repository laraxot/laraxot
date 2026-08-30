<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignCatalogItemComponent;

/**
 * @extends Factory<SignCatalogItemComponent>
 */
final class SignCatalogItemComponentFactory extends Factory
{
    protected $model = SignCatalogItemComponent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sign_catalog_item_id' => SignCatalogItem::factory(),
            'position' => 10,
            'work_center_code' => 'material',
            'sign_price_list_item_id' => null,
            'description' => $this->faker->words(4, true),
            'unit_code' => 'nr',
            'quantity' => 1,
            'width_mm' => null,
            'height_mm' => null,
            'unit_cost' => $this->faker->randomFloat(4, 1, 200),
        ];
    }
}
