<?php

declare(strict_types=1);

namespace Modules\Signature\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Signature\Models\Sketch;

/**
 * @extends Factory<Sketch>
 */
class SketchFactory extends Factory
{
    /** @var class-string<Sketch> */
    protected $model = Sketch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sketchable_type' => 'Modules\Intervention\Models\Intervention',
            'sketchable_id' => 1,
            'user_id' => 1,
            'disk' => 'public',
            'path' => null,
            'mime_type' => 'image/png',
            'payload' => [],
        ];
    }
}
