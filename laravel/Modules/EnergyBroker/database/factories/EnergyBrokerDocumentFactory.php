<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;

/**
 * @extends Factory<EnergyBrokerDocument>
 */
class EnergyBrokerDocumentFactory extends Factory
{
    /** @var class-string<EnergyBrokerDocument> */
    protected $model = EnergyBrokerDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'energy_broker_import_batch_id' => null,
            'customer_id' => null,
            'media_id' => null,
            'source_file_name' => $this->faker->slug(3).'.pdf',
            'file_hash' => $this->faker->sha256(),
            'document_type' => $this->faker->randomElement(['bill', 'contract', 'offer', 'unknown']),
            'service_type' => $this->faker->randomElement(['electricity', 'gas', null]),
            'supplier_name' => $this->faker->randomElement(['Enel Energia', 'Eni Plenitude', 'A2A Energia', 'Edison', 'Iren']),
            'analysis_status' => $this->faker->randomElement(['draft', 'processing', 'analyzed', 'reviewed', 'failed']),
            'confidence_score' => $this->faker->randomFloat(4, 0, 1),
            'raw_text' => $this->faker->optional()->paragraph(),
            'analysis_payload' => null,
            'normalized_payload' => null,
            'reviewed_payload' => null,
            'processed_at' => $this->faker->optional()->dateTimeThisYear(),
            'imported_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
