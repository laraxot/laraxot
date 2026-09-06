<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
     * @return \Illuminate\Database\Eloquent\Collection<int, CatalogPartType>
     */
    public function create(array $attributes = []): \Illuminate\Database\Eloquent\Collection
    {
        return parent::create($attributes);
    }

    /**
     * @return CatalogPartType
     */
    public function createOne(array $attributes = []): CatalogPartType
    {
        return parent::createOne($attributes);
    }
}
