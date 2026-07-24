<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementTenderEligibilityCheck;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;

/**
 * @extends Factory<PublicProcurementTenderEligibilityCheck>
 */
class PublicProcurementTenderEligibilityCheckFactory extends Factory
{
    /** @var class-string<PublicProcurementTenderEligibilityCheck> */
    protected $model = PublicProcurementTenderEligibilityCheck::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tender_opportunity_id' => PublicProcurementTenderOpportunity::factory(),
            'public_procurement_contract_id' => null,
            'evaluated_by_user_id' => null,
            'status' => $this->faker->randomElement(['needs_review', 'eligible', 'not_eligible']),
            'score' => $this->faker->numberBetween(0, 100),
            'required_documents' => ['durc', 'insurance', 'soa'],
            'missing_documents' => $this->faker->randomElements(['durc', 'soa', 'site_visit'], 1),
            'expired_documents' => [],
            'warnings' => $this->faker->randomElements(['SOA in scadenza', 'Sopralluogo obbligatorio'], 1),
            'actions' => ['Verificare validità DURC'],
            'evaluated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'summary' => $this->faker->sentence(),
        ];
    }
}
