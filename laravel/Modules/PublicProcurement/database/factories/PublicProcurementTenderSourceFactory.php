<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;

/**
 * @extends Factory<PublicProcurementTenderSource>
 */
class PublicProcurementTenderSourceFactory extends Factory
{
    /** @var class-string<PublicProcurementTenderSource> */
    protected $model = PublicProcurementTenderSource::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Portale Gare '.$this->faker->unique()->city(),
            'source_type' => $this->faker->randomElement(['manual', 'portal', 'api']),
            'url' => $this->faker->optional()->url(),
            'is_active' => $this->faker->boolean(80),
            'filters' => [
                'keywords' => $this->faker->randomElements(
                    ['impianti elettrici', 'illuminazione pubblica', 'quadri elettrici', 'fotovoltaico'],
                    2,
                ),
                'territories' => $this->faker->randomElements(['Lombardia', 'Piemonte', 'Veneto', 'Emilia-Romagna'], 2),
            ],
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
