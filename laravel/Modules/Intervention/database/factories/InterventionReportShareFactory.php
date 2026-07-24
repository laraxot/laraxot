<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionReportShare;

/**
 * @extends Factory<InterventionReportShare>
 */
class InterventionReportShareFactory extends Factory
{
    /** @var class-string<InterventionReportShare> */
    protected $model = InterventionReportShare::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'intervention_id' => Intervention::factory(),
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ];
    }
}
