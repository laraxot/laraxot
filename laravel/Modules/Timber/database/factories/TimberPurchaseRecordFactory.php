<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;

/**
 * @extends Factory<TimberPurchaseRecord>
 */
final class TimberPurchaseRecordFactory extends Factory
{
    protected $model = TimberPurchaseRecord::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => TimberSupplier::factory(),
            'transporter_id' => null,
            'origin_forest' => $this->faker->randomElement([
                'Bosco del Cansiglio', 'Foresta della Lessinia', 'Bosco di Paneveggio', 'Selva di Sila',
            ]),
            'ddt_number' => 'DDT-'.$this->faker->unique()->numerify('2026-#####'),
            'ddt_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'wood_species' => $this->faker->randomElement(['abete', 'faggio', 'quercia', 'pioppo', 'larice', 'castagno']),
            'quantity' => $this->faker->randomFloat(2, 500, 5000),
            'unit_of_measure' => 'kg',
            'gross_weight' => $this->faker->randomFloat(2, 500, 5000),
            'net_weight' => $this->faker->randomFloat(2, 450, 4800),
            'unit_purchase_price' => $this->faker->randomFloat(2, 0.08, 0.35),
            'certifications' => 'FSC',
            'photo_path' => null,
            'notes' => null,
            'status_code' => 'registered',
            'received_at' => now(),
        ];
    }
}
