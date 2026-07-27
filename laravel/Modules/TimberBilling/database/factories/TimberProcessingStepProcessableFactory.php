<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\TimberBilling\Models\TimberProcessingStepProcessable;

/**
 * @extends Factory<TimberProcessingStepProcessable>
 */
final class TimberProcessingStepProcessableFactory extends Factory
{
    protected $model = TimberProcessingStepProcessable::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'processing_step_id' => TimberProcessingStep::factory(),
            'processable_type' => TimberLot::class,
            'processable_id' => TimberLot::factory(),
        ];
    }
}
