<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
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
     * @return Collection<int, CatalogMeasureUnit>|CatalogMeasureUnit
     */
    public function create(mixed $attributes = [], ?Model $parent = null): Collection|CatalogMeasureUnit
    {
        return parent::create($attributes, $parent);
    }

    public function createOne(mixed $attributes = [], ?Model $parent = null): CatalogMeasureUnit
    {
        return parent::createOne($attributes);
    }
}
