<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsNonConformityReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsNonConformityReport>
 */
final class WtsNonConformityReportFactory extends Factory
{
    protected $model = WtsNonConformityReport::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wts_inbound_lot_id' => WtsInboundLot::factory(),
            'wts_inbound_record_id' => null,
            'form_number' => 'FIR-'.$this->faker->unique()->numerify('2026-###'),
            'note' => 'Non conformita rilevata in scarico',
            'recipient_email' => 'qualita@wts.example',
            'reported_by_user_id' => null,
            'reported_at' => now(),
            'email_sent_at' => null,
        ];
    }
}
