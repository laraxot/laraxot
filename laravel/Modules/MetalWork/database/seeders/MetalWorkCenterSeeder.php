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

            $code = (string) $center['code'];

            WorkCenter::query()->firstOrCreate(
                ['context_code' => MetalWorksCosting::CONTEXT, 'code' => $code],
                [
                    'name' => (string) __('metalwork::metalwork.work_centers_list.'.(string) ($center['name_key'] ?? $code)),
                    'is_internal' => (bool) ($center['is_internal'] ?? true),
                    'hourly_rate' => (float) ($center['hourly_rate'] ?? 0),
                    'default_markup_percent' => isset($center['default_markup_percent'])
                        ? (float) $center['default_markup_percent']
                        : null,
                    'sort_order' => (int) ($center['sort_order'] ?? 0),
                    'is_active' => true,
                ],
            );
        }
    }
}
