<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;

/**
 * @extends Factory<MetelCatalogItem>
 */
class MetelCatalogItemFactory extends Factory
{
    /** @var class-string<MetelCatalogItem> */
    protected $model = MetelCatalogItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gross = $this->faker->randomFloat(4, 0.5, 500);

        return [
            'metel_catalog_id' => MetelCatalog::factory(),
            'product_id' => null,
            'metel_code' => mb_strtoupper($this->faker->unique()->bothify('MTL#######')),
            'barcode' => $this->faker->optional()->ean13(),
            'etim_code' => $this->faker->optional()->bothify('EC######'),
            'description' => $this->faker->sentence(4),
            'unit_of_measure' => $this->faker->randomElement(['PZ', 'MT', 'KG', 'LT', 'CF']),
            'packaging' => (string) $this->faker->randomElement([1, 10, 25, 50, 100]),
            'gross_price' => $gross,
            'net_price' => round($gross * $this->faker->randomFloat(2, 0.4, 0.9), 4),
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, MetelCatalogItem>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return MetelCatalogItem
     */
    public function createOne(array $attributes = []): MetelCatalogItem
    {
        return parent::createOne($attributes);
    }
}
