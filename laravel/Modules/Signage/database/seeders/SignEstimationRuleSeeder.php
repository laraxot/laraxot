<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Signage\Models\SignEstimationRule;

/**
 * Default quantity-estimation rules. These are STARTING conventions (partly
 * inferred from the drawings, e.g. "2m cable per letter", "min 60 min/letter"),
 * meant to be tuned by Signage from the UI — not measured production data.
 * Idempotent (keyed on role_code).
 */
final class SignEstimationRuleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->rules() as $rule) {
            SignEstimationRule::query()->updateOrCreate(['role_code' => $rule['role_code']], $rule);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function rules(): array
    {
        return [
            // Sheet materials: bounding-box area in m².
            ['role_code' => 'body', 'unit_code' => 'mq', 'strategy_code' => 'area', 'factor' => 1, 'base_value' => 0,
                'notes' => 'Area lastra = larghezza × altezza × pezzi.'],
            ['role_code' => 'face', 'unit_code' => 'mq', 'strategy_code' => 'area', 'factor' => 1, 'base_value' => 0,
                'notes' => 'Area lastra frontale = larghezza × altezza × pezzi.'],
            ['role_code' => 'paint', 'unit_code' => 'mq', 'strategy_code' => 'area', 'factor' => 1, 'base_value' => 0,
                'notes' => 'Superficie da verniciare ≈ area insegna.'],

            // Milling / LED: length in metres from the bounding-box perimeter.
            ['role_code' => 'cutting', 'unit_code' => 'mt', 'strategy_code' => 'perimeter', 'factor' => 1, 'base_value' => 0,
                'notes' => 'Sviluppo taglio ≈ perimetro (2×(L+H)) × pezzi. Da tarare sul reale.'],
            ['role_code' => 'led', 'unit_code' => 'mt', 'strategy_code' => 'perimeter', 'factor' => 0.9, 'base_value' => 0,
                'notes' => 'Striscia LED ≈ 90% del perimetro × pezzi. Da tarare.'],

            // Per-piece constants.
            ['role_code' => 'cable', 'unit_code' => 'mt', 'strategy_code' => 'constant', 'factor' => 0, 'base_value' => 2,
                'notes' => '2 m di cavo per pezzo (da nota disegno "2m per letter").'],
            ['role_code' => 'fixing', 'unit_code' => 'nr', 'strategy_code' => 'constant', 'factor' => 0, 'base_value' => 4,
                'notes' => '4 perni/prigionieri per pezzo (default, da tarare).'],
        ];
    }
}
