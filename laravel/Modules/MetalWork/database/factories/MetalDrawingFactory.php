<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalDrawing;

/**
 * @extends Factory<MetalDrawing>
 */
final class MetalDrawingFactory extends Factory
{
    protected $model = MetalDrawing::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = $this->faker->unique()->numerify('35#######-#');

        return [
            'part_code' => $code,
            'name' => 'PIEGATO FISSAGGIO',
            'source_file' => $code.'_stp1.stp',
            'storage_path' => null,
            'content_hash' => hash('sha256', $code),
            'cad_system' => 'SIEMENS PLM Software NX2506.7002',
            'length_unit_code' => 'mm',
            'bbox_length_mm' => 500,
            'bbox_width_mm' => 280,
            'bbox_thickness_mm' => 10,
            'point_count' => 121,
            'face_count' => 22,
            'plane_count' => 12,
            'cylinder_count' => 10,
            'solid_count' => 1,
            'is_assembly' => false,
        ];
    }
}
