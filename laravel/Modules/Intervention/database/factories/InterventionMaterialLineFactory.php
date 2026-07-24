<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;

/**
 * @extends Factory<InterventionMaterialLine>
 */
class InterventionMaterialLineFactory extends Factory
{
    /** @var class-string<InterventionMaterialLine> */
    protected $model = InterventionMaterialLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intervention_id' => Intervention::factory(),
            'description' => $this->faker->words(3, true),
            'quantity' => 1,
        ];
    }
}
