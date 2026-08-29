<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Timber\Models\TimberLabelPrint;
use Modules\Timber\Models\TimberLot;

/**
 * @extends Factory<TimberLabelPrint>
 */
final class TimberLabelPrintFactory extends Factory
{
    protected $model = TimberLabelPrint::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $labelCode = 'LBL-'.$this->faker->unique()->numerify('2026-#####');

        return [
            'labelable_type' => TimberLot::class,
            'labelable_id' => TimberLot::factory(),
            'label_code' => $labelCode,
            'qr_payload' => 'TMB|LOTTO='.Str::upper(Str::slug($labelCode, '')),
            'print_reason_code' => 'initial_print',
            'printed_at' => now(),
        ];
    }
}
