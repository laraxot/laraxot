<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsProcessingStep>
 */
final class WtsProcessingStepFactory extends Factory
{
    protected $model = WtsProcessingStep::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_sheet_code' => 'WS-'.$this->faker->unique()->numerify('2026-#####'),
            'step_type_code' => 'sorting',
            'quantity_mode' => 'units',
            'selected_units_count' => 1,
            'input_weight' => 100,
            'output_weight' => null,
            'remainder_weight' => null,
            'remainder_waste_code' => '15 01 10*',
            'technical_sheet_snapshot_id' => WtsTechnicalSheetSnapshot::factory(),
            'executed_by_user_id' => null,
            'executed_at' => now(),
            'notes' => null,
            'meta' => [],
        ];
    }
}
