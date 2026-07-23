<?php

declare(strict_types=1);

namespace Modules\Inventory\Queries;

use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\ProductUnit;

final class GetStockSnapshotQuery
{
    /**
     * @return array{aggregate:int, units:int}
     */
    public function execute(int $productId, ?int $warehouseId = null, ?int $locationId = null): array
    {
        $aggregate = $this->computeAggregate($productId, $warehouseId, $locationId);
        $units = $this->countUnits($productId, $warehouseId, $locationId);

        return [
            'aggregate' => $aggregate,
            'units' => $units,
        ];
    }

    private function computeAggregate(int $productId, ?int $warehouseId, ?int $locationId): int
    {
        // Aggregate stock = sum(in) - sum(out) for the selected warehouse / location.
        // For transfers: to_* adds, from_* subtracts.
        $q = InventoryMovement::query()->where('product_id', $productId);

        if ($warehouseId !== null) {
            $q->where(function ($sub) use ($warehouseId): void {
                $sub->where('to_warehouse_id', $warehouseId)
                    ->orWhere('from_warehouse_id', $warehouseId);
            });
        }

        if ($locationId !== null) {
            $q->where(function ($sub) use ($locationId): void {
                $sub->where('to_location_id', $locationId)
                    ->orWhere('from_location_id', $locationId);
            });
        }

        // Use raw conditional sums for speed.
        $row = $q->selectRaw(
            'SUM(CASE WHEN to_warehouse_id IS NOT NULL
                    AND (? IS NULL OR to_warehouse_id = ?)
                    AND (? IS NULL OR to_location_id = ?)
                THEN quantity ELSE 0 END) AS qty_in,
             SUM(CASE WHEN from_warehouse_id IS NOT NULL
                    AND (? IS NULL OR from_warehouse_id = ?)
                    AND (? IS NULL OR from_location_id = ?)
                THEN quantity ELSE 0 END) AS qty_out',
            [
                $warehouseId,
                $warehouseId,
                $locationId,
                $locationId,
                $warehouseId,
                $warehouseId,
                $locationId,
                $locationId,
            ],
        )->first();

        $inRaw = $row?->getAttribute('qty_in');
        $outRaw = $row?->getAttribute('qty_out');

        $in = is_numeric($inRaw) ? (int) $inRaw : 0;
        $out = is_numeric($outRaw) ? (int) $outRaw : 0;

        return $in - $out;
    }

    private function countUnits(int $productId, ?int $warehouseId, ?int $locationId): int
    {
        $q = ProductUnit::query()
            ->where('product_id', $productId)
            ->where('status', 'available');

        if ($warehouseId !== null) {
            $q->where('current_warehouse_id', $warehouseId);
        }

        if ($locationId !== null) {
            $q->where('current_location_id', $locationId);
        }

        return (int) $q->count();
    }
}

