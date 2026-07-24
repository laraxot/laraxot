<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Inventory\Models\WarehouseLocation;

final class EditInventoryMovement extends EditRecord
{
    protected static string $resource = InventoryMovementResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $productId = (int) ($data['product_id'] ?? 0);
        $quantity = (int) ($data['quantity'] ?? 0);
        $unitPrice = $data['unit_price'] ?? null;

        if (! is_numeric($unitPrice) && $productId > 0) {
            $product = Product::query()->find($productId);
            $unitPrice = $product?->getAttribute('unit_price');
        }

        $resolvedUnitPrice = is_numeric($unitPrice) ? round((float) $unitPrice, 2) : 0.0;

        $data['unit_price'] = $resolvedUnitPrice;
        $data['line_total'] = round($resolvedUnitPrice * $quantity, 2);

        $this->assertLocationBelongsToWarehouse(
            (int) ($data['from_warehouse_id'] ?? 0),
            (int) ($data['from_location_id'] ?? 0),
            'from_location_id',
        );
        $this->assertLocationBelongsToWarehouse(
            (int) ($data['to_warehouse_id'] ?? 0),
            (int) ($data['to_location_id'] ?? 0),
            'to_location_id',
        );

        return $data;
    }

    private function assertLocationBelongsToWarehouse(int $warehouseId, int $locationId, string $field): void
    {
        if ($locationId <= 0) {
            return;
        }

        $location = WarehouseLocation::query()->find($locationId);
        if (! $location instanceof WarehouseLocation || (int) $location->warehouse_id !== $warehouseId) {
            throw ValidationException::withMessages([
                $field => __('inventory.validation.location_mismatch'),
            ]);
        }
    }
}
