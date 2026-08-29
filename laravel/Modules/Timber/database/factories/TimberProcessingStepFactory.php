<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberProcessingStep;

/**
 * @extends Factory<TimberProcessingStep>
 */
final class TimberProcessingStepFactory extends Factory
{
    protected $model = TimberProcessingStep::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_sheet_code' => 'WS-'.$this->faker->unique()->numerify('2026-#####'),
            'step_type_code' => 'taglio',
            'input_quantity' => $this->faker->randomFloat(2, 100, 1000),
            'output_quantity' => $this->faker->randomFloat(2, 80, 900),
            'waste_quantity' => $this->faker->randomFloat(2, 5, 50),
            'recoverable_quantity' => $this->faker->randomFloat(2, 0, 20),
            'unit_of_measure' => 'kg',
            'executed_at' => now(),
            'notes' => null,
            'meta' => [],
        ];
    }
}
