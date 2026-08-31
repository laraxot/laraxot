<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsDerivedMaterial>
 */
final class WtsDerivedMaterialFactory extends Factory
{
    protected $model = WtsDerivedMaterial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'processing_step_id' => WtsProcessingStep::factory(),
            'technical_sheet_snapshot_id' => WtsTechnicalSheetSnapshot::factory(),
            'output_code' => 'OUT-'.$this->faker->unique()->numerify('2026-#####'),
            'output_type_code' => 'drum',
            'container_type_code' => 'drum',
            'quantity' => 1,
            'weight' => 25,
            'is_dirty' => false,
            'meta' => [],
        ];
    }
}
