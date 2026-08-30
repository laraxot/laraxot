<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MetalWork\Models\MetalRawMaterial;

/**
 * @extends Factory<MetalRawMaterial>
 */
final class MetalRawMaterialFactory extends Factory
{
    protected $model = MetalRawMaterial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => mb_strtoupper($this->faker->unique()->bothify('FP###X##')),
            'description' => 'FERRO PIATTO '.$this->faker->numberBetween(50, 200).'X'.$this->faker->numberBetween(5, 30),
            'supplier_name' => $this->faker->company(),
            'family_code' => 'flat',
            'unit_code' => 'kg',
            'steel_grade' => 'S275JR',
            'is_active' => true,
        ];
    }
}
