<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;

/**
 * @extends Factory<PublicProcurementTenderOpportunity>
 */
class PublicProcurementTenderOpportunityFactory extends Factory
{
    /** @var class-string<PublicProcurementTenderOpportunity> */
    protected $model = PublicProcurementTenderOpportunity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $published = $this->faker->dateTimeBetween('-2 months', 'now');

        return [
            'tender_source_id' => PublicProcurementTenderSource::factory(),
            'customer_id' => null,
            'status' => $this->faker->randomElement(['new', 'screening', 'eligible', 'discarded']),
            'external_id' => $this->faker->optional()->numerify('EXT-#######'),
            'source_url' => $this->faker->optional()->url(),
            'contracting_authority' => 'Comune di '.$this->faker->city(),
            'title' => 'Appalto '.$this->faker->words(4, true),
            'cig' => strtoupper($this->faker->bothify('?#########')),
            'cup' => strtoupper($this->faker->bothify('?##?######?####')),
            'cpv_code' => $this->faker->numerify('########-#'),
            'category' => $this->faker->randomElement(['OS30', 'OG1', 'OG11', 'Illuminazione pubblica']),
            'territory' => $this->faker->randomElement(['Lombardia', 'Piemonte', 'Veneto', 'Emilia-Romagna']),
            'estimated_amount' => $this->faker->randomFloat(2, 20000, 500000),
            'published_at' => $published,
            'submission_deadline_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'site_visit_due_at' => $this->faker->optional()->dateTimeBetween('now', '+2 weeks'),
            'keywords' => $this->faker->randomElements(
                ['scuola', 'palestra', 'impianti elettrici', 'OS30', 'illuminazione', 'manutenzione'],
                3,
            ),
            'ai_summary' => [
                'required_documents' => ['durc' => 'DURC valido', 'insurance' => 'Polizza assicurativa valida'],
                'requires_soa' => $this->faker->boolean(),
            ],
            'raw_excerpt' => $this->faker->paragraph(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
