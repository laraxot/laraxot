<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Costing\Models\WorkCenter;
use Modules\MetalWork\Support\MetalWorksCosting;

/**
 * Seeds the workshop's cost centres from config, without touching the rates an
 * operator has already corrected: config holds the starting point, the table
 * holds the truth.
 */
final class MetalWorkCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = config('metalwork.work_centers', []);

        if (! is_array($centers)) {
            return;
        }

        foreach ($centers as $center) {
            if (! is_array($center) || ! isset($center['code'])) {
                continue;
            }

            $code = $this->stringValue($center, 'code');

            if ($code === '') {
                continue;
            }

            WorkCenter::query()->firstOrCreate(
                ['context_code' => MetalWorksCosting::CONTEXT, 'code' => $code],
                [
                    'name' => (string) __('metalwork::metalwork.work_centers_list.'.$this->stringValueOrFallback($center, 'name_key', $code)),
                    'is_internal' => (bool) ($center['is_internal'] ?? true),
                    'hourly_rate' => $this->floatValue($center, 'hourly_rate', 0.0),
                    'default_markup_percent' => isset($center['default_markup_percent'])
                        ? $this->floatValue($center, 'default_markup_percent', 0.0)
                        : null,
                    'sort_order' => $this->intValue($center, 'sort_order', 0),
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * @param  array<array-key, mixed>  $center
     */
    private function stringValue(array $center, string $key): string
    {
        $value = $center[$key] ?? null;

        return is_string($value) ? $value : '';
    }

    /**
     * @param  array<array-key, mixed>  $center
     */
    private function stringValueOrFallback(array $center, string $key, string $fallback): string
    {
        $value = $center[$key] ?? null;

        return is_string($value) && $value !== '' ? $value : $fallback;
    }

    /**
     * @param  array<array-key, mixed>  $center
     */
    private function floatValue(array $center, string $key, float $fallback): float
    {
        $value = $center[$key] ?? null;

        return is_numeric($value) ? (float) $value : $fallback;
    }

    /**
     * @param  array<array-key, mixed>  $center
     */
    private function intValue(array $center, string $key, int $fallback): int
    {
        $value = $center[$key] ?? null;

        return is_numeric($value) ? (int) $value : $fallback;
    }
}
