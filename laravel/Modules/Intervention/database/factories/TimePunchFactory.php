<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;

/**
 * @extends Factory<TimePunch>
 */
class TimePunchFactory extends Factory
{
    /** @var class-string<TimePunch> */
    protected $model = TimePunch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intervention_id' => Intervention::factory(),
            'user_id' => 1,
            'started_at' => now()->subHour(),
            'ended_at' => now(),
        ];
    }
}
