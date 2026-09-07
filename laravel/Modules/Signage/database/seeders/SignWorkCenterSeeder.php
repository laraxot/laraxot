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
            $normalized = $this->normalizeCenter($center);
            if ($normalized === null) {
                continue;
            }

            WorkCenter::query()->updateOrCreate(
                ['context_code' => SignageCosting::CONTEXT, 'code' => $normalized['code']],
                [
                    'name' => (string) __('signage::signage.work_centers.'.$normalized['name_key']),
                    'is_internal' => $normalized['is_internal'],
                    'sort_order' => $normalized['sort_order'],
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Normalizes one raw config entry into the typed shape `updateOrCreate()`
     * needs, or returns null when the entry is missing the code it must be
     * keyed on.
     *
     * @return array{code: string, name_key: string, is_internal: bool, sort_order: int}|null
     */
    private function normalizeCenter(mixed $center): ?array
    {
        if (! is_array($center) || ! isset($center['code'])) {
            return null;
        }

        $codeRaw = $center['code'];
        if (! is_string($codeRaw) && ! is_int($codeRaw)) {
            return null;
        }
        $code = (string) $codeRaw;

        $nameKeyRaw = $center['name_key'] ?? null;
        $nameKey = is_string($nameKeyRaw) || is_int($nameKeyRaw) ? (string) $nameKeyRaw : $code;

        $sortOrderRaw = $center['sort_order'] ?? 0;

        return [
            'code' => $code,
            'name_key' => $nameKey,
            'is_internal' => (bool) ($center['is_internal'] ?? true),
            'sort_order' => is_numeric($sortOrderRaw) ? (int) $sortOrderRaw : 0,
        ];
    }
}
