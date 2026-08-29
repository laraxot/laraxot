<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberPackagedUnit;

/**
 * @extends Factory<TimberPackagedUnit>
 */
final class TimberPackagedUnitFactory extends Factory
{
    protected $model = TimberPackagedUnit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packageCode = 'PKG-'.$this->faker->unique()->numerify('2026-#####');

        return [
            'derived_material_id' => TimberDerivedMaterial::factory(),
            'package_code' => $packageCode,
            'qr_code' => 'TMB-QR-'.Str::upper(Str::slug($packageCode, '')),
            'package_type_code' => 'bancale',
            'quantity' => $this->faker->randomFloat(2, 1, 50),
            'unit_of_measure' => 'unita',
            'status_code' => 'pronto_per_vendita',
        ];
    }
}
