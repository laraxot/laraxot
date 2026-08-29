<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberProcessingStep;

/**
 * @extends Factory<TimberDerivedMaterial>
 */
final class TimberDerivedMaterialFactory extends Factory
{
    protected $model = TimberDerivedMaterial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'processing_step_id' => TimberProcessingStep::factory(),
            'output_code' => 'MAT-'.$this->faker->unique()->numerify('2026-#####'),
            'wood_species' => $this->faker->randomElement(['abete', 'faggio', 'quercia', 'pioppo']),
            'quantity' => $this->faker->randomFloat(2, 50, 800),
            'unit_of_measure' => 'kg',
            'status_code' => 'da_lavorare',
            'current_area_code' => 'zona_taglio',
            'is_waste' => false,
            'meta' => [],
        ];
    }
}
