<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Database\Factories\InventoryMovementFactory;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int|null $product_id
 * @property int|null $intervention_id
 * @property int|null $work_order_id
 * @property int|null $to_warehouse_id
 * @property int|null $from_warehouse_id
 * @property string|null $movement_type
 * @property int|float|string|null $quantity
 * @property numeric-string|float|null $unit_price
 * @property numeric-string|float|null $line_total
 * @property Carbon|null $occurred_at
 * @property-read Product|null $product
 */
final class InventoryMovement extends XotBaseModel
{
    /** @use HasFactory<InventoryMovementFactory> */
    use HasFactory;

    protected $table = 'inventory_movements';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'origin_auto' => 'boolean',
            'occurred_at' => 'datetime',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** @return BelongsTo<Warehouse, $this> */
    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    /** @return BelongsTo<WarehouseLocation, $this> */
    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'from_location_id');
    }

    /** @return BelongsTo<Warehouse, $this> */
    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    /** @return BelongsTo<WarehouseLocation, $this> */
    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'to_location_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return BelongsToMany<ProductUnit, $this> */
    public function productUnits(): BelongsToMany
    {
        return $this->belongsToMany(ProductUnit::class, 'inventory_movement_product_units');
    }
}
