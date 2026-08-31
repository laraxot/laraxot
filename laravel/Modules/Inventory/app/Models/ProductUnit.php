<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Catalog\Models\Product;
use Modules\Inventory\Database\Factories\ProductUnitFactory;
use Modules\Xot\Models\XotBaseModel;

final class ProductUnit extends XotBaseModel
{

    protected $table = 'product_units';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** @return BelongsTo<Warehouse, $this> */
    public function currentWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'current_warehouse_id');
    }

    /** @return BelongsTo<WarehouseLocation, $this> */
    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'current_location_id');
    }

    /** @return BelongsToMany<InventoryMovement, $this> */
    public function inventoryMovements(): BelongsToMany
    {
        return $this->belongsToMany(InventoryMovement::class, 'inventory_movement_product_units');
    }
}
