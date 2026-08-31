<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsInboundRecord>
 */
final class WtsInboundRecordFactory extends Factory
{
    protected $model = WtsInboundRecord::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legacy_reference' => 'ECOS-'.$this->faker->unique()->numberBetween(1000, 9999),
            'form_number' => 'FIR-'.$this->faker->unique()->numerify('2026-###'),
            'source_company_name' => $this->faker->company(),
            'commercial_company_name' => null,
            'vehicle_reference' => strtoupper($this->faker->bothify('??###??')),
            'gross_quantity' => 1250,
            'tare_weight' => null,
            'net_weight' => null,
            'entry_weight' => null,
            'status_code' => 'registered',
            'acceptance_status' => null,
            'technical_sheet_snapshot_id' => WtsTechnicalSheetSnapshot::factory(),
            'received_at' => now(),
            'taken_charge_at' => null,
            'notes' => null,
        ];
    }
}
