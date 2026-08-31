<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsPrintJob;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsPrintJob>
 */
final class WtsPrintJobFactory extends Factory
{
    protected $model = WtsPrintJob::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wts_unit_id' => WtsUnit::factory(),
            'label_code' => 'LOT-'.$this->faker->unique()->numerify('2026-####').'-BIG-BAG-01',
            'zpl' => "^XA\n^FO20,20^A0N,30,30^FDTEST^FS\n^XZ",
            'copies' => 1,
            'status_code' => 'pending',
            'attempts' => 0,
        ];
    }
}
