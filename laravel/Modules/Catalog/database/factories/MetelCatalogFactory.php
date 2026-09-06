<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\MetelCatalog;

/**
 * @extends Factory<MetelCatalog>
 */
class MetelCatalogFactory extends Factory
{
    /** @var class-string<MetelCatalog> */
    protected $model = MetelCatalog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('cat-####'),
            'brand_code' => mb_strtoupper($this->faker->lexify('???')),
            'name' => 'Catalogo '.$this->faker->company(),
            'manufacturer' => $this->faker->company(),
            'source_type' => $this->faker->randomElement(['file', 'api', 'manual']),
            'version' => $this->faker->year().'/'.$this->faker->numerify('##'),
            'imported_at' => $this->faker->optional()->dateTimeThisYear(),
            'is_active' => true,
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, MetelCatalog>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return MetelCatalog
     */
    public function createOne(array $attributes = []): MetelCatalog
    {
        return parent::createOne($attributes);
    }
}
