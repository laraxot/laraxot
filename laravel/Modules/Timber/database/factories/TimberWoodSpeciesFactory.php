<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Timber\Models\TimberWoodSpecies;

/**
 * @extends Factory<TimberWoodSpecies>
 */
final class TimberWoodSpeciesFactory extends Factory
{
    protected $model = TimberWoodSpecies::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'code' => Str::slug($name),
            'name' => ucfirst($name),
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
