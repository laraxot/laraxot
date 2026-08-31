<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsInboundLot>
 */
final class WtsInboundLotFactory extends Factory
{
    protected $model = WtsInboundLot::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inbound_record_id' => WtsInboundRecord::factory(),
            'lot_code' => 'LOT-'.$this->faker->unique()->numerify('2026-####'),
            'status_code' => 'registered',
            'current_area_code' => 'incoming',
            'initial_units_count' => 0,
        ];
    }
}
