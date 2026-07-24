<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\ComplianceDocument;

/**
 * @extends Factory<ComplianceDocument>
 */
class ComplianceDocumentFactory extends Factory
{
    /** @var class-string<ComplianceDocument> */
    protected $model = ComplianceDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['durc', 'insurance', 'certification', 'id_card', 'training', 'safety_plan']),
            'title' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['missing', 'valid', 'expired', 'waived']),
            'is_required' => $this->faker->boolean(80),
            'issued_on' => $this->faker->optional()->dateTimeBetween('-2 years', '-1 month'),
            'expires_on' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'work_order_id' => null,
            'customer_id' => null,
            'employee_id' => null,
            'vehicle_id' => null,
            'subcontractor_id' => null,
            'equipment_id' => null,
            'media_id' => null,
            'uploaded_by_user_id' => null,
            'extracted_text' => null,
            'ai_classification' => null,
            'ai_extracted_fields' => null,
            'ai_processed_at' => null,
            'meta' => null,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
