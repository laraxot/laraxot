<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WtsUnit>
 */
final class WtsUnitFactory extends Factory
{
    protected $model = WtsUnit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitCode = 'UNIT-'.$this->faker->unique()->numerify('2026-#####');

        return [
            'inbound_lot_id' => WtsInboundLot::factory(),
            'technical_sheet_snapshot_id' => WtsTechnicalSheetSnapshot::factory(),
            'unit_code' => $unitCode,
            'qr_code' => 'WTS-QR-'.Str::upper(Str::slug($unitCode, '')),
            'unit_type_code' => 'big_bag',
            'status_code' => 'available',
            'current_area_code' => 'to_process',
            'location_label' => null,
            'gross_weight' => null,
            'is_banned' => false,
            'generated_sequence' => 1,
            'meta' => [],
        ];
    }
}
