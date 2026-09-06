<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
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
     * @return Collection<int, CatalogModel>
     */
    public function create(mixed $attributes = [], ?Model $parent = null): Collection
    {
        return parent::create($attributes, $parent);
    }

    public function createOne(mixed $attributes = [], ?Model $parent = null): CatalogModel
    {
        return parent::createOne($attributes, $parent);
    }
}
