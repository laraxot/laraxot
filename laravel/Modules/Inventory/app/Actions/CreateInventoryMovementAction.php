<?php

declare(strict_types=1);

namespace Modules\Inventory\Actions;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\WarehouseLocation;

final class CreateInventoryMovementAction
{
    /**
     * @param array<int, int> $productUnitIds
     */
    public function execute(
        int $productId,
        string $movementType,
        int $quantity,
        ?int $fromWarehouseId,
        ?int $toWarehouseId,
        ?string $note,
        ?int $workOrderId,
        ?int $interventionId,
        ?int $createdByUserId,
        bool $originAuto = false,
        ?CarbonImmutable $occurredAt = null,
        ?float $unitPrice = null,
        array $productUnitIds = [],
        ?int $fromLocationId = null,
        ?int $toLocationId = null,
    ): InventoryMovement {
        $occurredAt = $occurredAt ?? CarbonImmutable::now();
        $resolvedUnitPrice = $unitPrice;

        $this->assertLocationMatchesWarehouse($fromWarehouseId, $fromLocationId, 'from');
        $this->assertLocationMatchesWarehouse($toWarehouseId, $toLocationId, 'to');

        if ($resolvedUnitPrice === null) {
            /** @var Product|null $product */
            $product = Product::query()->find($productId);
            $rawUnitPrice = $product?->unit_price;
            $resolvedUnitPrice = is_numeric($rawUnitPrice) ? (float) $rawUnitPrice : 0.0;
        }

        $lineTotal = round($resolvedUnitPrice * $quantity, 2);

        /** @var InventoryMovement $movement */
        $movement = InventoryMovement::query()->create([
            'product_id' => $productId,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'from_warehouse_id' => $fromWarehouseId,
            'from_location_id' => $fromLocationId,
            'to_warehouse_id' => $toWarehouseId,
            'to_location_id' => $toLocationId,
            'note' => $note,
            'work_order_id' => $workOrderId,
            'intervention_id' => $interventionId,
            'created_by_user_id' => $createdByUserId,
            'origin_auto' => $originAuto,
            'occurred_at' => $occurredAt,
            'unit_price' => $resolvedUnitPrice,
            'line_total' => $lineTotal,
        ]);

        if ($productUnitIds !== []) {
            $unitIds = array_values(array_unique(array_map('intval', $productUnitIds)));
            $movement->productUnits()->sync($unitIds);

            // Best-effort: update current warehouse for involved units
            if ($toWarehouseId !== null) {
                ProductUnit::query()
                    ->whereIn('id', $unitIds)
                    ->update([
                        'current_warehouse_id' => $toWarehouseId,
                        'current_location_id' => $toLocationId,
                    ]);
            }
        }

        return $movement;
    }

    private function assertLocationMatchesWarehouse(?int $warehouseId, ?int $locationId, string $direction): void
    {
        if ($locationId === null) {
            return;
        }

        if ($warehouseId === null) {
            throw new InvalidArgumentException("The {$direction} warehouse is required when a {$direction} location is provided.");
        }

        /** @var WarehouseLocation|null $location */
        $location = WarehouseLocation::query()->find($locationId);
        if (! $location instanceof WarehouseLocation || (int) $location->warehouse_id !== $warehouseId) {
            throw new InvalidArgumentException("The selected {$direction} location does not belong to the selected warehouse.");
        }
    }
}

