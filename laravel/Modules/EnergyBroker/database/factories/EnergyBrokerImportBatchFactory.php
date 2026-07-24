<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;

/**
 * @extends Factory<EnergyBrokerImportBatch>
 */
class EnergyBrokerImportBatchFactory extends Factory
{
    /** @var class-string<EnergyBrokerImportBatch> */
    protected $model = EnergyBrokerImportBatch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(1, 50);

        return [
            'uploaded_by_user_id' => null,
            'source_name' => 'Import '.$this->faker->dateTimeThisYear()->format('Y-m-d'),
            'source_type' => $this->faker->randomElement(['manual_upload', 'email', 'sftp', 'api']),
            'status' => $this->faker->randomElement(['draft', 'processing', 'completed', 'failed']),
            'total_files' => $total,
            'processed_files' => $this->faker->numberBetween(0, $total),
            'meta' => null,
        ];
    }
}
