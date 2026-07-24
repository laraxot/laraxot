<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;

/**
 * @extends Factory<PublicProcurementAiExtraction>
 */
class PublicProcurementAiExtractionFactory extends Factory
{
    /** @var class-string<PublicProcurementAiExtraction> */
    protected $model = PublicProcurementAiExtraction::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => $this->faker->unique()->uuid(),
            'media_id' => null,
            'created_by_user_id' => null,
            'document_type' => $this->faker->randomElement(['bando', 'disciplinare', 'durc', 'soa', 'unknown']),
            'status' => $this->faker->randomElement(['draft', 'applied', 'expired']),
            'confidence' => $this->faker->randomFloat(2, 0, 100),
            'extracted_payload' => [
                'cig' => strtoupper($this->faker->bothify('?#########')),
                'contracting_authority' => 'Comune di '.$this->faker->city(),
                'estimated_amount' => $this->faker->randomFloat(2, 20000, 500000),
            ],
            'raw_text' => $this->faker->paragraphs(2, true),
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'applied_at' => null,
        ];
    }
}
