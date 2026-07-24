<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionUserAssignment;

/**
 * @extends Factory<InterventionUserAssignment>
 */
class InterventionUserAssignmentFactory extends Factory
{
    /** @var class-string<InterventionUserAssignment> */
    protected $model = InterventionUserAssignment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intervention_id' => Intervention::factory(),
            'user_id' => 1,
        ];
    }
}
