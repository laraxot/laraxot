<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\CatalogFrequency;

/**
 * @extends Factory<CatalogFrequency>
 */
class CatalogFrequencyFactory extends Factory
{
    /** @var class-string<CatalogFrequency> */
    protected $model = CatalogFrequency::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Giornaliera',
                'Settimanale',
                'Quindicinale',
                'Mensile',
                'Bimestrale',
                'Trimestrale',
                'Semestrale',
                'Annuale',
                'Biennale',
            ]),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
