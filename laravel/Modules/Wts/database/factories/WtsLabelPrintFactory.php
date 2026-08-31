<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsLabelPrint;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsLabelPrint>
 */
final class WtsLabelPrintFactory extends Factory
{
    protected $model = WtsLabelPrint::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'labelable_type' => WtsUnit::class,
            'labelable_id' => WtsUnit::factory(),
            'label_code' => 'LBL-'.$this->faker->unique()->numerify('2026-#####'),
            'qr_payload' => 'WTS-QR-'.$this->faker->unique()->numerify('#####'),
            'print_reason_code' => 'initial_print',
            'printed_by_user_id' => null,
            'printed_at' => now(),
        ];
    }
}
