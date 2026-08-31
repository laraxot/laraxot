<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalDrawingComponent;

/**
 * @extends Factory<MetalDrawingComponent>
 */
class MetalDrawingComponentFactory extends Factory
{
    /** @var class-string<MetalDrawingComponent> */
    protected $model = MetalDrawingComponent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
