<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalEstimateLine;

/**
 * @extends Factory<MetalEstimateLine>
 */
class MetalEstimateLineFactory extends Factory
{
    /** @var class-string<MetalEstimateLine> */
    protected $model = MetalEstimateLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
