<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\CatalogModel;

/**
 * @extends Factory<CatalogModel>
 */
class CatalogModelFactory extends Factory
{
    /** @var class-string<CatalogModel> */
    protected $model = CatalogModel::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->bothify('Serie ?##-###'),
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return \Illuminate\Database\Eloquent\Collection<int, CatalogModel>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return CatalogModel
     */
    public function createOne(array $attributes = []): CatalogModel
    {
        return parent::createOne($attributes);
    }
}
