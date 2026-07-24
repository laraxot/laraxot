<?php

declare(strict_types=1);

namespace Modules\Geo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Geo\Models\GeoNamesCap;

/** @extends Factory<GeoNamesCap> */
class GeoNamesCapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = GeoNamesCap::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_code' => 'IT',
            'postal_code' => $this->faker->numerify('#####'),
            'place_name' => $this->faker->city(),
            'admin_name1' => $this->faker->randomElement(['Lombardia', 'Lazio', 'Campania', 'Veneto', 'Piemonte']),
            'admin_code1' => $this->faker->randomElement(['LOM', 'LAZ', 'CAM', 'VEN', 'PIE']),
            'admin_name2' => $this->faker->randomElement(['Milano', 'Roma', 'Napoli', 'Venezia', 'Torino']),
            'admin_code2' => $this->faker->randomElement(['MI', 'RM', 'NA', 'VE', 'TO']),
            'admin_name3' => $this->faker->city(),
            'admin_code3' => (string) $this->faker->numberBetween(1, 999),
            'latitude' => $this->faker->latitude(36, 47),
            'longitude' => $this->faker->longitude(6, 18),
            'accuracy' => $this->faker->numberBetween(1, 6),
        ];
    }
}
