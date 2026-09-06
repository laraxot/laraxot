<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Models\CatalogPartType;

/**
 * @extends Factory<CatalogPartType>
 */
class CatalogPartTypeFactory extends Factory
{
    /** @var class-string<CatalogPartType> */
    protected $model = CatalogPartType::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Ricambio',
                'Consumabile',
                'Accessorio',
                'Componente',
                'Materiale di consumo',
                'Utensile',
                'Guarnizione',
                'Raccorderia',
                'Valvolame',
            ]),
        ];
    }

    /**
     * @return Collection<int, CatalogPartType>|CatalogPartType
     */
    public function create(mixed $attributes = [], ?Model $parent = null): Collection|CatalogPartType
    {
        return parent::create($attributes, $parent);
    }

    public function createOne(mixed $attributes = [], ?Model $parent = null): CatalogPartType
    {
        return parent::createOne($attributes);
    }
}
