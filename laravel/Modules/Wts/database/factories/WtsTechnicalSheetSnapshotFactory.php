<?php

declare(strict_types=1);

namespace Modules\Wts\Database\Factories;

use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WtsTechnicalSheetSnapshot>
 */
final class WtsTechnicalSheetSnapshotFactory extends Factory
{
    protected $model = WtsTechnicalSheetSnapshot::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legacy_system' => 'factory',
            'sheet_code' => 'ST-'.$this->faker->unique()->numerify('2026-#####'),
            'producer_name' => $this->faker->company(),
            'waste_code' => '15 01 10*',
            'hp_codes' => 'HP4, HP14',
            'packaging_description' => 'Imballaggi misti contaminati',
            'production_description' => 'Rifiuto da ciclo produttivo plastico',
            'note_1' => 'Linea trattamento 1',
            'note_2' => 'Controllare descrizione scheda',
            'legacy_payload' => [
                'document_type' => 'ING-FACTORY',
                'mte_dat_part' => now()->toDateTimeString(),
            ],
        ];
    }
}
