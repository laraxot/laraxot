<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\CatalogMeasureUnit;

/**
 * @extends Factory<CatalogMeasureUnit>
 */
class CatalogMeasureUnitFactory extends Factory
{
    /** @var class-string<CatalogMeasureUnit> */
    protected $model = CatalogMeasureUnit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var array{0: string, 1: string} $unit */
        $unit = $this->faker->unique()->randomElement([
            ['Pezzo', 'PZ'],
            ['Metro', 'MT'],
            ['Metro quadrato', 'MQ'],
            ['Metro cubo', 'MC'],
            ['Kilogrammo', 'KG'],
            ['Litro', 'LT'],
            ['Confezione', 'CF'],
            ['Ora', 'H'],
            ['Coppia', 'CP'],
        ]);

        return [
            'name' => $unit[0],
            'symbol' => $unit[1],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, CatalogMeasureUnit>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @return CatalogMeasureUnit
     */
    public function createOne(array $attributes = []): CatalogMeasureUnit
    {
        return parent::createOne($attributes);
    }
}
