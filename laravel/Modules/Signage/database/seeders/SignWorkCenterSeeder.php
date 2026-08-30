<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Support\SignageCosting;

/**
 * Seeds the work centres from config. The list is drawn from what Signage
 * actually runs — the machines on their production boards (Meca, Zund, waterjet,
 * jigs, BlockOut), the distinta groupings in their gestionale (material,
 * labour, file setup) and the outsourced steps they name in the order flow
 * (polishing, galvanic, painting).
 *
 * Markups are deliberately left empty: they are the customer's figures, not
 * ours, and must be filled in from the UI. Idempotent (keyed on code).
 */
final class SignWorkCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = config('signage.work_centers', []);
        if (! is_array($centers)) {
            return;
        }

        foreach ($centers as $center) {
            if (! is_array($center) || ! isset($center['code'])) {
                continue;
            }

            $code = (string) $center['code'];
            $nameKey = isset($center['name_key']) ? (string) $center['name_key'] : $code;

            WorkCenter::query()->updateOrCreate(
                ['context_code' => SignageCosting::CONTEXT, 'code' => $code],
                [
                    'name' => (string) __('signage::signage.work_centers.'.$nameKey),
                    'is_internal' => (bool) ($center['is_internal'] ?? true),
                    'sort_order' => (int) ($center['sort_order'] ?? 0),
                    'is_active' => true,
                ],
            );
        }
    }
}
