<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementOfficialRecord;

/**
 * @extends Factory<PublicProcurementOfficialRecord>
 */
class PublicProcurementOfficialRecordFactory extends Factory
{
    /** @var class-string<PublicProcurementOfficialRecord> */
    protected $model = PublicProcurementOfficialRecord::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'media_id' => null,
            'type' => $this->faker->randomElement(['handover', 'suspension', 'resumption', 'testing']),
            'title' => 'Verbale '.$this->faker->words(3, true),
            'protocol_number' => $this->faker->optional()->numerify('PROT-#####/####'),
            'recorded_on' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'status' => $this->faker->randomElement(['draft', 'signed']),
            'summary' => $this->faker->optional()->paragraph(),
        ];
    }
}
