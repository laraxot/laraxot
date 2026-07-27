<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementDocumentRequirement;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

/**
 * @extends Factory<PublicProcurementDocumentRequirement>
 */
class PublicProcurementDocumentRequirementFactory extends Factory
{
    /** @var class-string<PublicProcurementDocumentRequirement> */
    protected $model = PublicProcurementDocumentRequirement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = SafeStringCastAction::cast($this->faker->randomElement(['durc', 'soa', 'insurance', 'site_visit', 'financial_statement']));

        return [
            'public_procurement_contract_id' => PublicProcurementContract::factory(),
            'tender_opportunity_id' => null,
            'compliance_document_id' => null,
            'code' => $code,
            'title' => ucfirst(str_replace('_', ' ', $code)),
            'phase' => $this->faker->randomElement(['participation', 'execution']),
            'status' => $this->faker->randomElement(['missing', 'present', 'expired']),
            'severity' => $this->faker->randomElement(['info', 'warning', 'blocking']),
            'is_required' => $this->faker->boolean(80),
            'due_on' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'notes' => $this->faker->optional()->sentence(),
            'meta' => ['source' => 'demo'],
        ];
    }
}
