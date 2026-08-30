<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Facades\DB;
use Modules\MetalWork\Datas\MetalCostLine;
use Modules\MetalWork\Datas\MetalPartCosting;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalEstimateLine;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\MetalWork\Models\MetalRawMaterial;

/**
 * Freezes a costing into a stored estimate, rows included.
 *
 * Prices move: the material row is worth what the last invoice said on the day
 * the quotation went out, not what it says months later when the customer calls
 * back. Storing the lines is what makes an old quotation explainable.
 */
final class StoreMetalPartEstimateAction
{
    /**
     * @param  array<string, mixed>  $inputs  the operator's choices, as passed to the costing
     */
    public function execute(
        MetalPartCosting $costing,
        ?MetalDrawing $drawing = null,
        ?MetalRawMaterial $material = null,
        array $inputs = [],
        ?int $userId = null,
    ): MetalPartEstimate {
        return DB::transaction(function () use ($costing, $drawing, $material, $inputs, $userId): MetalPartEstimate {
            $estimate = MetalPartEstimate::query()->create([
                'drawing_id' => $drawing?->id,
                'raw_material_id' => $material?->id,
                'part_code' => $costing->partCode,
                'name' => $costing->name,
                'quantity' => $costing->quantity,
                'thickness_mm' => $this->float($inputs, 'thickness_mm'),
                'occupancy_percent' => $this->float($inputs, 'occupancy_percent'),
                'scrap_percent' => $this->float($inputs, 'scrap_percent'),
                'net_weight_kg' => $costing->netWeightKg,
                'gross_weight_kg' => $costing->grossWeightKg,
                'total_cost' => $costing->totalCost(),
                'sale_price' => $costing->totalSalePrice(),
                'manual_sale_price' => $costing->isManualPrice ? $costing->unitSalePrice : null,
                'markup_percent' => $this->float($inputs, 'markup_percent'),
                'created_by' => $userId,
            ]);

            foreach ($costing->lines as $line) {
                MetalEstimateLine::query()->create([
                    'part_estimate_id' => $estimate->id,
                    ...$this->lineAttributes($line),
                ]);
            }

            return $estimate;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function lineAttributes(MetalCostLine $line): array
    {
        $attributes = $line->toArray();
        unset($attributes['line_margin']);

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $inputs
     */
    private function float(array $inputs, string $key): ?float
    {
        $value = $inputs[$key] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }
}
