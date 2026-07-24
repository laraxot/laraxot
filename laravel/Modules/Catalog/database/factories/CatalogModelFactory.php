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
}
