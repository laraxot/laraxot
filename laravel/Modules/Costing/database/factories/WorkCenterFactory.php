<?php

declare(strict_types=1);

namespace Modules\Costing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Costing\Models\WorkCenter;

/**
 * @extends Factory<WorkCenter>
 */
final class WorkCenterFactory extends Factory
{
    /** @var class-string<WorkCenter> */
    protected $model = WorkCenter::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'context_code' => 'signage',
            'code' => $this->faker->unique()->slug(2),
            'name' => $this->faker->words(2, true),
            'is_internal' => true,
            'hourly_rate' => 0,
            'default_markup_percent' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
